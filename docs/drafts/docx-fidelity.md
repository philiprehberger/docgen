# DOCX fidelity — what survives, what doesn't

*Draft for `docgen.philiprehberger.com/concepts/docx-fidelity` — Phase 8 docs site.*

Docgen renders three formats from one source: HTML, PDF (via Chromium),
and DOCX (via PhpOffice/PhpWord). HTML and PDF look identical. DOCX
doesn't, and that's a deliberate tradeoff worth understanding before you
build a workflow that relies on it.

This page tells you which parts of your template survive the HTML → DOCX
conversion, which parts don't, and how to decide whether docgen's DOCX
output is the right shape for your use case.

## The short version

Use DOCX when:

- The recipient needs to edit the document in Word or Google Docs.
- The layout is predominantly paragraphs, headings, tables, and lists.
- Visual fidelity is a "nice to have," not a contract requirement.

Don't use DOCX when:

- The layout depends on flexbox, grid, absolute positioning, or transforms.
- The document is a brand artifact where typography and spacing must be
  pixel-precise (offer letters with sealed corporate identity, certificates
  with award-grade typography, etc.). Render those as PDF.
- Your downstream tooling expects PDF/A or any other archival format.

In practice: invoices, statements, contracts, simple correspondence — DOCX
holds up well. Hero-marketing-style layouts, certificates, anything with
a complex grid — render as PDF and stop there.

## What survives the conversion

- **Block-level structure** — paragraphs, headings (`h1`–`h6`), block
  quotes, horizontal rules.
- **Inline formatting** — bold, italic, underline, strike-through, links.
- **Lists** — ordered (`ol`) and unordered (`ul`), nested up to ~3 levels.
- **Tables** — `table` / `thead` / `tbody` / `tr` / `td` / `th` with
  column widths in absolute units (px, pt).
- **Basic CSS** — `color`, `background-color`, `font-family` (when the
  font is also installed on the recipient's system), `font-size` (in pt
  or px), `font-weight`, `text-align`, `margin`, `padding`, `border`
  (single-color, single-style only).
- **Images** — `img` tags referencing reachable URLs are embedded.

## What doesn't survive

- **Flexbox and Grid** — DOCX has no equivalent. Flex containers collapse
  to inline-block placement; multi-column grids render as a single column.
- **Absolute or fixed positioning** — anything with `position: absolute`
  or `position: fixed` becomes inline content at the position of the source
  element in the document order. Ornamental borders, watermarks, and
  decorative overlays usually break.
- **`@page` directives** — Page sizes, margins, headers, and footers
  defined in CSS don't transfer. Configure those in the DOCX output
  metadata instead (workspace-level defaults are coming in v2).
- **CSS gradients, transforms, filters** — silently dropped.
- **Web fonts loaded via `@import` or `<link>`** — the renderer can't
  embed font files into the DOCX archive; fonts have to exist on the
  recipient's system to render.
- **Custom-element-driven layouts** — anything assembled via
  client-side JavaScript or web components doesn't exist by the time
  PhpWord sees the markup; only the pre-rendered HTML reaches the
  conversion.

## How to author for both PDF and DOCX

The reference templates that ship with docgen
(`sample-templates/invoice.twig`, `offer-letter.twig`, `certificate.twig`)
follow this pattern:

1. **Layout uses flow positioning, not flexbox/grid.** Headers stack
   above content; columns are real `<table>` columns, not flex children.
2. **Spacing is in `pt` or `px`,** not `rem` or `em` (PhpWord's CSS parser
   is happiest with absolute units).
3. **Borders are single-color, single-style.** No multi-color or
   double borders — PhpWord falls back to the first color it sees.
4. **Print-only styling lives in `@media print`.** PDF picks it up;
   DOCX ignores it cleanly.
5. **Templates that only render well as PDF say so in the description.**
   The certificate template, for example, is a "PDF-first" template;
   submitting `formats: ['docx']` against it produces a usable file but
   one that won't match the design.

## Worked example — invoice (renders identically)

The invoice template renders to PDF and DOCX with substantially the same
layout. The header table is a real `<table>`, the line items are a real
`<table>`, totals are right-aligned via `text-align: right` (which works
in both formats), and the page padding is in `px`.

*(Side-by-side screenshots ship with the docs site in Phase 8.)*

## Worked example — offer letter (renders well)

Letter format is the friendliest case for DOCX: serif body, a single
column, embedded `<dl>` for terms-of-employment, signature lines at the
bottom. DOCX output is editable in Word with paragraphs spaced correctly,
headings preserved, and the signature lines still rendering as visible
underscores.

## Worked example — certificate (renders, but as a "different" document)

The certificate template uses CSS variables, a landscape `@page` directive,
absolute-positioned borders, and a flex-layout signature area. None of
that has a DOCX equivalent. The PDF output is a polished award certificate;
the DOCX output is a single-column letter-format document with the same
text content and no decorative framing. The text is preserved and
editable, but it's not the same document.

This is *fine* for some workflows — an admin who wants to update the
certificate program description before printing-and-framing might want
the DOCX. For the customer-facing "here's your certificate" delivery,
PDF is the right format.

## Roadmap

DOCX fidelity is the most-asked-about gap in the demo. The two features
that would close most of the gap, and where they sit:

- **Native DOCX templates** — uploading a `.docx` file with
  merge-field syntax in it, instead of HTML+Twig. This trades the
  multi-format-from-one-source story for highest-fidelity DOCX output.
  Tracked as a v2 feature (see the plan's "Open decisions" section
  flagging DOCX-input templates as a deferred capability).
- **LibreOffice headless for DOCX → PDF** — replacing PhpWord's
  in-process converter with a LibreOffice subprocess. Higher fidelity at
  the cost of installing LibreOffice on the EC2 host. Worth it for a real
  customer; out of scope for the portfolio demo.

If your use case is "the output needs to look exactly like the design,"
the answer is: render as PDF, and hand DOCX customers a PDF too. That's
how Stripe, Figma, Notion, and every other modern SaaS handle it.
