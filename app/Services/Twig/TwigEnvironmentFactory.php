<?php

namespace App\Services\Twig;

use Twig\Environment;
use Twig\Extension\SandboxExtension;
use Twig\Loader\ArrayLoader;
use Twig\Sandbox\SecurityPolicy;

/**
 * Builds Twig environments configured for sandboxed template evaluation.
 *
 * Templates run as data. The sandbox allows a curated set of tags / filters
 * / functions and refuses everything else. No `attribute()`, no PHP function
 * passthrough, no `include` from arbitrary paths.
 */
class TwigEnvironmentFactory
{
    public function make(string $body, string $cacheTag = 'inline'): Environment
    {
        $loader = new ArrayLoader([$cacheTag => $body]);

        $env = new Environment($loader, [
            'autoescape' => 'html',
            'strict_variables' => false,
            'cache' => false,
        ]);

        $env->addExtension(new SandboxExtension($this->policy(), true));

        return $env;
    }

    public function policy(): SecurityPolicy
    {
        return new SecurityPolicy(
            allowedTags: ['if', 'for', 'else', 'elseif', 'set', 'spaceless', 'apply'],
            allowedFilters: [
                'escape', 'e', 'raw', 'length', 'lower', 'upper', 'title', 'capitalize',
                'trim', 'join', 'split', 'default', 'number_format', 'date', 'replace',
                'striptags', 'nl2br', 'first', 'last', 'reverse', 'sort', 'keys',
                'merge', 'slice', 'abs', 'round', 'format', 'url_encode',
            ],
            allowedMethods: [],
            allowedProperties: [],
            allowedFunctions: ['range', 'cycle', 'max', 'min', 'date'],
        );
    }
}
