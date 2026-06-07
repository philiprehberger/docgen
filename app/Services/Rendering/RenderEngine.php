<?php

namespace App\Services\Rendering;

use App\Models\Render;
use App\Services\Twig\TwigEnvironmentFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Runs a Render record from `queued` → terminal state. Delegates per-format
 * production to the registered renderers (HtmlRenderer is Phase 3;
 * PdfRenderer is Phase 4; DocxRenderer is Phase 5).
 *
 * Outputs are written under the configured filesystem disk at
 * `<rendered_path>/<render_id>/<format>` and recorded on the Render record
 * with their byte count + sha256. The controller mints signed URLs at
 * read time, not here.
 */
class RenderEngine
{
    /** @var array<string, FormatRenderer> */
    private array $renderers = [];

    public function __construct(private readonly TwigEnvironmentFactory $twigFactory)
    {
        $this->register('html', new HtmlRenderer($twigFactory));
        $this->register('pdf', new PdfRenderer);
        $this->register('docx', new DocxRenderer);
    }

    public function register(string $format, FormatRenderer $renderer): void
    {
        $this->renderers[$format] = $renderer;
    }

    public function supports(string $format): bool
    {
        return isset($this->renderers[$format]);
    }

    /**
     * Run all formats requested by the render record and persist outputs.
     * Idempotent at the file-level — re-running overwrites the same paths.
     */
    public function run(Render $render, array $data): void
    {
        $started = microtime(true);

        $render->forceFill([
            'status' => Render::STATUS_RENDERING,
            'started_at' => now(),
        ])->save();

        $twig = $this->twigFactory->make($render->version->body_snapshot, "render-{$render->id}");
        $html = $twig->render("render-{$render->id}", $data);

        // SSRF guard on Chromium asset fetches. Only matters when the
        // rendered HTML carries absolute URLs that the headless browser
        // will try to load.
        $forbidden = (new AssetUrlGuard)->findForbiddenUrls($html);

        if ($forbidden !== []) {
            throw new \RuntimeException(
                'Template references one or more forbidden URLs (private/loopback/link-local hosts): '
                . implode(', ', array_slice($forbidden, 0, 3))
                . (count($forbidden) > 3 ? ' …' : '')
            );
        }

        $disk = Storage::disk(config('filesystems.default'));
        $renderedRoot = config('docgen.rendered_path', 'rendered');
        $baseDir = "{$renderedRoot}/{$render->id}";

        $outputs = [];

        foreach ($render->formats_requested as $format) {
            if (! $this->supports($format)) {
                throw new \RuntimeException("Unsupported format: {$format}");
            }

            $renderer = $this->renderers[$format];
            $rendered = $renderer->render($html, $render, $data);

            $relativePath = "{$baseDir}/output.{$format}";
            $disk->put($relativePath, $rendered);

            $outputs[] = [
                'format' => $format,
                'path' => $relativePath,
                'bytes' => strlen($rendered),
                'sha256' => hash('sha256', $rendered),
            ];
        }

        $render->forceFill([
            'status' => Render::STATUS_SUCCEEDED,
            'outputs' => $outputs,
            'duration_ms' => (int) ((microtime(true) - $started) * 1000),
            'completed_at' => now(),
        ])->save();
    }

    public function fail(Render $render, \Throwable $e): void
    {
        Log::warning("Render {$render->id} failed: {$e->getMessage()}");

        $render->forceFill([
            'status' => Render::STATUS_FAILED,
            'error_code' => Str::snake(class_basename($e)),
            'error_message' => $e->getMessage(),
            'completed_at' => now(),
        ])->save();
    }
}
