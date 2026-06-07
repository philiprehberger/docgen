# philiprehberger-docgen

Official Python SDK for the [Docgen](https://docgen.philiprehberger.com) document-generation API.

## Install

```bash
pip install philiprehberger-docgen
```

Python ≥ 3.9.

## Quickstart

```python
import os
from philiprehberger_docgen import (
    Configuration,
    ApiClient,
    TemplatesApi,
    RendersApi,
    TemplateCreate,
    RenderCreate,
)
from philiprehberger_docgen_sdk import poll_render

config = Configuration(
    host="https://api.docgen.philiprehberger.com",
    access_token=os.environ["DOCGEN_API_KEY"],   # docgen_live_…
)
client = ApiClient(config)
templates = TemplatesApi(client)
renders = RendersApi(client)

# 1. Author a template + freeze a version
template = templates.create_template(TemplateCreate(
    name="Invoice",
    body="<h1>Invoice {{ number }}</h1><p>Total: {{ total }}</p>",
))

version = templates.create_template_version(template_id=template.id)

# 2. Submit a render — async by default
render = renders.create_render(RenderCreate(
    template_id=template.id,
    formats=["pdf"],
    data={"number": "INV-001", "total": "$2,625.00"},
))

# 3. Poll until terminal (hand-written ergonomics helper)
done = poll_render(renders, render.id, max_wait_ms=30_000)

for output in done.outputs:
    if output.format == "pdf":
        print(f"Download: {output.url}")
```

## `poll_render`

Hand-written ergonomics layer on top of `RendersApi.get_render`. Exponential backoff (500ms → 5s ceiling), configurable wall-clock budget, raises `PollRenderTimeout` if the deadline elapses.

## License

MIT.
