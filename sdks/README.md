# SDKs

Generated SDKs for the Docgen API in three languages. All three are produced from `openapi/spec.yaml` via the [openapi-generator](https://openapi-generator.tech) project, then layered with a hand-written ergonomics helper per language.

## Layout

```
sdks/
├── typescript/
│   ├── generated/            ← openapi-generator output (do not hand-edit)
│   ├── src/
│   │   ├── pollRender.ts     ← hand-tuned exponential-backoff helper
│   │   └── index.ts          ← re-exports generated + helpers
│   ├── package.json
│   └── README.md
├── php/
│   ├── generated/
│   ├── src/
│   │   ├── PollRender.php
│   │   └── PollRenderTimeout.php
│   ├── composer.json
│   └── README.md
└── python/
    ├── generated/
    ├── philiprehberger_docgen_sdk/
    │   ├── __init__.py
    │   └── poll_render.py
    ├── pyproject.toml
    └── README.md
```

The generated directories are committed to the repo so consumers can `git clone` and use the SDK directly. CI re-runs the generation and fails the build if the committed code drifts from what the spec produces — keeps the SDKs honest.

## Regenerate

```bash
npm run sdk:generate
```

This wipes `*/generated/` and re-runs each generator from `openapi/spec.yaml`. Hand-written code in `sdks/<lang>/src/` is untouched.

## Build downloadable zips

```bash
npm run sdk:zip
```

Writes three zips to `public/downloads/sdks/` — the docs site's Downloads page links to them so prospects can grab them without running any tooling.

## Hand-written ergonomics — `pollRender`

All three SDKs ship the same five-method API + one hand-written helper:

| Method | What it does |
|---|---|
| `templates.create/get/update/delete/listTemplates` | Generated |
| `templates.createTemplateVersion / listTemplateVersions / getTemplateVersion` | Generated |
| `templates.getTemplateFields` | Generated |
| `renders.create/get/cancel/listRenders` | Generated |
| `renders.downloadRenderOutput` | Generated |
| **`pollRender(api, id, options)`** | **Hand-written.** Exponential backoff + wall-clock budget. |

Why hand-write `pollRender` instead of letting the SDK consumer write their own polling loop? Because the generated client gives them `getRender` and nothing else, and the obvious polling loop spams the server, wastes rate limit budget, and doesn't compose with `AbortSignal` (or its language equivalent). Shipping a sane default removes that footgun.

## Contract test parity

A 5-test contract runs across all three SDKs (Phase 6 deliverable):

1. `templates.create` returns a template with the slug we sent.
2. `templates.createTemplateVersion` increments the version label.
3. `renders.create` returns a render id; `pollRender` reaches `succeeded`.
4. `renders.downloadRenderOutput` resolves to a valid signed URL.
5. `apiKeys.mint` returns the plaintext once and never again.

Each SDK's tests run in its own language's CI matrix.

## License

MIT.
