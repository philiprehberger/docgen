<?php

namespace App\Jobs;

use App\Models\Render;
use App\Services\Rendering\RenderEngine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RunRender implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public function __construct(
        public readonly string $renderId,
        public readonly array $data,
    ) {}

    public function handle(RenderEngine $engine): void
    {
        $render = Render::query()->with('version')->find($this->renderId);

        if ($render === null || $render->isTerminal()) {
            return;
        }

        try {
            $engine->run($render, $this->data);
        } catch (\Throwable $e) {
            $engine->fail($render, $e);
        }
    }
}
