import { DocsLayout } from "../../../components/DocsLayout";

export const metadata = { title: "Twig templating" };

export default function Page() {
  return (
    <DocsLayout>
      <h1>Twig templating</h1>
      <p>
        Docgen templates are HTML + <a href="https://twig.symfony.com/">Twig</a>.
        Twig is a battle-tested templating language from the Symfony ecosystem
        with a well-documented security model. Docgen runs every template in
        a strict sandbox — no PHP function passthrough, no file includes from
        arbitrary paths, no <code>attribute()</code> magic. Templates run as
        data.
      </p>

      <h2>Syntax basics</h2>
      <pre>{`<h1>Hello {{ name }}</h1>

{% if subscriber.active %}
  <p>Welcome back, {{ subscriber.first_name }}.</p>
{% endif %}

{% for line in invoice.lines %}
  <tr>
    <td>{{ line.description }}</td>
    <td>{{ line.amount|number_format(2) }}</td>
  </tr>
{% endfor %}`}</pre>

      <p>
        Three core constructs:
      </p>
      <ul>
        <li><code>{`{{ expr }}`}</code> — print an expression, auto-escaped for HTML by default.</li>
        <li><code>{`{% tag %}`}</code> — control-flow tags like <code>if</code>, <code>for</code>, <code>set</code>.</li>
        <li><code>{`expr|filter`}</code> — apply a filter, eg. <code>upper</code>, <code>number_format(2)</code>, <code>date("Y-m-d")</code>.</li>
      </ul>

      <h2>What the sandbox allows</h2>

      <p><strong>Tags</strong> — <code>if</code>, <code>for</code>, <code>else</code>, <code>elseif</code>, <code>set</code>, <code>spaceless</code>, <code>apply</code></p>

      <p><strong>Filters</strong> — <code>escape</code> / <code>e</code>, <code>raw</code>, <code>length</code>, <code>lower</code>, <code>upper</code>, <code>title</code>, <code>capitalize</code>, <code>trim</code>, <code>join</code>, <code>split</code>, <code>default</code>, <code>number_format</code>, <code>date</code>, <code>replace</code>, <code>striptags</code>, <code>nl2br</code>, <code>first</code>, <code>last</code>, <code>reverse</code>, <code>sort</code>, <code>keys</code>, <code>merge</code>, <code>slice</code>, <code>abs</code>, <code>round</code>, <code>format</code>, <code>url_encode</code></p>

      <p><strong>Functions</strong> — <code>range</code>, <code>cycle</code>, <code>max</code>, <code>min</code>, <code>date</code></p>

      <p>
        Anything not on the list is refused at render time with a clear error
        message. If you need a filter that&apos;s missing, file an issue — we
        can usually add it after a security review.
      </p>

      <h2>Auto-escaping</h2>
      <p>
        Output is HTML-escaped by default. To render literal HTML (eg. you&apos;ve
        sanitized upstream), use the <code>raw</code> filter:
      </p>
      <pre>{`{{ rich_text|raw }}`}</pre>

      <p>
        Be careful — <code>raw</code> bypasses XSS protection. If the rendered
        document is going to a recipient who didn&apos;t supply the data, you
        almost certainly want auto-escaping on.
      </p>

      <h2>Comments</h2>
      <pre>{`{# This is a Twig comment. It does NOT appear in the rendered output. #}`}</pre>

      <h2>What templates can&apos;t do</h2>
      <ul>
        <li>Make HTTP requests (the sandbox has no network primitives).</li>
        <li>Read or write files (no <code>include</code>, no <code>source</code> beyond the template body itself).</li>
        <li>Call arbitrary PHP functions.</li>
        <li>Reference other templates — each render is one body, no composition. Composition is on the v2 roadmap.</li>
      </ul>

      <h2>Size cap</h2>
      <p>
        Templates are capped at <code>DOCGEN_TEMPLATE_BODY_MAX_BYTES</code>
        (default 256 KB). If your template is bigger, you&apos;re probably
        embedding base64 images — upload them via <code>POST /v1/assets</code>{" "}
        (coming in v2) and reference them by ID instead.
      </p>

      <h2>Next</h2>
      <p>
        <a href="/concepts/fields">Merge-field discovery</a> — how the API
        tells you which variables your template expects before you submit data.
      </p>
    </DocsLayout>
  );
}
