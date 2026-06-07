<?php

namespace App\Services\Rendering;

use App\Models\Render;
use Spatie\Browsershot\Browsershot;

/**
 * PDF format renderer backed by Chromium via Spatie/Browsershot + Puppeteer.
 *
 * Production rendering happens out-of-process: Browsershot spawns a
 * short-lived Chromium for each render. The cold-start cost is ~500ms but
 * we avoid Chromium memory leaks (the most common failure mode for
 * long-running persistent instances).
 *
 * Chromium path resolution: when DOCGEN_CHROME_PATH is set, use it;
 * otherwise we let Browsershot use puppeteer's bundled binary. The deploy
 * pipeline ships puppeteer + the Chromium download under node_modules/.
 */
class PdfRenderer implements FormatRenderer
{
    public function render(string $html, Render $render, array $data): string
    {
        $shot = Browsershot::html($html)
            ->showBackground()
            ->emulateMedia('print')
            ->format('A4')
            ->margins(20, 20, 20, 20)        // mm
            ->noSandbox()                    // EC2 host runs as `ubuntu`; no sandbox.
            ->setOption('args', [
                '--disable-dev-shm-usage',
                '--disable-gpu',
            ]);

        $chrome = env('DOCGEN_CHROME_PATH') ?: $this->detectChromePath();

        if ($chrome !== null) {
            $shot = $shot->setChromePath($chrome);
        }

        if ($nodeBin = env('DOCGEN_NODE_BIN')) {
            $shot = $shot->setNodeBinary($nodeBin);
        }

        return $shot->pdf();
    }

    /**
     * Locate puppeteer's downloaded Chromium binary. Browsershot's bundled
     * helper uses puppeteer-core which sometimes drifts from the matching
     * chrome version on disk; pinning the path resolves the mismatch
     * without forcing every deploy to set DOCGEN_CHROME_PATH explicitly.
     */
    private function detectChromePath(): ?string
    {
        $candidates = glob(($_SERVER['HOME'] ?? '/home/ubuntu') . '/.cache/puppeteer/chrome/*/chrome-linux64/chrome');

        if (! $candidates) {
            return null;
        }

        // Pick the newest version directory (lexicographic sort works for
        // `linux-149.0.7827.22`-style names since they share a prefix).
        rsort($candidates);

        return is_executable($candidates[0]) ? $candidates[0] : null;
    }
}
