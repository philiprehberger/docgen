import { DocsLayout } from "../../../components/DocsLayout";

export const metadata = { title: "Signed downloads" };

export default function Page() {
  return (
    <DocsLayout>
      <h1>Signed downloads</h1>
      <p>
        Rendered outputs are stored on disk and served via Laravel&apos;s{" "}
        <code>temporarySignedRoute</code>. The URL carries a signature and an
        expiry, so anyone with the link can download for the lifetime of the
        signature — and no longer.
      </p>

      <h2>What a signed URL looks like</h2>
      <pre>{`https://api.docgen.philiprehberger.com/v1/renders/01HY.../outputs/pdf
  ?expires=1717891234
  &signature=a8f3c2b09e...`}</pre>

      <p>
        No <code>Authorization</code> header is needed on the GET. The
        signature <em>is</em> the auth. This means:
      </p>
      <ul>
        <li>You can paste the URL into an email and it just works.</li>
        <li>You can return it from your backend to a browser and the
            browser downloads directly — no proxy through your server.</li>
        <li>You can attach it to a Slack message; the preview unfurls.</li>
      </ul>

      <h2>TTL</h2>
      <p>
        The signature expires. By default, signed URLs live for one hour.
        You can override per-request, up to the workspace ceiling
        (default 24 hours):
      </p>
      <pre>{`POST /v1/renders
{
  "template_id": "01HX...",
  "formats": ["pdf"],
  "data": { ... },
  "signed_url_ttl": 21600    // 6 hours, in seconds
}`}</pre>

      <p>
        Once a render succeeds, its outputs are stored. When you poll
        <code>GET /v1/renders/&#123;id&#125;</code>, the API mints a fresh
        signed URL each time, so subsequent calls always return a URL valid
        for the configured TTL <em>from now</em> — not from the original
        render submit time.
      </p>

      <h2>What happens at expiry</h2>
      <p>
        After <code>expires</code> passes, the URL returns:
      </p>
      <pre>{`401 Unauthorized
Content-Type: application/problem+json
{
  "type": "about:blank",
  "title": "Unauthorized",
  "status": 401,
  "detail": "Signed URL is missing or expired."
}`}</pre>

      <p>
        The file itself sticks around longer — there&apos;s a cleanup cron
        that sweeps expired files past their TTL plus a grace window. If
        you re-poll the render, you get a fresh signed URL pointing at the
        same file, until the cleanup runs.
      </p>

      <h2>Why not S3 + pre-signed URLs?</h2>
      <p>
        S3 is the right answer for production scale, and the codebase is
        structured to swap to it by changing one filesystem driver. For a
        portfolio demo, local disk + Laravel signed URLs proves out the
        same pattern without dragging in AWS account state. <em>If you&apos;re
        building this for a real customer, S3 with the same signed-URL flow
        is a one-config-line change.</em>
      </p>

      <h2>Security model — what signed URLs do and don&apos;t do</h2>
      <p>
        <strong>Do:</strong> stop accidental URL guessing. The signature is
        HMAC-SHA256 over the URL path + expiry + the app&apos;s secret key.
        Brute-forcing it is infeasible.
      </p>
      <p>
        <strong>Don&apos;t:</strong> stop the URL from being shared. Anyone
        with the link can download for the lifetime of the signature. If
        you need recipient-bound URLs (only Alice can download), build a
        thin gateway in your app that resolves recipient → signed URL on
        demand instead of returning the signed URL directly.
      </p>

      <h2>Filename + MIME type</h2>
      <p>
        Downloads come with <code>Content-Disposition: attachment; filename="render-&#123;id&#125;.&#123;format&#125;"</code>{" "}
        and the appropriate MIME type:
      </p>
      <ul>
        <li><code>application/pdf</code> for PDF</li>
        <li><code>application/vnd.openxmlformats-officedocument.wordprocessingml.document</code> for DOCX</li>
        <li><code>text/html; charset=utf-8</code> for HTML</li>
      </ul>

      <h2>Next</h2>
      <p>
        <a href="/concepts/docx-fidelity">DOCX fidelity</a> — what survives
        the HTML → DOCX conversion, what doesn&apos;t.
      </p>
    </DocsLayout>
  );
}
