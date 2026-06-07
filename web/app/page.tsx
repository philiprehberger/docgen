import Link from "next/link";
import { CodeBlock } from "../components/CodeBlock";

export default function Home() {
  return (
    <div>
      <section className="mx-auto max-w-5xl px-6 pt-20 pb-24">
        <p className="text-xs uppercase tracking-widest text-amber-300/80 mb-4">
          Document generation as an API
        </p>
        <h1 className="text-4xl sm:text-5xl md:text-6xl font-semibold tracking-tight leading-[1.05] mb-6 max-w-3xl">
          One template. Three formats. A signed download URL by the time you finish the curl.
        </h1>
        <p className="text-lg text-zinc-400 leading-relaxed max-w-2xl mb-10">
          POST an HTML+Twig template and the data to merge into it. Get back HTML, a Chromium-rendered PDF, and a Word DOCX — all from the same source, all behind signed URLs that expire on the schedule you set.
        </p>
        <div className="flex flex-wrap gap-3">
          <Link
            href="/reference"
            className="rounded-md bg-amber-400 text-amber-950 hover:bg-amber-300 transition-colors px-4 py-2 text-sm font-medium"
          >
            Try it →
          </Link>
          <Link
            href="/concepts/templating"
            className="rounded-md border border-zinc-700 hover:border-zinc-500 transition-colors px-4 py-2 text-sm"
          >
            Read the concepts
          </Link>
          <Link
            href="https://github.com/philiprehberger/docgen"
            className="rounded-md border border-zinc-800 hover:border-zinc-600 hover:text-zinc-200 transition-colors px-4 py-2 text-sm text-zinc-400"
          >
            View source
          </Link>
        </div>
      </section>

      <section className="mx-auto max-w-5xl px-6 pb-24">
        <div className="grid lg:grid-cols-2 gap-6">
          <div>
            <p className="text-xs uppercase tracking-widest text-zinc-500 mb-3">
              1. Author a template
            </p>
            <CodeBlock language="curl">
{`curl -X POST https://api.docgen.philiprehberger.com/v1/templates \\
  -H 'Authorization: Bearer docgen_live_…' \\
  -H 'Content-Type: application/json' \\
  -d '{
    "name": "Invoice",
    "body": "<h1>Invoice {{ number }}</h1><p>{{ total }}</p>"
  }'`}
            </CodeBlock>
            <p className="text-sm text-zinc-500 leading-relaxed">
              HTML + Twig source. The API parses the body, validates the syntax, and tells you which merge fields it found.
            </p>
          </div>

          <div>
            <p className="text-xs uppercase tracking-widest text-zinc-500 mb-3">
              2. Freeze a version
            </p>
            <CodeBlock language="curl">
{`curl -X POST https://api.docgen.philiprehberger.com/v1/templates/<id>/versions \\
  -H 'Authorization: Bearer docgen_live_…'`}
            </CodeBlock>
            <p className="text-sm text-zinc-500 leading-relaxed">
              Snapshots the current draft as <code className="text-amber-300/90 text-xs">v1</code>. Versions are immutable — even if the draft changes, the version body never does.
            </p>
          </div>

          <div>
            <p className="text-xs uppercase tracking-widest text-zinc-500 mb-3">
              3. Submit a render
            </p>
            <CodeBlock language="curl">
{`curl -X POST https://api.docgen.philiprehberger.com/v1/renders \\
  -H 'Authorization: Bearer docgen_live_…' \\
  -H 'Content-Type: application/json' \\
  -d '{
    "template_id": "<id>",
    "formats": ["pdf", "docx"],
    "data": { "number": "INV-001", "total": "$2,625.00" }
  }'`}
            </CodeBlock>
            <p className="text-sm text-zinc-500 leading-relaxed">
              Returns 202 + a poll URL. Add <code className="text-amber-300/90 text-xs">?sync=true</code> to block up to 15 seconds for small renders.
            </p>
          </div>

          <div>
            <p className="text-xs uppercase tracking-widest text-zinc-500 mb-3">
              4. Download the output
            </p>
            <CodeBlock language="json">
{`{
  "status": "succeeded",
  "outputs": [
    {
      "format": "pdf",
      "url": "https://api.docgen.philiprehberger.com/v1/renders/01.../outputs/pdf?signature=…",
      "expires_at": "2026-06-07T22:30:00Z",
      "bytes": 12480
    }
  ]
}`}
            </CodeBlock>
            <p className="text-sm text-zinc-500 leading-relaxed">
              Signed URL, TTL you set, no auth header needed on the download. Pass it to your client, your CDN, your email.
            </p>
          </div>
        </div>
      </section>

      <section className="mx-auto max-w-5xl px-6 pb-24">
        <h2 className="text-2xl font-semibold mb-8">What it actually ships</h2>
        <div className="grid md:grid-cols-3 gap-4 text-sm">
          <Feature title="Twig templates">
            Sandboxed evaluation. The API walks the AST and tells you the
            merge-field schema before you submit data.
          </Feature>
          <Feature title="Frozen versions">
            Once a template version has rendered anything, the body is
            immutable. Last month&apos;s invoices don&apos;t change when this
            month&apos;s template ships.
          </Feature>
          <Feature title="Async + sync">
            <code className="text-amber-300/90 text-xs">POST /v1/renders</code>{" "}
            returns 202 by default; <code className="text-amber-300/90 text-xs">?sync=true</code>{" "}
            blocks up to 15s. Same endpoint, two shapes.
          </Feature>
          <Feature title="Signed downloads">
            Output URLs are <code className="text-amber-300/90 text-xs">temporarySignedRoute</code>{" "}
            with a configurable TTL. No public buckets, no auth headers on the link.
          </Feature>
          <Feature title="PDF via Chromium">
            Spatie/Browsershot + puppeteer. Best CSS fidelity available
            without firing up a print server.
          </Feature>
          <Feature title="DOCX via PhpWord">
            Native Word 2007 output. <Link href="/concepts/docx-fidelity"
            className="text-amber-300 underline-offset-4 hover:underline">Honest tradeoffs documented.</Link>
          </Feature>
        </div>
      </section>

      <section className="mx-auto max-w-5xl px-6 pb-24">
        <h2 className="text-2xl font-semibold mb-4">Try it from the docs</h2>
        <p className="text-zinc-400 max-w-2xl mb-6">
          The <Link href="/reference" className="text-amber-300 underline-offset-4 hover:underline">interactive reference</Link>{" "}
          mints a 30-minute sandbox key for your IP, pre-loads three
          reference templates, and lets you fire real renders against the
          live API. Paste your own JSON; watch the PDF appear in the next pane.
        </p>
        <Link
          href="/reference"
          className="inline-block rounded-md bg-amber-400 text-amber-950 hover:bg-amber-300 transition-colors px-4 py-2 text-sm font-medium"
        >
          Open the try-it console →
        </Link>
      </section>
    </div>
  );
}

function Feature({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div className="rounded-lg border border-zinc-800/80 bg-zinc-900/40 p-5">
      <p className="font-medium text-zinc-100 mb-2">{title}</p>
      <p className="text-zinc-400 leading-relaxed">{children}</p>
    </div>
  );
}
