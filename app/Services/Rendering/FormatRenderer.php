<?php

namespace App\Services\Rendering;

use App\Models\Render;

interface FormatRenderer
{
    /**
     * Produce the binary or text body for one output format.
     *
     * @param  string  $html  Pre-rendered HTML (Twig already evaluated).
     * @param  array<string, mixed>  $data  Input data (in case the renderer needs raw values).
     */
    public function render(string $html, Render $render, array $data): string;
}
