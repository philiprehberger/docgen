# @philiprehberger/docgen

Official TypeScript SDK for the [Docgen](https://docgen.philiprehberger.com) document-generation API. POST a template + data, get HTML / PDF / DOCX back.

## Install

```bash
npm install @philiprehberger/docgen
```

## Quickstart

```ts
import { Configuration, TemplatesApi, RendersApi, pollRender } from '@philiprehberger/docgen';

const config = new Configuration({
  basePath: 'https://api.docgen.philiprehberger.com',
  accessToken: process.env.DOCGEN_API_KEY,   // `docgen_live_…`
});

const templates = new TemplatesApi(config);
const renders = new RendersApi(config);

// 1. Author a template + freeze a version
const template = await templates.createTemplate({
  templateCreate: {
    name: 'Invoice',
    body: '<h1>Invoice {{ number }}</h1><p>Total: {{ total }}</p>',
  },
});

const version = await templates.createTemplateVersion({ templateId: template.id });
//  → version.label === 'v1'

// 2. Submit a render — async by default
const render = await renders.createRender({
  renderCreate: {
    templateId: template.id,
    formats: ['pdf'],
    data: { number: 'INV-001', total: '$2,625.00' },
  },
});

// 3. Poll until terminal
const done = await pollRender(renders, render.id);

if (done.status === 'succeeded') {
  const pdfUrl = done.outputs.find((o) => o.format === 'pdf')!.url;
  console.log(`Download: ${pdfUrl}`);
}
```

## The `pollRender` helper

The generated `getRender` method is the raw building block. `pollRender` is a hand-written ergonomics layer on top:

- Backs off exponentially (500ms → 800ms → 1.28s → … → 5s cap).
- Respects an overall `maxWaitMs` budget (default 60s).
- Cooperates with an `AbortSignal` for UI cancellation.

```ts
import { pollRender, PollRenderTimeout } from '@philiprehberger/docgen';

try {
  const done = await pollRender(renders, render.id, {
    maxWaitMs: 30_000,
    signal: controller.signal,
  });
} catch (e) {
  if (e instanceof PollRenderTimeout) {
    // Render didn't finish in time. The job is still running on the server.
  }
}
```

## Sync mode

Small renders that should land inside a single request — eg. a single-page invoice — can use sync mode:

```ts
// Add ?sync=true to the request. The Configuration layer doesn't expose
// this directly; use the fetch override or pass it via `runtime.queryParameters`.
const render = await renders.createRender({
  renderCreate: { /* ... */ },
}, { query: { sync: true } });

if (render.status === 'succeeded') {
  // Render finished within the workspace's sync timeout (default 15s).
} else {
  // Fell back to async. Use pollRender from here.
}
```

## License

MIT. See the [docgen repo](https://github.com/philiprehberger/docgen) for source.
