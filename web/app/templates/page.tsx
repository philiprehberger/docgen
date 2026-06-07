import Link from "next/link";

export const metadata = { title: "Reference templates" };

const TEMPLATES = [
  {
    slug: "invoice",
    title: "Invoice",
    fictional: "Northcliffe Legal LLP → Ridgeline Supply Co.",
    fidelity: "Renders identically as PDF + DOCX",
    shape:
      "Object roots (sender, client, invoice, totals) + array root (lines) with object items",
    description: `Commercial-services invoice with company letterhead, parties block, line-item table, totals block, payment-terms footer. Layout uses real <table> elements (not flexbox) so DOCX output matches the PDF.`,
  },
  {
    slug: "offer-letter",
    title: "Offer letter",
    fictional: "Beacon Academy → new hire",
    fidelity: "PDF-first; DOCX renders editable",
    shape:
      "Nested object roots (company, candidate, position, signer) with no arrays",
    description: `Formal employment offer with letterhead, salutation, terms-of-employment block (rendered via <dl>), signature lines, sign-off. Friendly to DOCX recipients who need to edit before signing.`,
  },
  {
    slug: "certificate",
    title: "Certificate of completion",
    fictional: "Beacon Academy → graduate",
    fidelity: "PDF only — DOCX strips the design",
    shape:
      "Deep nesting (institution.*, program.*, signers.primary.*, signers.secondary.*)",
    description: `Landscape award certificate with @page directive, absolute-positioned ornamental borders, flex-layout signature area. PDF is polished and frameable; DOCX exists but isn't the same document.`,
  },
];

export default function Templates() {
  return (
    <div className="mx-auto max-w-5xl px-6 py-16">
      <p className="text-xs uppercase tracking-widest text-amber-300/80 mb-4">Reference templates</p>
      <h1 className="text-3xl sm:text-4xl font-semibold tracking-tight mb-4">
        Three templates, three fidelity stories
      </h1>
      <p className="text-lg text-zinc-400 max-w-2xl mb-12">
        Every sandbox key gets these three templates seeded into its
        workspace, ready to render. They&apos;re the showcase set the docs
        site is built around — different shapes of merge fields, different
        layouts, different fidelity profiles across PDF and DOCX.
      </p>

      <div className="space-y-6">
        {TEMPLATES.map((t) => (
          <div
            key={t.slug}
            className="rounded-lg border border-zinc-800/80 bg-zinc-900/40 p-6"
          >
            <div className="flex flex-wrap items-baseline justify-between gap-3 mb-2">
              <h2 className="text-xl font-semibold text-zinc-100">{t.title}</h2>
              <code className="text-xs font-mono text-amber-200 bg-amber-300/10 rounded px-2 py-1">
                {t.slug}
              </code>
            </div>
            <p className="text-sm text-zinc-500 mb-4">{t.fictional}</p>
            <p className="text-sm text-zinc-300 leading-relaxed mb-4">{t.description}</p>
            <dl className="grid sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
              <div>
                <dt className="text-zinc-500 mb-1">Shape</dt>
                <dd className="text-zinc-300">{t.shape}</dd>
              </div>
              <div>
                <dt className="text-zinc-500 mb-1">Fidelity</dt>
                <dd className="text-zinc-300">{t.fidelity}</dd>
              </div>
            </dl>
          </div>
        ))}
      </div>

      <div className="mt-10 rounded-lg border border-amber-400/30 bg-amber-400/5 p-6">
        <p className="text-sm text-zinc-200 leading-relaxed mb-3">
          <span className="font-medium text-amber-200">Render them yourself.</span>{" "}
          Open the <Link href="/reference" className="text-amber-300 underline-offset-4 hover:underline">interactive reference</Link>,
          mint a sandbox key (one click), and the three templates above are
          pre-seeded in your workspace. Paste sample data and watch the PDF
          appear.
        </p>
        <p className="text-xs text-zinc-500">
          All brands are fictional. Northcliffe Legal, Ridgeline Supply, and
          Beacon Academy don&apos;t exist — they&apos;re portfolio fixtures.
        </p>
      </div>
    </div>
  );
}
