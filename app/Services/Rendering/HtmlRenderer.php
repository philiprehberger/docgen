<?php

namespace App\Services\Rendering;

use App\Models\Render;
use App\Services\Twig\TwigEnvironmentFactory;

class HtmlRenderer implements FormatRenderer
{
    public function __construct(private readonly TwigEnvironmentFactory $twigFactory) {}

    public function render(string $html, Render $render, array $data): string
    {
        // HTML output is just the Twig-rendered string. RenderEngine already
        // ran Twig before calling us, so we pass it through verbatim.
        return $html;
    }
}
