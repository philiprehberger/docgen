import { SdkPage } from "../../../components/SdkPage";

export const metadata = { title: "TypeScript SDK" };

export default function Page() {
  return (
    <SdkPage
      snippets={{
        lang: "TypeScript",
        pkg: "@philiprehberger/docgen",
        install: "npm install @philiprehberger/docgen",
        sourceUrl: "https://github.com/philiprehberger/docgen/tree/main/sdks/typescript",
        quickstartLang: "typescript",
        quickstart: `import {
  Configuration,
  TemplatesApi,
  RendersApi,
  pollRender,
} from '@philiprehberger/docgen';

const config = new Configuration({
  basePath: 'https://api.docgen.philiprehberger.com',
  accessToken: process.env.DOCGEN_API_KEY,   // docgen_live_…
});

const templates = new TemplatesApi(config);
const renders = new RendersApi(config);

// 1. Create a template
const template = await templates.createTemplate({
  templateCreate: {
    name: 'Invoice',
    body: '<h1>Invoice {{ number }}</h1><p>Total: {{ total }}</p>',
  },
});

// 2. Freeze v1
await templates.createTemplateVersion({ templateId: template.id });

// 3. Submit a render
const render = await renders.createRender({
  renderCreate: {
    templateId: template.id,
    formats: ['pdf'],
    data: { number: 'INV-001', total: '$2,625.00' },
  },
});

// 4. Poll until terminal
const done = await pollRender(renders, render.id);

if (done.status === 'succeeded') {
  const pdfUrl = done.outputs.find((o) => o.format === 'pdf')!.url;
  console.log(\`Download: \${pdfUrl}\`);
}`,
        pollLang: "typescript",
        poll: `import { pollRender, PollRenderTimeout } from '@philiprehberger/docgen';

const controller = new AbortController();

try {
  const done = await pollRender(renders, render.id, {
    maxWaitMs: 30_000,
    signal: controller.signal,
  });
} catch (e) {
  if (e instanceof PollRenderTimeout) {
    // Render didn't finish in time; the job is still running.
  }
}`,
        notes: (
          <>
            <p>
              <strong>Sync mode.</strong> Add{" "}
              <code className="text-amber-300/90 text-xs">?sync=true</code>{" "}
              to the request via the runtime override to block up to 15s
              for an inline response — useful for single-page renders
              where you don&apos;t want to poll.
            </p>
            <p className="mt-3">
              <strong>AbortSignal.</strong> The <code className="text-amber-300/90 text-xs">signal</code>{" "}
              option threads through the polling loop, so you can wire it
              to a UI cancel button without leaving an orphan timer running.
            </p>
          </>
        ),
      }}
    />
  );
}
