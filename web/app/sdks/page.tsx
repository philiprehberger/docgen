import Link from "next/link";

export const metadata = { title: "SDKs" };

const SDKS = [
  {
    href: "/sdks/typescript",
    lang: "TypeScript",
    install: "npm i @philiprehberger/docgen",
    pkg: "@philiprehberger/docgen",
    badgeAlt: "npm version",
    badgeSrc:
      "https://img.shields.io/npm/v/@philiprehberger/docgen.svg?label=npm&color=f59e0b",
    blurb: "Node 18+. Dual ESM + CJS output. AbortSignal-aware pollRender helper.",
  },
  {
    href: "/sdks/php",
    lang: "PHP",
    install: "composer require philiprehberger/docgen",
    pkg: "philiprehberger/docgen",
    badgeAlt: "Packagist version",
    badgeSrc:
      "https://img.shields.io/packagist/v/philiprehberger/docgen.svg?label=packagist&color=f59e0b",
    blurb: "PHP 8.2+. Guzzle 7 HTTP client. Laravel-friendly types.",
  },
  {
    href: "/sdks/python",
    lang: "Python",
    install: "pip install philiprehberger-docgen",
    pkg: "philiprehberger-docgen",
    badgeAlt: "PyPI version",
    badgeSrc:
      "https://img.shields.io/pypi/v/philiprehberger-docgen.svg?label=pypi&color=f59e0b",
    blurb: "Python 3.9+. urllib3-based, pydantic v2. FastAPI / Django / Flask compatible.",
  },
];

export default function SDKsIndex() {
  return (
    <div className="mx-auto max-w-5xl px-6 py-16">
      <p className="text-xs uppercase tracking-widest text-amber-300/80 mb-4">SDKs</p>
      <h1 className="text-3xl sm:text-4xl font-semibold tracking-tight mb-4">
        One spec, three languages
      </h1>
      <p className="text-lg text-zinc-400 max-w-2xl mb-12">
        Published to npm, Packagist, and PyPI. Each package is generated from
        the OpenAPI spec, then layered with a hand-tuned <code className="text-amber-300/90 text-base">pollRender</code>{" "}
        helper that backs off exponentially and respects a wall-clock budget.
        Identical 5-test contract across every language so behavior is portable.
      </p>
      <div className="grid sm:grid-cols-2 gap-4">
        {SDKS.map((s) => (
          <Link
            key={s.href}
            href={s.href}
            className="block rounded-lg border border-zinc-800 bg-zinc-900/40 p-5 hover:border-zinc-700 hover:bg-zinc-900/70 transition-colors"
          >
            <div className="flex items-baseline justify-between mb-1">
              <p className="text-sm uppercase tracking-widest text-zinc-500">{s.lang}</p>
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img src={s.badgeSrc} alt={s.badgeAlt} className="h-4" />
            </div>
            <p className="text-base font-medium text-zinc-100 mb-2 font-mono break-all">{s.pkg}</p>
            <p className="text-sm text-zinc-400 mb-4">{s.blurb}</p>
            <p className="text-xs font-mono text-amber-300/80">{s.install}</p>
          </Link>
        ))}
      </div>

      <div className="mt-12 rounded-lg border border-zinc-800/80 bg-zinc-900/40 p-6">
        <p className="text-sm text-zinc-300 leading-relaxed">
          <span className="text-zinc-100 font-medium">Why only three?</span>{" "}
          Webhook Relay shipped four (TS, PHP, Python, Go). Docgen drops Go
          because the spec is bigger and three languages tells the same
          story — same person can hand-author the spec, generate the clients,
          and ship a workable SDK in any language with a working OpenAPI
          generator. Go (and .NET, Ruby, Swift, …) all live one{" "}
          <code className="text-amber-300/90 text-xs">npm run sdk:generate</code>{" "}
          away.
        </p>
      </div>
    </div>
  );
}
