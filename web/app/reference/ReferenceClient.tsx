"use client";

import { ApiReferenceReact } from "@scalar/api-reference-react";
import "@scalar/api-reference-react/style.css";
import { useState } from "react";

type MintResponse = {
  api_key: string;
  prefix: string;
  last_four: string;
  workspace_id: string;
  expires_at: string;
  sample_template_ids: Record<string, string>;
  limits: {
    renders_per_minute: number;
    renders_per_day: number;
    key_lifetime_minutes: number;
  };
};

type MintError = {
  status: number;
  title: string;
  detail?: string;
};

const API_BASE = "https://api.docgen.philiprehberger.com";
const SECURITY_SCHEME_NAME = "bearerAuth"; // matches openapi/spec.yaml

export function ReferenceClient() {
  const [token, setToken] = useState<string | null>(null);
  const [sampleIds, setSampleIds] = useState<Record<string, string>>({});
  const [state, setState] = useState<
    | { kind: "idle" }
    | { kind: "loading" }
    | { kind: "error"; message: string }
    | { kind: "ready" }
  >({ kind: "idle" });

  async function mint() {
    setState({ kind: "loading" });
    try {
      const response = await fetch(`${API_BASE}/v1/sandbox/keys`, { method: "POST" });
      if (!response.ok) {
        const problem = (await response.json().catch(() => null)) as MintError | null;
        setState({
          kind: "error",
          message: problem?.detail ?? `Mint failed with status ${response.status}.`,
        });
        return;
      }
      const data = (await response.json()) as MintResponse;
      setToken(data.api_key);
      setSampleIds(data.sample_template_ids);
      setState({ kind: "ready" });
    } catch (err) {
      setState({
        kind: "error",
        message: err instanceof Error ? err.message : "Network error.",
      });
    }
  }

  return (
    <div>
      <SandboxBanner state={state} token={token} sampleIds={sampleIds} onMint={mint} />
      <ApiReferenceReact
        key={token ?? "no-token"}
        configuration={{
          url: "/openapi.yaml",
          theme: "deepSpace",
          layout: "modern",
          darkMode: true,
          hideClientButton: false,
          hideDownloadButton: false,
          persistAuth: true,
          metaData: {
            title: "Docgen — API reference",
          },
          servers: [
            {
              url: API_BASE,
              description: "Production",
            },
          ],
          authentication: token
            ? {
                preferredSecurityScheme: SECURITY_SCHEME_NAME,
                securitySchemes: {
                  [SECURITY_SCHEME_NAME]: {
                    token,
                  },
                },
              }
            : undefined,
        }}
      />
    </div>
  );
}

function SandboxBanner({
  state,
  token,
  sampleIds,
  onMint,
}: {
  state: { kind: "idle" | "loading" | "error" | "ready"; message?: string };
  token: string | null;
  sampleIds: Record<string, string>;
  onMint: () => void;
}) {
  return (
    <div className="border-b border-zinc-800 bg-zinc-900/60">
      <div className="mx-auto max-w-7xl px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div className="text-sm text-zinc-300 max-w-3xl">
          <span className="text-zinc-100 font-medium">Try the API.</span>{" "}
          Mint a 30-minute sandbox key and every try-it request below sends it as{" "}
          <span className="font-mono text-amber-300">Authorization: Bearer …</span>{" "}
          automatically.{" "}
          {state.kind === "ready" && Object.keys(sampleIds).length > 0 ? (
            <span className="text-zinc-500">
              Seeded templates ({Object.keys(sampleIds).join(", ")}) are ready to render.
            </span>
          ) : (
            <span className="text-zinc-500">
              Each mint seeds an isolated workspace with three reference templates: invoice, offer-letter, certificate.
            </span>
          )}
        </div>

        <div className="flex flex-col sm:items-end gap-2 min-w-0">
          {state.kind === "idle" && (
            <button
              onClick={onMint}
              className="rounded-md bg-amber-400 text-amber-950 hover:bg-amber-300 transition-colors px-3 py-1.5 text-sm font-medium"
            >
              Get sandbox key →
            </button>
          )}
          {state.kind === "loading" && (
            <span className="text-sm text-zinc-400">Minting…</span>
          )}
          {state.kind === "ready" && token && (
            <div className="flex items-center gap-2 max-w-full">
              <button
                onClick={onMint}
                className="rounded-md border border-zinc-700 hover:border-zinc-500 px-2.5 py-1 text-xs text-zinc-300 transition-colors shrink-0"
                title="Mint another key"
              >
                ↺
              </button>
              <code
                onClick={() => navigator.clipboard?.writeText(token)}
                className="text-xs font-mono text-amber-200 bg-amber-300/10 rounded px-2 py-1 break-all cursor-pointer hover:bg-amber-300/15 truncate"
                title="Click to copy"
              >
                {token}
              </code>
            </div>
          )}
          {state.kind === "error" && (
            <p className="text-sm text-amber-300">{state.message}</p>
          )}
        </div>
      </div>
    </div>
  );
}
