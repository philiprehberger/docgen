import { SdkPage } from "../../../components/SdkPage";

export const metadata = { title: "Python SDK" };

export default function Page() {
  return (
    <SdkPage
      snippets={{
        lang: "Python",
        pkg: "philiprehberger-docgen",
        install: "pip install philiprehberger-docgen",
        sourceUrl: "https://github.com/philiprehberger/docgen/tree/main/sdks/python",
        quickstartLang: "python",
        quickstart: `import os

from philiprehberger_docgen import (
    Configuration, ApiClient, TemplatesApi, RendersApi,
    TemplateCreate, RenderCreate,
)
from philiprehberger_docgen_sdk import poll_render

config = Configuration(
    host="https://api.docgen.philiprehberger.com",
    access_token=os.environ["DOCGEN_API_KEY"],   # docgen_live_…
)
client = ApiClient(config)
templates = TemplatesApi(client)
renders = RendersApi(client)

# 1. Create a template
template = templates.create_template(TemplateCreate(
    name="Invoice",
    body="<h1>Invoice {{ number }}</h1><p>Total: {{ total }}</p>",
))

# 2. Freeze v1
templates.create_template_version(template_id=template.id)

# 3. Submit a render
render = renders.create_render(RenderCreate(
    template_id=template.id,
    formats=["pdf"],
    data={"number": "INV-001", "total": "$2,625.00"},
))

# 4. Poll until terminal
done = poll_render(renders, render.id, max_wait_ms=30_000)

for output in done.outputs:
    if output.format == "pdf":
        print(f"Download: {output.url}")`,
        pollLang: "python",
        poll: `from philiprehberger_docgen_sdk import poll_render, PollRenderTimeout

try:
    done = poll_render(
        renders,
        render.id,
        max_wait_ms=30_000,
        initial_interval_ms=500,
        max_interval_ms=5_000,
        backoff_factor=1.6,
    )
except PollRenderTimeout as e:
    # e.render_id, e.max_wait_ms
    ...`,
        notes: (
          <>
            <p>
              <strong>Frameworks.</strong> The client is pure-stdlib HTTP via
              urllib3, so it slots into FastAPI, Django, Flask, or a plain
              script identically. No global state, no monkey-patching.
            </p>
            <p className="mt-3">
              <strong>Typing.</strong> Pydantic v2 models, full type hints,{" "}
              <code className="text-amber-300/90 text-xs">py.typed</code>{" "}
              shipped — mypy + pyright see the SDK as fully typed.
            </p>
          </>
        ),
      }}
    />
  );
}
