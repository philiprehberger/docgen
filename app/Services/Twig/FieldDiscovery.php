<?php

namespace App\Services\Twig;

use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Node\Expression\AssignNameExpression;
use Twig\Node\Expression\GetAttrExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\ForNode;
use Twig\Node\Node;
use Twig\Source;

/**
 * Walk a parsed Twig template and infer the merge-field schema.
 *
 * The output is a tree of {name, type, children, item_type} nodes describing
 * every variable referenced in the template. Types are inferred from usage:
 *
 * - {% for x in items %}      → items is `array`
 * - {{ user.name }}           → user is `object`, with child `name`
 * - {{ user.addresses[0] }}   → addresses is `array`
 * - {{ client_name }}         → client_name is `scalar`
 *
 * Variables introduced by {% set %} or used as loop variables are tracked
 * as "locals" and excluded from the emitted schema.
 *
 * This is deliberately a static-analysis walker, not a runtime probe — we
 * never execute the template here.
 */
class FieldDiscovery
{
    /** @var array<string, FieldNode> */
    private array $roots = [];

    /** @var array<string, true> Set of variable names introduced by {% set %} or {% for x in y %}. */
    private array $locals = [];

    public function discover(string $body): array
    {
        $env = new Environment(new ArrayLoader(['t' => $body]), [
            'cache' => false,
            'strict_variables' => false,
        ]);

        $source = new Source($body, 't');
        $tokens = $env->tokenize($source);
        $ast = $env->parse($tokens);

        $this->roots = [];
        $this->locals = [];

        $this->walk($ast);

        return ['fields' => array_map(fn (FieldNode $n) => $n->toArray(), array_values($this->roots))];
    }

    private function walk(Node $node): void
    {
        // {% for loopVar [,key] in seq %} — `seq` is an array root, `loopVar` becomes a local.
        if ($node instanceof ForNode) {
            $seqNode = $node->getNode('seq');
            $valueTarget = $node->getNode('value_target');
            $keyTarget = $node->hasNode('key_target') ? $node->getNode('key_target') : null;

            $loopVarName = $valueTarget instanceof AssignNameExpression
                ? $valueTarget->getAttribute('name')
                : null;
            $keyVarName = $keyTarget instanceof AssignNameExpression
                ? $keyTarget->getAttribute('name')
                : null;

            $field = null;

            if ($seqNode instanceof NameExpression || $seqNode instanceof GetAttrExpression) {
                $path = $this->pathFor($seqNode);

                if ($path !== null) {
                    [$rootName, $segments] = $path;

                    if (! $this->isPseudoVariable($rootName) && ! isset($this->locals[$rootName])) {
                        $root = $this->ensureRoot($rootName);
                        $field = $this->walkSegments($root, $segments);
                        $field->markArray();
                    }
                }
            }

            if ($loopVarName !== null) {
                $this->locals[$loopVarName] = true;
            }

            // Twig synthesizes `_key` as the key_target when no explicit key alias is given.
            // Stash it under locals so it doesn't leak as a root.
            if ($keyVarName !== null) {
                $this->locals[$keyVarName] = true;
            }

            $loopBody = $node->getNode('body');

            if ($field !== null && $loopVarName !== null) {
                $itemFields = $this->captureLoopItemFields($loopBody, $loopVarName);

                if ($itemFields !== []) {
                    $field->setItemAsObject($itemFields);
                } else {
                    $field->setItemAsScalar();
                }
            }

            // Walk the body so nested loops and other refs get picked up.
            if ($loopVarName !== null) {
                $this->walkSkippingVar($loopBody, $loopVarName);
            } else {
                $this->walk($loopBody);
            }

            if ($loopVarName !== null) {
                unset($this->locals[$loopVarName]);
            }

            if ($keyVarName !== null) {
                unset($this->locals[$keyVarName]);
            }

            return;
        }

        // {% set var = expr %} — `var` becomes local; expr may reference other vars.
        if ($node::class === 'Twig\\Node\\SetNode') {
            $names = $node->getNode('names');

            foreach ($names as $assign) {
                if ($assign instanceof AssignNameExpression) {
                    $this->locals[$assign->getAttribute('name')] = true;
                }
            }
        }

        // Variable read.
        if ($node instanceof NameExpression) {
            $name = $node->getAttribute('name');

            if (! $this->isPseudoVariable($name) && ! isset($this->locals[$name])) {
                $this->ensureRoot($name);
            }
        }

        // Attribute access (e.g. user.name or user['name']).
        if ($node instanceof GetAttrExpression) {
            $path = $this->pathFor($node);

            if ($path !== null) {
                [$rootName, $segments] = $path;

                if (! $this->isPseudoVariable($rootName) && ! isset($this->locals[$rootName])) {
                    $root = $this->ensureRoot($rootName);
                    $this->walkSegments($root, $segments);
                }
            }

            // Don't recurse into children of GetAttr — pathFor already drilled the chain.
            return;
        }

        foreach ($node as $child) {
            if ($child instanceof Node) {
                $this->walk($child);
            }
        }
    }

    /**
     * Walk a node tree but skip any subtree rooted at a NameExpression with the
     * given variable name. Used inside loop bodies to avoid double-counting
     * the loop variable.
     */
    private function walkSkippingVar(Node $node, string $skip): void
    {
        if ($node instanceof GetAttrExpression) {
            // The root might be the skip variable.
            $path = $this->pathFor($node);

            if ($path !== null && $path[0] === $skip) {
                return;
            }
        }

        if ($node instanceof NameExpression && $node->getAttribute('name') === $skip) {
            return;
        }

        $this->walk($node);
    }

    /**
     * For a loop body iterating `loopVar`, extract attribute accesses on
     * `loopVar` and return them as a per-item children array.
     *
     * @return array<string, FieldNode>
     */
    private function captureLoopItemFields(Node $body, string $loopVar): array
    {
        $virtualRoot = new FieldNode($loopVar);
        $this->collectAccessesOn($body, $loopVar, $virtualRoot);

        return $virtualRoot->children();
    }

    private function collectAccessesOn(Node $node, string $loopVar, FieldNode $virtualRoot): void
    {
        if ($node instanceof GetAttrExpression) {
            $path = $this->pathFor($node);

            if ($path !== null && $path[0] === $loopVar) {
                $segments = $path[1];

                $cursor = $virtualRoot;

                foreach ($segments as $segment) {
                    $cursor = $cursor->ensureChild($segment);
                }

                return;
            }
        }

        foreach ($node as $child) {
            if ($child instanceof Node) {
                $this->collectAccessesOn($child, $loopVar, $virtualRoot);
            }
        }
    }

    /**
     * Reduce a NameExpression / chain of GetAttrExpression into
     * [rootName, ['seg1', 'seg2', …]].
     *
     * @return array{0: string, 1: array<int, string>}|null
     */
    private function pathFor(Node $node): ?array
    {
        $segments = [];

        while ($node instanceof GetAttrExpression) {
            $attr = $node->getNode('attribute');

            if (! $attr instanceof Node || ! $attr->hasAttribute('value')) {
                return null;
            }

            array_unshift($segments, (string) $attr->getAttribute('value'));
            $node = $node->getNode('node');
        }

        if ($node instanceof NameExpression) {
            return [$node->getAttribute('name'), $segments];
        }

        return null;
    }

    private function walkSegments(FieldNode $root, array $segments): FieldNode
    {
        $cursor = $root;

        foreach ($segments as $segment) {
            $cursor = $cursor->ensureChild($segment);
        }

        return $cursor;
    }

    private function ensureRoot(string $name): FieldNode
    {
        if (! isset($this->roots[$name])) {
            $this->roots[$name] = new FieldNode($name);
        }

        return $this->roots[$name];
    }

    private function isPseudoVariable(string $name): bool
    {
        // `_self`, `_context`, `loop`, etc. are Twig internals that show up as
        // NameExpression nodes during traversal.
        return in_array($name, ['_self', '_context', '_charset', 'loop'], true);
    }
}
