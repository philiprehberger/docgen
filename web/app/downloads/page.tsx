import Link from "next/link";

export const metadata = { title: "Downloads" };

const VERSION = "0.5.0";

const SPEC = [
  {
    title: "OpenAPI 3.1 (YAML)",
    href: "/openapi.yaml",
    blurb:
      "Source of truth. Feed it into your own generator, lint with Spectral, import into Postman or Insomnia.",
    badge: ".yaml",
  },
  {
    title: "OpenAPI 3.1 (JSON)",
    href: "/openapi.json",
    blurb: "Same spec, JSON-encoded. Better for build tools that don't read YAML.",
    badge: ".json",
  },
  {
    title: "Postman collection",
    href: "/postman-collection.json",
    blurb:
      "Drop into Postman / Insomnia / Bruno. Includes every endpoint with example bodies. Set Authorization: Bearer docgen_… as a collection-level header.",
    badge: ".json",
  },
  {
    title: "API reference (PDF)",
    href: `/openapi-reference-${VERSION}.pdf`,
    blurb:
      "Print-ready PDF of the full reference, rendered from the Scalar view. Pre-rendered so you don't have to print the docs site yourself.",
    badge: ".pdf",
  },
];

const SDKS = [
  {
    title: "TypeScript SDK",
    blurb: "Dual ESM + CJS. Node 18+. Published to npm.",
    href: `/downloads/sdks/docgen-typescript-sdk.zip`,
    install: "npm i @philiprehberger/docgen",
    pageHref: "/sdks/typescript",
  },
  {
    title: "PHP SDK",
    blurb: "PSR-4 namespaced. PHP 8.2+. Published to Packagist.",
    href: `/downloads/sdks/docgen-php-sdk.zip`,
    install: "composer require philiprehberger/docgen",
    pageHref: "/sdks/php",
  },
  {
    title: "Python SDK",
    blurb: "urllib3-based, pydantic v2. Python 3.9+. Published to PyPI.",
    href: `/downloads/sdks/docgen-python-sdk.zip`,
    install: "pip install philiprehberger-docgen",
    pageHref: "/sdks/python",
  },
];

const TEMPLATES = [
  {
    title: "Invoice template",
    href: "/downloads/templates/invoice.twig",
    blurb: "Northcliffe Legal → Ridgeline Supply. HTML+Twig source, renders to PDF + DOCX with matched fidelity.",
  },
  {
    title: "Offer-letter template",
    href: "/downloads/templates/offer-letter.twig",
    blurb: "Beacon Academy hire. Single-column layout, DOCX-friendly.",
  },
  {
    title: "Certificate template",
    href: "/downloads/templates/certificate.twig",
    blurb: "Beacon Academy graduate. PDF-first; landscape, ornamental borders, flex layout.",
  },
];

export default function Downloads() {
  return (
    <div className="mx-auto max-w-5xl px-6 py-16">
      <p className="text-xs uppercase tracking-widest text-amber-300/80 mb-4">Downloads</p>
      <h1 className="text-3xl sm:text-4xl font-semibold tracking-tight mb-4">
        Spec, SDKs, templates — every deliverable, downloadable
      </h1>
      <p className="text-zinc-400 max-w-2xl mb-12">
        Everything on this page regenerates from the same OpenAPI spec. If
        you only grab one file, take the YAML — it&apos;s the source every
        other artifact was built from.
      </p>

      <section className="mb-12">
        <h2 className="text-xl font-semibold mb-4">API specification</h2>
        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
          {SPEC.map((s) => (
            <a
              key={s.href}
              href={s.href}
              download
              className="flex flex-col rounded-lg border border-zinc-800 bg-zinc-900/40 p-5 hover:border-zinc-700 hover:bg-zinc-900/70 transition-colors"
            >
              <div className="flex items-baseline justify-between mb-2">
                <p className="text-sm font-medium text-zinc-100">{s.title}</p>
                <span className="text-[10px] uppercase tracking-widest text-zinc-500 font-mono">
                  {s.badge}
                </span>
              </div>
              <p className="text-sm text-zinc-400 leading-relaxed flex-1">{s.blurb}</p>
              <p className="mt-4 text-xs text-amber-300/80">Download →</p>
            </a>
          ))}
        </div>
      </section>

      <section className="mb-12">
        <h2 className="text-xl font-semibold mb-4">
          SDK source archives{" "}
          <span className="text-sm font-normal text-zinc-500">v{VERSION}</span>
        </h2>
        <div className="grid sm:grid-cols-3 gap-3">
          {SDKS.map((s) => (
            <div
              key={s.href}
              className="rounded-lg border border-zinc-800 bg-zinc-900/40 p-5 hover:border-zinc-700 transition-colors"
            >
              <p className="text-sm font-medium text-zinc-100 mb-1">{s.title}</p>
              <p className="text-sm text-zinc-400 leading-relaxed mb-3">{s.blurb}</p>
              <p className="text-xs font-mono text-amber-300/80 mb-4 break-all">{s.install}</p>
              <div className="flex gap-2 text-xs">
                <a
                  href={s.href}
                  download
                  className="rounded-md bg-amber-400/15 text-amber-200 hover:bg-amber-400/25 px-3 py-1.5 transition-colors"
                >
                  Download zip
                </a>
                <Link
                  href={s.pageHref}
                  className="rounded-md border border-zinc-800 hover:border-zinc-600 px-3 py-1.5 text-zinc-400 hover:text-zinc-200 transition-colors"
                >
                  Quickstart
                </Link>
              </div>
            </div>
          ))}
        </div>
      </section>

      <section className="mb-12">
        <h2 className="text-xl font-semibold mb-4">Reference templates</h2>
        <div className="grid sm:grid-cols-3 gap-3">
          {TEMPLATES.map((t) => (
            <a
              key={t.href}
              href={t.href}
              download
              className="rounded-lg border border-zinc-800 bg-zinc-900/40 p-5 hover:border-zinc-700 transition-colors block"
            >
              <p className="text-sm font-medium text-zinc-100 mb-2">{t.title}</p>
              <p className="text-sm text-zinc-400 leading-relaxed mb-4">{t.blurb}</p>
              <p className="text-xs text-amber-300/80">Download .twig →</p>
            </a>
          ))}
        </div>
      </section>

      <section className="rounded-md border border-zinc-800 bg-zinc-900/30 p-5">
        <p className="text-sm text-zinc-300">
          <strong className="text-zinc-100">Regenerate locally?</strong>{" "}
          Clone the repo and run{" "}
          <code className="text-amber-200 bg-amber-300/10 px-1.5 py-0.5 rounded text-xs font-mono">
            npm run sdk:generate
          </code>{" "}
          to rebuild SDK source from the spec, then{" "}
          <code className="text-amber-200 bg-amber-300/10 px-1.5 py-0.5 rounded text-xs font-mono">
            npm run sdk:zip
          </code>{" "}
          to produce the archives. Every artifact on this page is reproducible
          from <code className="text-amber-200 bg-amber-300/10 px-1.5 py-0.5 rounded text-xs font-mono">openapi/spec.yaml</code>.
        </p>
      </section>
    </div>
  );
}
