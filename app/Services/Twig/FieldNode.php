<?php

namespace App\Services\Twig;

/**
 * One node in the inferred merge-field tree.
 *
 * Type is upgraded as evidence accumulates:
 *   - default: scalar
 *   - any child set:  object (overrides scalar)
 *   - markArray():    array (overrides scalar; array-of-X retained via item)
 */
class FieldNode
{
    private string $type = 'scalar';

    /** @var array<string, self> */
    private array $children = [];

    /** Item type for arrays. Either 'scalar' or 'object'. */
    private string $itemType = 'scalar';

    /** @var array<string, self> Children of each array item when itemType=object. */
    private array $itemChildren = [];

    public function __construct(private readonly string $name) {}

    public function name(): string
    {
        return $this->name;
    }

    /** @return array<string, self> */
    public function children(): array
    {
        return $this->children;
    }

    public function ensureChild(string $name): self
    {
        if (! isset($this->children[$name])) {
            $this->children[$name] = new self($name);

            if ($this->type === 'scalar') {
                $this->type = 'object';
            }
        }

        return $this->children[$name];
    }

    public function markArray(): void
    {
        $this->type = 'array';
    }

    public function setItemAsScalar(): void
    {
        if ($this->itemType === 'object') {
            // Object beats scalar (we've seen attribute access on items elsewhere).
            return;
        }

        $this->itemType = 'scalar';
    }

    /** @param array<string, self> $itemChildren */
    public function setItemAsObject(array $itemChildren): void
    {
        $this->itemType = 'object';
        $this->itemChildren = $itemChildren;
    }

    public function toArray(): array
    {
        $out = [
            'name' => $this->name,
            'type' => $this->type,
            'required' => true,
        ];

        if ($this->type === 'object' && $this->children !== []) {
            $out['children'] = array_map(fn (self $c) => $c->toArray(), array_values($this->children));
        }

        if ($this->type === 'array') {
            $out['item_type'] = $this->itemType;

            if ($this->itemType === 'object' && $this->itemChildren !== []) {
                $out['children'] = array_map(fn (self $c) => $c->toArray(), array_values($this->itemChildren));
            }
        }

        return $out;
    }
}
