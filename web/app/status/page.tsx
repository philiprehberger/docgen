"use client";

import { useEffect, useState } from "react";

type HealthState =
  | { kind: "loading" }
  | { kind: "ok"; data: HealthBody }
  | { kind: "down"; message: string };

type HealthBody = {
  healthy: boolean;
  version: string;
  queue_depth: number;
  twig_version?: string;
  php_version?: string;
};

const HEALTH_URL = "https://api.docgen.philiprehberger.com/v1/healthz";

export default function Status() {
  const [state, setState] = useState<HealthState>({ kind: "loading" });
  const [checkedAt, setCheckedAt] = useState<Date | null>(null);

  useEffect(() => {
    let cancelled = false;

    async function check() {
      try {
        const response = await fetch(HEALTH_URL, { cache: "no-store" });

        if (!response.ok) {
          if (!cancelled) {
            setState({ kind: "down", message: `HTTP ${response.status}` });
            setCheckedAt(new Date());
          }
          return;
        }

        const body = (await response.json()) as HealthBody;

        if (!cancelled) {
          setState({ kind: "ok", data: body });
          setCheckedAt(new Date());
        }
      } catch (e) {
        if (!cancelled) {
          setState({
            kind: "down",
            message: e instanceof Error ? e.message : "Network error",
          });
          setCheckedAt(new Date());
        }
      }
    }

    check();
    const t = setInterval(check, 30_000);

    return () => {
      cancelled = true;
      clearInterval(t);
    };
  }, []);

  const overall =
    state.kind === "ok"
      ? "All systems operational"
      : state.kind === "down"
      ? "Degraded"
      : "Checking…";

  return (
    <div className="mx-auto max-w-3xl px-6 py-16">
      <p className="text-xs uppercase tracking-widest text-amber-300/80 mb-3">Status</p>
      <h1 className="text-3xl sm:text-4xl font-semibold tracking-tight mb-3">{overall}</h1>
      <p className="text-zinc-400 mb-12 max-w-xl">
        Live health of the demo.{" "}
        <code className="text-amber-200 bg-amber-300/10 px-1.5 py-0.5 rounded text-sm font-mono">
          /v1/healthz
        </code>{" "}
        is polled from your browser every 30 seconds.
      </p>

      <div className="space-y-3">
        <StatusRow
          service="API"
          url="api.docgen.philiprehberger.com/v1"
          ok={state.kind === "ok"}
          message={state.kind === "down" ? state.message : undefined}
        />
        <StatusRow service="Admin panel" url="api.docgen.philiprehberger.com/admin" ok={state.kind === "ok"} />
        <StatusRow service="Docs site" url="docgen.philiprehberger.com" ok={true} />
        <StatusRow service="Horizon worker" url="(internal)" ok={state.kind === "ok"} />
      </div>

      {state.kind === "ok" && (
        <div className="mt-8 rounded-md border border-zinc-800 bg-zinc-900/30 p-5 text-sm text-zinc-300">
          <div className="grid sm:grid-cols-3 gap-4">
            <Stat label="Version" value={state.data.version} />
            <Stat label="Queue depth" value={String(state.data.queue_depth)} />
            <Stat label="Twig" value={state.data.twig_version ?? "—"} />
          </div>
        </div>
      )}

      {checkedAt && (
        <p className="mt-6 text-xs text-zinc-500">
          Last checked: {checkedAt.toLocaleTimeString()}.
        </p>
      )}
    </div>
  );
}

function StatusRow({
  service,
  url,
  ok,
  message,
}: {
  service: string;
  url: string;
  ok: boolean;
  message?: string;
}) {
  return (
    <div className="flex items-center justify-between rounded-md border border-zinc-800 bg-zinc-900/40 px-5 py-3">
      <div>
        <p className="text-sm font-medium text-zinc-100">{service}</p>
        <p className="text-xs text-zinc-500 font-mono mt-0.5">{url}</p>
      </div>
      <span
        className={`inline-flex items-center gap-2 text-xs px-2.5 py-1 rounded-full ${
          ok
            ? "text-emerald-300 bg-emerald-300/10"
            : "text-red-300 bg-red-300/10"
        }`}
      >
        <span className={`size-1.5 rounded-full ${ok ? "bg-emerald-300" : "bg-red-300"}`} />
        {ok ? "Operational" : message ?? "Down"}
      </span>
    </div>
  );
}

function Stat({ label, value }: { label: string; value: string }) {
  return (
    <div>
      <p className="text-xs uppercase tracking-widest text-zinc-500 mb-1">{label}</p>
      <p className="text-zinc-200 font-mono text-sm">{value}</p>
    </div>
  );
}
