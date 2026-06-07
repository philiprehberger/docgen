import { RendersApi } from '../generated/src/apis/RendersApi';
import type { Render } from '../generated/src/models/Render';

const TERMINAL_STATUSES = ['succeeded', 'failed', 'cancelled'] as const;

export interface PollRenderOptions {
  /** Total wall-clock time to wait before giving up. Default 60s. */
  maxWaitMs?: number;
  /** Initial interval. Subsequent polls back off until `maxIntervalMs`. Default 500ms. */
  initialIntervalMs?: number;
  /** Hard ceiling on the polling interval. Default 5s. */
  maxIntervalMs?: number;
  /** Multiplicative backoff factor between polls. Default 1.6. */
  backoffFactor?: number;
  /** Abort the poll loop early. */
  signal?: AbortSignal;
}

/**
 * Poll a Docgen render until it reaches a terminal state.
 *
 * The generated SDK exposes `getRender`, but spamming it every 50ms is
 * wasteful and gets you rate-limited. This helper:
 *
 *   - backs off exponentially (500ms → 800ms → 1.28s → … → 5s cap)
 *   - respects an overall `maxWaitMs` budget (default 60s)
 *   - cooperates with an `AbortSignal` so you can wire it to UI cancel
 *
 * Throws `PollRenderTimeout` if `maxWaitMs` elapses before terminal.
 * Throws `PollRenderAborted` if the signal fires.
 */
export async function pollRender(
  api: RendersApi,
  renderId: string,
  options: PollRenderOptions = {},
): Promise<Render> {
  const {
    maxWaitMs = 60_000,
    initialIntervalMs = 500,
    maxIntervalMs = 5_000,
    backoffFactor = 1.6,
    signal,
  } = options;

  const started = Date.now();
  let interval = initialIntervalMs;

  // eslint-disable-next-line no-constant-condition
  while (true) {
    if (signal?.aborted) {
      throw new PollRenderAborted(renderId);
    }

    const render = await api.getRender({ renderId });

    if (isTerminal(render.status)) {
      return render;
    }

    if (Date.now() - started >= maxWaitMs) {
      throw new PollRenderTimeout(renderId, maxWaitMs);
    }

    await sleep(interval, signal);
    interval = Math.min(maxIntervalMs, Math.round(interval * backoffFactor));
  }
}

function isTerminal(status: string): boolean {
  return (TERMINAL_STATUSES as readonly string[]).includes(status);
}

function sleep(ms: number, signal?: AbortSignal): Promise<void> {
  return new Promise((resolve, reject) => {
    const t = setTimeout(() => {
      signal?.removeEventListener('abort', onAbort);
      resolve();
    }, ms);

    const onAbort = () => {
      clearTimeout(t);
      reject(new DOMException('Aborted', 'AbortError'));
    };

    signal?.addEventListener('abort', onAbort, { once: true });
  });
}

export class PollRenderTimeout extends Error {
  constructor(public readonly renderId: string, public readonly maxWaitMs: number) {
    super(`pollRender(${renderId}) timed out after ${maxWaitMs}ms`);
    this.name = 'PollRenderTimeout';
  }
}

export class PollRenderAborted extends Error {
  constructor(public readonly renderId: string) {
    super(`pollRender(${renderId}) aborted`);
    this.name = 'PollRenderAborted';
  }
}
