import { DocsLayout } from "../../../components/DocsLayout";

export const metadata = { title: "DOCX fidelity" };

export default function Page() {
  return (
    <DocsLayout>
      <h1>DOCX fidelity — what survives, what doesn&apos;t</h1>
      <p>
        Docgen renders three formats from one source: HTML, PDF (via Chromium),
        and DOCX (via <a href="https://phpoffice.github.io/PhpWord/">PhpOffice/PhpWord</a>).
        HTML and PDF look identical. DOCX doesn&apos;t, and that&apos;s a
        deliberate tradeoff worth understanding before you build a workflow
        that relies on it.
      </p>

      <h2>The short version</h2>
      <p><strong>Use DOCX when:</strong></p>
      <ul>
        <li>The recipient needs to edit the document in Word or Google Docs.</li>
        <li>The layout is predominantly paragraphs, headings, tables, and lists.</li>
        <li>Visual fidelity is a &quot;nice to have,&quot; not a contract requirement.</li>
      </ul>
      <p><strong>Don&apos;t use DOCX when:</strong></p>
      <ul>
        <li>The layout depends on flexbox, grid, absolute positioning, or transforms.</li>
        <li>
          The document is a brand artifact where typography and spacing must be
          pixel-precise (offer letters with sealed corporate identity, certificates
          with award-grade typography, etc.). Render those as PDF.
        </li>
        <li>Your downstream tooling expects PDF/A or any other archival format.</li>
      </ul>

      <h2>What survives the conversion</h2>
      <ul>
        <li>
          <strong>Block-level structure</strong> — paragraphs, headings
          (<code>h1</code>–<code>h6</code>), block quotes, horizontal rules.
        </li>
        <li>
          <strong>Inline formatting</strong> — bold, italic, underline,
          strike-through, links.
        </li>
        <li>
          <strong>Lists</strong> — ordered (<code>ol</code>) and unordered
          (<code>ul</code>), nested up to ~3 levels.
        </li>
        <li>
          <strong>Tables</strong> — <code>table</code> / <code>thead</code> /{" "}
          <code>tbody</code> / <code>tr</code> / <code>td</code> / <code>th</code>{" "}
          with column widths in absolute units (px, pt).
        </li>
        <li>
          <strong>Basic CSS</strong> — <code>color</code>, <code>background-color</code>,
          <code>font-family</code> (when the font is also installed on the
          recipient&apos;s system), <code>font-size</code> (in pt or px),
          <code>font-weight</code>, <code>text-align</code>, <code>margin</code>,
          <code>padding</code>, <code>border</code> (single-color, single-style only).
        </li>
        <li>
          <strong>Images</strong> — <code>img</code> tags referencing reachable URLs
          are embedded.
        </li>
      </ul>

      <h2>What doesn&apos;t survive</h2>
      <ul>
        <li>
          <strong>Flexbox and Grid</strong> — DOCX has no equivalent. Flex
          containers collapse to inline-block placement; multi-column grids
          render as a single column.
        </li>
        <li>
          <strong>Absolute or fixed positioning</strong> — anything with{" "}
          <code>position: absolute</code> or <code>position: fixed</code>{" "}
          becomes inline content. Ornamental borders, watermarks, and
          decorative overlays usually break.
        </li>
        <li>
          <strong><code>@page</code> directives</strong> — page sizes,
          margins, headers, and footers defined in CSS don&apos;t transfer.
        </li>
        <li>
          <strong>CSS gradients, transforms, filters</strong> — silently dropped.
        </li>
        <li>
          <strong>Web fonts</strong> loaded via <code>@import</code> or{" "}
          <code>&lt;link&gt;</code> — the renderer can&apos;t embed font files
          into the DOCX archive; fonts have to exist on the recipient&apos;s
          system.
        </li>
        <li>
          <strong>Custom-element-driven layouts</strong> — anything assembled
          via client-side JavaScript or web components doesn&apos;t exist by
          the time PhpWord sees the markup.
        </li>
      </ul>

      <h2>How to author for both PDF and DOCX</h2>
      <p>
        The three <a href="/templates">reference templates</a> that ship with docgen
        follow this pattern:
      </p>
      <ol>
        <li>
          <strong>Layout uses flow positioning, not flexbox/grid.</strong>{" "}
          Headers stack above content; columns are real <code>&lt;table&gt;</code>{" "}
          columns, not flex children.
        </li>
        <li>
          <strong>Spacing is in <code>pt</code> or <code>px</code>,</strong> not{" "}
          <code>rem</code> or <code>em</code>.
        </li>
        <li>
          <strong>Borders are single-color, single-style.</strong> No
          multi-color or double borders.
        </li>
        <li>
          <strong>Print-only styling lives in <code>@media print</code>.</strong>{" "}
          PDF picks it up; DOCX ignores it cleanly.
        </li>
      </ol>

      <h2>Worked examples</h2>
      <p>
        The reference templates demonstrate three different fidelity levels:
      </p>
      <ul>
        <li>
          <strong><a href="/templates">Invoice</a></strong> — renders to PDF and DOCX
          with substantially the same layout. The header is a <code>&lt;table&gt;</code>,
          the line items are a <code>&lt;table&gt;</code>, totals are right-aligned
          via <code>text-align: right</code>. Both formats are usable.
        </li>
        <li>
          <strong><a href="/templates">Offer letter</a></strong> — friendliest case for
          DOCX: serif body, single column, embedded <code>&lt;dl&gt;</code> for
          terms, signature lines at the bottom. Editable in Word with
          paragraphs spaced correctly.
        </li>
        <li>
          <strong><a href="/templates">Certificate</a></strong> — landscape{" "}
          <code>@page</code> directive, absolute-positioned ornamental
          borders, flex layout. <em>None of that has a DOCX equivalent.</em>{" "}
          PDF is a polished award certificate; DOCX is a single-column
          letter-format document with the same text. <strong>This is a
          PDF-only template.</strong>
        </li>
      </ul>

      <h2>Roadmap</h2>
      <p>
        DOCX fidelity is the most-asked-about gap. Two features that would
        close most of it:
      </p>
      <ul>
        <li>
          <strong>Native DOCX templates</strong> — upload a <code>.docx</code>{" "}
          file with merge-field syntax, instead of HTML+Twig. Trades
          multi-format-from-one-source for highest-fidelity DOCX. v2 feature.
        </li>
        <li>
          <strong>LibreOffice headless for DOCX → PDF</strong> — replace
          PhpWord&apos;s in-process converter with a LibreOffice subprocess.
          Higher fidelity at the cost of installing LibreOffice. Worth it
          for a real customer; out of scope for the demo.
        </li>
      </ul>

      <p>
        If your use case is &quot;the output needs to look exactly like the
        design,&quot; render as PDF and hand DOCX customers a PDF too.
        That&apos;s how Stripe, Figma, Notion, and every other modern SaaS
        handle it.
      </p>
    </DocsLayout>
  );
}
