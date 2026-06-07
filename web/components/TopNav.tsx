import Link from "next/link";

const NAV = [
  { href: "/concepts", label: "Concepts" },
  { href: "/reference", label: "Reference" },
  { href: "/sdks", label: "SDKs" },
  { href: "/templates", label: "Templates" },
  { href: "/downloads", label: "Downloads" },
  { href: "/pricing", label: "Pricing" },
  { href: "/status", label: "Status" },
  { href: "/about", label: "About" },
];

export function TopNav() {
  return (
    <header className="border-b border-zinc-800 bg-zinc-950 sticky top-0 z-40">
      <div className="mx-auto max-w-6xl px-6 h-14 flex items-center justify-between">
        <Link
          href="/"
          className="text-sm font-medium tracking-tight text-zinc-100 hover:text-amber-300 transition-colors"
        >
          Docgen
        </Link>
        <nav className="hidden md:flex items-center gap-6 text-sm">
          {NAV.map((item) => (
            <Link
              key={item.href}
              href={item.href}
              className="text-zinc-300 hover:text-zinc-100 transition-colors"
            >
              {item.label}
            </Link>
          ))}
        </nav>
        <Link
          href="/reference"
          className="text-sm rounded-md bg-amber-400/15 text-amber-200 hover:bg-amber-400/25 transition-colors px-3 py-1.5"
        >
          Try it
        </Link>
      </div>
    </header>
  );
}
