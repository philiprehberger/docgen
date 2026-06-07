import Link from "next/link";
import { DocsLayout } from "../../components/DocsLayout";

export const metadata = { title: "Concepts" };

const CONCEPTS = [
  {
    href: "/concepts/templating",
    title: "Twig templating",
    summary: "HTML + Twig source, sandboxed evaluation, the allowed tags / filters / functions.",
  },
  {
    href: "/concepts/fields",
    title: "Merge-field discovery",
    summary: "Walk the template AST, infer the field schema, validate input data before render.",
  },
  {
    href: "/concepts/versioning",
    title: "Frozen versions",
    summary: "Once a version exists, its body never changes. Renders pin a version, not a draft.",
  },
  {
    href: "/concepts/async",
    title: "Async job lifecycle",
    summary: "POST → 202 → poll. Or ?sync=true for inline. Idempotency keys for safe retries.",
  },
  {
    href: "/concepts/signed-urls",
    title: "Signed downloads",
    summary: "temporarySignedRoute, configurable TTL, no public buckets, no auth headers on the link.",
  },
  {
    href: "/concepts/docx-fidelity",
    title: "DOCX fidelity",
    summary: "Honest tradeoffs — what survives the HTML → DOCX conversion, what doesn't, when to skip DOCX.",
  },
];

export default function Concepts() {
  return (
    <DocsLayout>
      <h1>Concepts</h1>
      <p>
        Six pages covering the load-bearing primitives. Read them in order if
        you&apos;re new to the API; skim the table of contents on the left if
        you&apos;re already familiar.
      </p>
      <div className="not-prose grid sm:grid-cols-2 gap-3 mt-8">
        {CONCEPTS.map((c) => (
          <Link
            key={c.href}
            href={c.href}
            className="rounded-lg border border-zinc-800/80 bg-zinc-900/40 p-5 hover:border-amber-300/40 transition-colors"
          >
            <p className="font-medium text-zinc-100 mb-1">{c.title}</p>
            <p className="text-sm text-zinc-400 leading-relaxed">{c.summary}</p>
          </Link>
        ))}
      </div>
    </DocsLayout>
  );
}
