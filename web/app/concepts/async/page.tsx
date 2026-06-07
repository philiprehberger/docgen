import { DocsLayout } from "../../../components/DocsLayout";

export const metadata = { title: "Async job lifecycle" };

export default function Page() {
  return (
    <DocsLayout>
      <h1>Async job lifecycle</h1>
      <p>
        Renders run on a queue. By default, <code>POST /v1/renders</code>{" "}
        returns <code>202 Accepted</code> with a poll URL — the actual
        Chromium / PhpWord work happens in a worker. For small renders that
        comfortably finish inside a request, <code>?sync=true</code> blocks
        up to 15 seconds for an inline response.
      </p>

      <h2>State machine</h2>
      <pre>{`queued → rendering → succeeded   (terminal)
                  → failed      (terminal)
                  → cancelled   (terminal)`}</pre>

      <p>
        Once a render is in a terminal state, the record is immutable.
        <code>DELETE /v1/renders/&#123;id&#125;</code> on a terminal render
        returns 409.
      </p>

      <h2>Async (default)</h2>
      <pre>{`POST /v1/renders               → 202 Accepted
{                                {
  "template_id": "01HX...",        "id": "01HY...",
  "formats": ["pdf"],              "status": "queued",
  "data": { ... }                  "poll_url": "/v1/renders/01HY..."
}                                }`}</pre>

      <p>Poll until terminal:</p>
      <pre>{`GET /v1/renders/01HY...        → 200 OK
                                 {
                                   "status": "rendering",
                                   "duration_ms": null,
                                   "outputs": []
                                 }

GET /v1/renders/01HY...        → 200 OK
                                 {
                                   "status": "succeeded",
                                   "duration_ms": 873,
                                   "outputs": [
                                     {
                                       "format": "pdf",
                                       "url": "https://…?signature=…",
                                       "expires_at": "…",
                                       "bytes": 12480,
                                       "sha256": "…"
                                     }
                                   ]
                                 }`}</pre>

      <p>
        Polling cadence: every SDK ships a <code>pollRender</code> helper
        that backs off exponentially (500ms → 5s ceiling) and respects a
        wall-clock budget. Don&apos;t spam <code>getRender</code> at 50ms
        intervals — that&apos;s how you trip rate limits.
      </p>

      <h2>Sync (?sync=true)</h2>
      <p>
        Add <code>?sync=true</code> to block up to 15 seconds (configurable
        via <code>DOCGEN_SYNC_RENDER_TIMEOUT</code>) for a terminal state:
      </p>
      <pre>{`POST /v1/renders?sync=true     → 200 OK         (finished in time)
                               or 202 Accepted   (timed out; still running)`}</pre>

      <p>
        <code>200</code> means the render reached a terminal state inside
        the deadline — even if that terminal state is <code>failed</code>.
        Check <code>status</code> before assuming success.
      </p>

      <p>
        <code>202</code> from <code>?sync=true</code> means the render
        overshot the deadline; it&apos;s still running on the queue. Switch
        to polling from this point.
      </p>

      <h2>When to use sync vs async</h2>
      <table>
        <thead><tr><th>Use sync when…</th><th>Use async when…</th></tr></thead>
        <tbody>
          <tr>
            <td>Single-page render</td>
            <td>Multi-page document, complex layout</td>
          </tr>
          <tr>
            <td>HTML / DOCX only (PDF can be slow on cold-start)</td>
            <td>PDF render (Chromium cold-start adds ~500ms)</td>
          </tr>
          <tr>
            <td>User waiting in front of a screen</td>
            <td>Background email / batch job</td>
          </tr>
          <tr>
            <td>Latency budget &lt; 5s</td>
            <td>Latency budget &gt; 30s, or you don&apos;t care</td>
          </tr>
        </tbody>
      </table>

      <h2>Idempotency keys</h2>
      <p>
        Both modes support an <code>Idempotency-Key</code> header. Same key
        + same template version + same input data hash returns the cached
        render record; same key + different parameters returns 409 Conflict.
        Pair this with retry-safe job dispatch in your job runner.
      </p>
      <pre>{`POST /v1/renders
Idempotency-Key: invoice-2026-0114
{ ... }`}</pre>

      <h2>Cancellation</h2>
      <p>
        <code>DELETE /v1/renders/&#123;id&#125;</code> cancels a queued or
        in-flight render. Returns 204 on success, 409 if the render is
        already terminal. Cancelled renders don&apos;t produce outputs.
      </p>

      <h2>Result webhooks (v2)</h2>
      <p>
        Push notification of terminal status is on the v2 roadmap — a
        workspace-level <code>result_webhook</code> URL that POSTs the
        render record on completion. Until then, poll.
      </p>

      <h2>Next</h2>
      <p>
        <a href="/concepts/signed-urls">Signed downloads</a> — how the
        output URLs work.
      </p>
    </DocsLayout>
  );
}
