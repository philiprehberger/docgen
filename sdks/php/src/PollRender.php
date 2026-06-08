<?php

namespace Docgen\Sdk;

use Docgen\Client\Api\RendersApi;
use Docgen\Client\Model\Render;

/**
 * Poll a Docgen render until it reaches a terminal state.
 *
 * Backs off exponentially (500ms → ~5s ceiling), respects a wall-clock
 * budget, throws on timeout.
 */
final class PollRender
{
    private function __construct() {}

    /**
     * @param  RendersApi  $api  Generated SDK Renders API.
     * @param  string  $renderId  The render to poll.
     * @param  array{
     *     max_wait_ms?: int,
     *     initial_interval_ms?: int,
     *     max_interval_ms?: int,
     *     backoff_factor?: float,
     * }  $options
     */
    public static function until(RendersApi $api, string $renderId, array $options = []): Render
    {
        $maxWaitMs = $options['max_wait_ms'] ?? 60000;
        $intervalMs = $options['initial_interval_ms'] ?? 500;
        $maxIntervalMs = $options['max_interval_ms'] ?? 5000;
        $factor = $options['backoff_factor'] ?? 1.6;

        $deadline = self::nowMs() + $maxWaitMs;

        while (true) {
            $render = $api->getRender($renderId);

            if (in_array($render->getStatus(), ['succeeded', 'failed', 'cancelled'], true)) {
                return $render;
            }

            if (self::nowMs() >= $deadline) {
                throw new PollRenderTimeout($renderId, $maxWaitMs);
            }

            usleep($intervalMs * 1000);
            $intervalMs = (int) min($maxIntervalMs, $intervalMs * $factor);
        }
    }

    private static function nowMs(): int
    {
        return (int) (microtime(true) * 1000);
    }
}
