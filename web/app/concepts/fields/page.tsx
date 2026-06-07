import { DocsLayout } from "../../../components/DocsLayout";

export const metadata = { title: "Merge-field discovery" };

export default function Page() {
  return (
    <DocsLayout>
      <h1>Merge-field discovery</h1>
      <p>
        Before you submit data to render a template, the API can tell you
        exactly which fields the template expects. Walk the AST, infer the
        type from usage, return a schema. <code>GET /v1/templates/&#123;id&#125;/fields</code>{" "}
        is the endpoint.
      </p>

      <h2>How it works</h2>
      <p>
        The template body is parsed into a Twig AST. The discovery walker
        scans every <code>NameExpression</code> and <code>GetAttrExpression</code>{" "}
        node, builds a tree of <code>&#123;name, type, children, item_type&#125;</code>{" "}
        nodes, and emits the result. Types come from usage:
      </p>
      <ul>
        <li><code>{`{{ client_name }}`}</code> → <code>client_name</code> is <code>scalar</code>.</li>
        <li><code>{`{{ user.name }}, {{ user.email }}`}</code> → <code>user</code> is <code>object</code>{" "} with children <code>name</code> and <code>email</code>.</li>
        <li><code>{`{% for item in items %}…{% endfor %}`}</code> → <code>items</code> is <code>array</code> with <code>item_type: scalar</code>.</li>
        <li><code>{`{% for line in lines %}{{ line.amount }}{% endfor %}`}</code> → <code>lines</code> is <code>array</code> with <code>item_type: object</code>, child <code>amount</code>.</li>
      </ul>

      <h2>Example</h2>
      <p>Given this template:</p>
      <pre>{`<h1>Invoice {{ invoice.number }}</h1>
<p>For {{ client.name }} ({{ client.email }})</p>
{% for line in lines %}
  <tr>
    <td>{{ line.description }}</td>
    <td>{{ line.amount|number_format(2) }}</td>
  </tr>
{% endfor %}
<p>Total: {{ totals.subtotal }} + {{ totals.tax }}</p>`}</pre>

      <p>
        <code>GET /v1/templates/&#123;id&#125;/fields</code> returns:
      </p>
      <pre>{`{
  "fields": [
    {
      "name": "invoice",
      "type": "object",
      "required": true,
      "children": [
        { "name": "number", "type": "scalar", "required": true }
      ]
    },
    {
      "name": "client",
      "type": "object",
      "required": true,
      "children": [
        { "name": "name", "type": "scalar", "required": true },
        { "name": "email", "type": "scalar", "required": true }
      ]
    },
    {
      "name": "lines",
      "type": "array",
      "required": true,
      "item_type": "object",
      "children": [
        { "name": "description", "type": "scalar", "required": true },
        { "name": "amount", "type": "scalar", "required": true }
      ]
    },
    {
      "name": "totals",
      "type": "object",
      "required": true,
      "children": [
        { "name": "subtotal", "type": "scalar", "required": true },
        { "name": "tax", "type": "scalar", "required": true }
      ]
    }
  ]
}`}</pre>

      <h2>What gets excluded</h2>
      <ul>
        <li>
          <strong>Loop variables.</strong> In <code>{`{% for line in lines %}`}</code>,{" "}
          <code>line</code> is a local, not a merge field — only <code>lines</code> is reported.
        </li>
        <li>
          <strong><code>{`{% set %}`}</code> assignments.</strong>{" "}
          <code>{`{% set total = a + b %}`}</code> makes <code>total</code> local;
          only <code>a</code> and <code>b</code> are merge fields.
        </li>
        <li>
          <strong>Twig internals.</strong> <code>loop.index</code>,{" "}
          <code>loop.first</code>, <code>_key</code>, <code>_context</code>{" "}
          are pseudo-variables — never reported.
        </li>
      </ul>

      <h2>Required flag</h2>
      <p>
        Every discovered field is <code>required: true</code> in v0.5 — referencing
        a variable in a template implies the renderer needs it. Optional
        merge fields with default values are a v2 feature; until then, use{" "}
        Twig&apos;s <code>default</code> filter inside the template if you
        want graceful handling of missing data:
      </p>
      <pre>{`{{ optional_note|default("No note provided.") }}`}</pre>

      <h2>Validation at render time</h2>
      <p>
        When you submit a render, the API validates your <code>data</code>{" "}
        against the version&apos;s frozen schema. Missing required fields
        return <code>422 Unprocessable Entity</code> with a per-field error:
      </p>
      <pre>{`{
  "type": "about:blank",
  "title": "Validation failed",
  "status": 422,
  "detail": "Input data is missing required merge fields.",
  "errors": {
    "data": ["Missing field: client.name"]
  }
}`}</pre>

      <h2>Next</h2>
      <p>
        <a href="/concepts/versioning">Frozen versions</a> — why the schema
        is captured at freeze time, not render time.
      </p>
    </DocsLayout>
  );
}
