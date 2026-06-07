<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use App\Models\Workspace;
use App\Support\ProblemResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Public sandbox endpoint that mints a short-lived API key + ephemeral
 * workspace. Powers the docs site's "try it" console — visitors paste JSON
 * into the Scalar reference page and the page calls this endpoint behind
 * the scenes to get them a working key.
 *
 * Hard limits to keep this from being abused as a free document factory:
 *
 *   - One sandbox key per IP per 10 minutes (rate-limited via Cache)
 *   - Sandbox keys live for 30 minutes, then expire
 *   - Sandbox workspace is isolated from any "real" data
 *   - is_sandbox=true workspace gets per-workspace rate limits applied
 *     downstream (renders/min, renders/day) — see RenderController
 */
class SandboxController extends Controller
{
    private const KEY_TTL_MINUTES = 30;

    private const RATE_LIMIT_WINDOW_MINUTES = 10;

    public function mint(Request $request): JsonResponse
    {
        $ip = $request->ip() ?? 'unknown';
        $rateLimitKey = 'sandbox:mint:' . $ip;

        if (Cache::has($rateLimitKey)) {
            return ProblemResponse::make(429, 'Too many requests',
                'Sandbox keys are limited to one per ' . self::RATE_LIMIT_WINDOW_MINUTES . ' minutes per IP.');
        }

        Cache::put($rateLimitKey, true, now()->addMinutes(self::RATE_LIMIT_WINDOW_MINUTES));

        [$workspace, $key, $plaintext] = DB::transaction(function () {
            $workspace = Workspace::create([
                'name' => 'Sandbox · ' . now()->format('Y-m-d H:i'),
                'is_sandbox' => true,
                'default_signed_url_ttl_seconds' => 3600,
                'max_signed_url_ttl_seconds' => 3600,
            ]);

            [$key, $plaintext] = ApiKey::mint($workspace, 'Sandbox key', sandbox: true);

            return [$workspace, $key, $plaintext];
        });

        $expiresAt = now()->addMinutes(self::KEY_TTL_MINUTES);

        return new JsonResponse([
            'api_key' => $plaintext,                                    // shown ONCE
            'prefix' => $key->prefix,
            'last_four' => $key->last_four,
            'workspace_id' => $workspace->id,
            'expires_at' => $expiresAt->toIso8601String(),
            'sample_template_ids' => $this->seedSampleTemplates($workspace),
            'limits' => [
                'renders_per_minute' => 5,
                'renders_per_day' => 50,
                'key_lifetime_minutes' => self::KEY_TTL_MINUTES,
            ],
            'notice' => 'Sandbox keys are read-once and expire after ' . self::KEY_TTL_MINUTES
                . ' minutes. Do not use sandbox keys against production data.',
        ], 201);
    }

    /**
     * Seed the new sandbox workspace with the three reference templates so
     * the try-it console has something to render immediately.
     *
     * @return array<string, string>  Map of slug → template id.
     */
    private function seedSampleTemplates(Workspace $workspace): array
    {
        $templatesDir = base_path('sample-templates');

        $catalog = [
            'invoice' => $templatesDir . '/invoice.twig',
            'offer-letter' => $templatesDir . '/offer-letter.twig',
            'certificate' => $templatesDir . '/certificate.twig',
        ];

        $ids = [];
        $fieldDiscovery = new \App\Services\Twig\FieldDiscovery;

        foreach ($catalog as $slug => $path) {
            if (! file_exists($path)) {
                continue;
            }

            $body = (string) file_get_contents($path);

            $template = \App\Models\Template::create([
                'workspace_id' => $workspace->id,
                'name' => ucwords(str_replace('-', ' ', $slug)),
                'slug' => $slug,
                'body' => $body,
            ]);

            $schema = $fieldDiscovery->discover($body);

            $template->versions()->create([
                'label' => 'v1',
                'body_snapshot' => $body,
                'fields_schema' => $schema,
                'created_at' => now(),
            ]);

            $ids[$slug] = $template->id;
        }

        return $ids;
    }
}
