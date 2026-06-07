# CLAUDE.md — docgen/web

The Next.js 16 docs + marketing site for the Docgen API. Deploys to PM2
port 3006 on the EC2 host, reverse-proxied by Apache at
`docgen.philiprehberger.com`.

## Conventions

- **No AI attribution anywhere.** Mirrors the root repo rule.
- **Standalone output.** `next.config.ts` has `output: 'standalone'` — the
  deploy bundles `.next/standalone/` + copied-in public/ + .next/static
  into one tree, rsyncs to the server, `pm2 reload`.
- **Brand color is amber-400 / amber-300.** Mirrors Filament dashboard
  brand. No sky/blue accents.
- **Concept pages** under `app/concepts/<slug>/page.tsx` use the
  `DocsLayout` wrapper for the left-rail nav.
- **SDK pages** under `app/sdks/<lang>/page.tsx` use the `SdkPage`
  component with the same shape — install, quickstart, pollRender helper,
  notes.

## Routes

```
/                         landing
/concepts                 index of 6 concept pages
/concepts/templating
/concepts/fields
/concepts/versioning
/concepts/async
/concepts/signed-urls
/concepts/docx-fidelity
/reference                Scalar API reference + sandbox-key minter
/sdks                     index of 3 SDK pages
/sdks/typescript
/sdks/php
/sdks/python
/templates                showcase of 3 reference templates
/downloads                OpenAPI YAML/JSON, Postman, SDK zips, templates
/pricing                  mocked tiers (honest portfolio framing)
/about                    honest portfolio framing
/status                   live /v1/healthz poll
```

## Deploy

```bash
cp .env.example .env
# fill in SERVER_HOST, SERVER_USERNAME, etc.
npm run deploy
```

`npm run deploy` → `next build` (standalone) → copy public + static into
`.next/standalone/` → rsync to EC2 → `pm2 reload docgen-web`.
