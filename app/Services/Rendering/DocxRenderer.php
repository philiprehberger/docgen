<?php

namespace App\Services\Rendering;

use App\Models\Render;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Html;

/**
 * DOCX renderer via PhpOffice/PhpWord.
 *
 * `Html::addHtml` accepts a sanitized HTML fragment and converts it into
 * PhpWord's internal element tree, which then writes as Word 2007 .docx.
 *
 * Fidelity is honest: paragraphs, headings, basic tables, lists, inline
 * formatting, links, and a curated set of CSS properties survive the
 * pipeline. Flexbox/grid/absolute positioning/transforms do not — the
 * DOCX format simply doesn't have an equivalent. The docs site's
 * /concepts/docx-fidelity page documents this tradeoff with side-by-side
 * renders so prospects can calibrate before betting on it.
 */
class DocxRenderer implements FormatRenderer
{
    public function render(string $html, Render $render, array $data): string
    {
        Settings::setOutputEscapingEnabled(false);

        $phpWord = new PhpWord;
        $section = $phpWord->addSection([
            'marginTop' => 1000,
            'marginBottom' => 1000,
            'marginLeft' => 1000,
            'marginRight' => 1000,
        ]);

        $fragment = $this->extractBody($html);

        Html::addHtml($section, $fragment, false, true);

        $tmp = tempnam(sys_get_temp_dir(), 'docgen-docx-');

        try {
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($tmp);

            return (string) file_get_contents($tmp);
        } finally {
            @unlink($tmp);
        }
    }

    /**
     * Extract the inner HTML of <body> if it exists; otherwise return the
     * input unchanged. PhpWord's HTML reader doesn't like full HTML5
     * documents — it wants a fragment.
     */
    private function extractBody(string $html): string
    {
        if (preg_match('/<body[^>]*>(.*)<\/body>/is', $html, $m)) {
            return $m[1];
        }

        return $html;
    }
}
