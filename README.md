# Docgen

A document generation API. POST a template + data, get HTML, PDF, or DOCX back.

- **Live demo:** https://docgen.philiprehberger.com *(coming online during deploy phase)*
- **API:** https://api.docgen.philiprehberger.com
- **Author:** Philip Rehberger ([philiprehberger.com](https://philiprehberger.com))

This is a portfolio-grade demo. The whole product surface — templates,
versioning, async renders, signed download URLs, SDKs in three languages,
Filament admin dashboard, deploy pipeline — exists to demonstrate what a
single engineer can ship end-to-end.

## What it does

- **Template authoring** in HTML + Twig with sandboxed evaluation.
- **Merge-field discovery** — the API walks the Twig AST and tells you what
  variables a template expects before you submit data.
- **Frozen versioning** — once a template version has rendered anything,
  the body is immutable. Renders pin a version, not a draft.
- **Multi-format output** from one source — HTML, PDF (Chromium via
  Browsershot), DOCX (PhpOffice/PhpWord).
- **Async lifecycle** — `POST /v1/renders` returns 202 + a poll URL.
  `?sync=true` blocks up to 15 seconds for small renders.
- **Signed download URLs** with a configurable TTL (default 1h, max 24h).
- **Per-render observability** — input data hash, output hash, render
  duration, Chromium version, asset fetch count.

## Stack

- Laravel 13.x, PHP 8.3, MySQL 8 / sqlite (local), Redis 7 + Horizon (production)
- Filament 5 for the admin dashboard
- Twig 3 for templates, sandboxed via `Twig\Extension\SandboxExtension`
- Spatie/Browsershot + puppeteer Chromium for PDF
- PhpOffice/PhpWord for DOCX
- Next.js 16 + Scalar for the docs site *(Phase 8)*
- OpenAPI 3.1 hand-authored as the source of truth
- Apache + mod_php + Let's Encrypt on EC2

## Local setup

```bash
git clone git@github.com:philiprehberger/docgen.git
cd docgen
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve            # localhost:8000

# Admin dashboard (after seeding a user)
# http://localhost:8000/admin
```

Local dev uses `sqlite` + `QUEUE_CONNECTION=sync` — jobs run inline, no Redis
needed. Switch to MySQL + Redis + Horizon to mirror production.

## Tests

```bash
php artisan test            # full PHPUnit suite
./vendor/bin/pint           # code style (Laravel preset)
```

## OpenAPI

`openapi/spec.yaml` is the source of truth. Run Spectral to lint:

```bash
npm run openapi:lint
```

Controllers must conform to the spec — CI fails on drift.

## Deploy

Atomic releases to EC2 via `npm run deploy`. See `scripts/deploy/` and the
guide at `~/projects/income-ops/.guides/new_demo_project.md`.

```bash
cp .env.deployment.example .env.deployment
# Fill in SERVER_HOST, SERVER_USERNAME, SERVER_PRIVATE_KEY
npm run deploy
npm run deploy:health
```

## License

MIT. See [LICENSE](LICENSE).
