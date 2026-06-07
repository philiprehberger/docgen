<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Queue;

class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'healthy' => true,
            'version' => (string) config('app.docgen_version', '0.5.0'),
            'queue_depth' => $this->queueDepth(),
            'twig_version' => \Twig\Environment::VERSION,
            'php_version' => PHP_VERSION,
        ]);
    }

    private function queueDepth(): int
    {
        try {
            return Queue::size('default');
        } catch (\Throwable) {
            return 0;
        }
    }
}
