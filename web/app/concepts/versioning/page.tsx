import { DocsLayout } from "../../../components/DocsLayout";

export const metadata = { title: "Frozen versions" };

export default function Page() {
  return (
    <DocsLayout>
      <h1>Frozen versions</h1>
      <p>
        Templates are mutable — you edit them, save them, iterate. Renders
        aren&apos;t. When you submit a render, you pin a <em>version</em>,
        and the version&apos;s body is immutable forever.
      </p>

      <h2>Why versions exist</h2>
      <p>
        Imagine an invoice template that ships in June. Twenty invoices
        render against it. In July, you tweak the layout — change the
        margin, swap a logo, fix a typo. Without versions, the next time
        someone re-renders June&apos;s PDFs (because their email got eaten,
        or for an audit), they get the July layout. That&apos;s wrong.
        Renders should always reproduce <em>exactly</em> what shipped.
      </p>

      <p>
        Versions solve this by snapshotting the template body at freeze
        time. Once a version exists, its <code>body_snapshot</code> never
        changes — not even if you delete the template, not even if you edit
        the draft. Reproducibility by construction.
      </p>

      <h2>Lifecycle</h2>
      <pre>{`POST /v1/templates           ← creates a draft (mutable)
PATCH /v1/templates/{id}     ← edits the draft
POST /v1/templates/{id}/versions   ← freezes the current draft as v1
                                     (or v2, v3, … — auto-incremented)
POST /v1/renders             ← submits a render against a specific version`}</pre>

      <h2>What gets frozen</h2>
      <p>A version captures three things, atomically:</p>
      <ul>
        <li>The <code>body_snapshot</code> — the full template source at freeze time.</li>
        <li>The <code>fields_schema</code> — the merge-field schema discovered from that body. <em>So even if the discovery logic changes later, the schema reported at freeze time is what renders validate against.</em></li>
        <li>The <code>label</code> — auto-incremented (<code>v1</code>, <code>v2</code>, …).</li>
      </ul>

      <h2>Choosing a version at render time</h2>
      <p>
        The render request takes an optional <code>version</code> field:
      </p>
      <pre>{`POST /v1/renders
{
  "template_id": "01HX...",
  "version": "v3",       // optional; defaults to the latest version
  "formats": ["pdf"],
  "data": { ... }
}`}</pre>

      <p>
        If you omit <code>version</code>, the API uses the latest. If you
        pass an explicit label that doesn&apos;t exist, you get a 404.
      </p>

      <h2>What if the template has no versions?</h2>
      <p>
        Renders refuse to run against an unversioned template:
      </p>
      <pre>{`{
  "type": "about:blank",
  "title": "Unprocessable",
  "status": 422,
  "detail": "Template has no frozen versions. Freeze a version with POST /v1/templates/{id}/versions before rendering."
}`}</pre>

      <p>
        This is deliberate. Renders against an in-progress draft are how
        documents end up looking different than expected six months later.
        Freezing is one HTTP call; do it once before you go live.
      </p>

      <h2>Versioning strategy in practice</h2>
      <ul>
        <li>
          <strong>Freeze before deploy.</strong> Lock the template version
          in CI right before your code ships, so the deployed code and the
          template version that&apos;s live both bear the same release tag.
        </li>
        <li>
          <strong>Pin the version in your code.</strong> Store{" "}
          <code>template_version: &quot;v3&quot;</code> in your config, not in your
          database. Bumps are deploys, not data migrations.
        </li>
        <li>
          <strong>Keep the draft livable.</strong> Edit freely between
          freezes. The draft is your sandbox; the version is the contract.
        </li>
      </ul>

      <h2>Next</h2>
      <p>
        <a href="/concepts/async">Async job lifecycle</a> — how renders queue,
        run, and resolve.
      </p>
    </DocsLayout>
  );
}
