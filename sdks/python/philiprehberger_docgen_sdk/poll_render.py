"""
Poll a Docgen render until it reaches a terminal state.

Backs off exponentially (500ms → ~5s ceiling), respects a wall-clock
budget, raises PollRenderTimeout if the deadline elapses.
"""

from __future__ import annotations

import time
from typing import Optional

from philiprehberger_docgen.api.renders_api import RendersApi
from philiprehberger_docgen.models.render import Render


_TERMINAL_STATUSES = ("succeeded", "failed", "cancelled")


def poll_render(
    api: RendersApi,
    render_id: str,
    *,
    max_wait_ms: int = 60_000,
    initial_interval_ms: int = 500,
    max_interval_ms: int = 5_000,
    backoff_factor: float = 1.6,
) -> Render:
    """Poll until terminal. Raises PollRenderTimeout on overall timeout."""

    deadline = time.monotonic() + (max_wait_ms / 1000)
    interval_ms = initial_interval_ms

    while True:
        render = api.get_render(render_id=render_id)

        if render.status in _TERMINAL_STATUSES:
            return render

        if time.monotonic() >= deadline:
            raise PollRenderTimeout(render_id, max_wait_ms)

        time.sleep(interval_ms / 1000)
        interval_ms = min(max_interval_ms, int(interval_ms * backoff_factor))


class PollRenderTimeout(TimeoutError):
    """The render didn't reach a terminal state within max_wait_ms."""

    def __init__(self, render_id: str, max_wait_ms: int) -> None:
        super().__init__(f"poll_render({render_id}) timed out after {max_wait_ms}ms")
        self.render_id = render_id
        self.max_wait_ms = max_wait_ms
