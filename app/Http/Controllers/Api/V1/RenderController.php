<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\RenderResource;
use App\Jobs\RunRender;
use App\Models\IdempotencyRecord;
use App\Models\Render;
use App\Models\Template;
use App\Models\TemplateVersion;
use App\Models\Workspace;
use App\Services\Rendering\RenderEngine;
use App\Support\ProblemResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class RenderController extends Controller
{
    private const SUPPORTED_FORMATS_PHASE_3 = ['html'];

    public function index(Request $request): JsonResponse
    {
        $workspace = $this->workspace($request);

        $perPage = max(1, min(100, (int) $request->query('per_page', 25)));

        $query = Render::query()->where('workspace_id', $workspace->id)->orderByDesc('id');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $renders = $query->cursorPaginate($perPage);

        return new JsonResponse([
            'data' => RenderResource::collection($renders->items())->toArray($request),
            'next_cursor' => $renders->nextCursor()?->encode(),
        ]);
    }

    public function store(Request $request, RenderEngine $engine): JsonResponse
    {
        $workspace = $this->workspace($request);

        try {
            $data = $request->validate([
                'template_id' => ['required', 'string', 'size:26'],
                'version' => ['nullable', 'string', 'regex:/^v\d+$/'],
                'formats' => ['required', 'array', 'min:1'],
                'formats.*' => ['string'],
                'data' => ['required', 'array'],
                'signed_url_ttl' => ['nullable', 'integer', 'min:60', 'max:' . config('docgen.max_signed_url_ttl')],
            ]);
        } catch (ValidationException $e) {
            return ProblemResponse::validation('Invalid render payload.', $e->errors());
        }

        $template = Template::query()
            ->where('id', $data['template_id'])
            ->where('workspace_id', $workspace->id)
            ->whereNull('archived_at')
            ->first();

        if ($template === null) {
            return ProblemResponse::notFound('Template not found.');
        }

        $version = $data['version'] ?? null
            ? $template->findVersionByLabel($data['version'])
            : $template->latestVersion();

        if ($version === null) {
            return ProblemResponse::unprocessable(
                'Template has no frozen versions. Freeze a version with POST /v1/templates/{id}/versions before rendering.'
            );
        }

        // Format support is phase-dependent. Phase 3 = html only; later
        // phases register pdf + docx in the engine.
        foreach ($data['formats'] as $format) {
            if (! $engine->supports($format)) {
                return ProblemResponse::validation('Unsupported output format.', [
                    'formats' => ["Format `{$format}` is not supported. Supported: " . implode(', ', $this->supportedFormatList($engine))],
                ]);
            }
        }

        // Validate input data against the version's frozen field schema.
        $missing = $this->missingRequiredFields($version, $data['data']);

        if ($missing !== []) {
            return ProblemResponse::validation('Input data is missing required merge fields.', [
                'data' => array_map(fn (string $f) => "Missing field: {$f}", $missing),
            ]);
        }

        $dataPayload = json_encode($data['data'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $dataSize = strlen($dataPayload);

        if ($dataSize > config('docgen.input_data_max_bytes')) {
            return ProblemResponse::validation('Input data exceeds the maximum size.', [
                'data' => ['Hard cap is ' . config('docgen.input_data_max_bytes') . ' bytes.'],
            ]);
        }

        $dataHash = hash('sha256', $dataPayload);

        $idempotencyKey = $request->header('Idempotency-Key');
        $requestHash = $this->requestHash($version->id, $data['formats'], $dataHash, (int) ($data['signed_url_ttl'] ?? 0));

        if ($idempotencyKey !== null) {
            $existing = IdempotencyRecord::findForKey($workspace->id, $idempotencyKey);

            if ($existing !== null) {
                if ($existing->request_hash !== $requestHash) {
                    return ProblemResponse::conflict(
                        'Idempotency key matches a previous request with different parameters.'
                    );
                }

                $cached = Render::find($existing->resource_id);

                if ($cached !== null) {
                    return (new RenderResource($cached))->response()->setStatusCode(200);
                }
            }
        }

        $ttl = (int) ($data['signed_url_ttl'] ?? $workspace->default_signed_url_ttl_seconds ?? config('docgen.default_signed_url_ttl'));

        $render = DB::transaction(function () use ($workspace, $template, $version, $data, $request, $dataHash, $dataSize, $ttl, $idempotencyKey, $requestHash) {
            $render = Render::create([
                'workspace_id' => $workspace->id,
                'template_id' => $template->id,
                'template_version_id' => $version->id,
                'template_version_label' => $version->label,
                'status' => Render::STATUS_QUEUED,
                'formats_requested' => array_values(array_unique($data['formats'])),
                'input_data_hash' => $dataHash,
                'input_data_size_bytes' => $dataSize,
                'signed_url_ttl_seconds' => $ttl,
                'created_by_api_key_id' => optional($request->attributes->get('api_key'))->id,
                'queued_at' => now(),
            ]);

            if ($idempotencyKey !== null) {
                IdempotencyRecord::create([
                    'workspace_id' => $workspace->id,
                    'idempotency_key' => $idempotencyKey,
                    'request_hash' => $requestHash,
                    'resource_type' => 'render',
                    'resource_id' => $render->id,
                    'expires_at' => now()->addDay(),
                ]);
            }

            return $render;
        });

        $sync = filter_var($request->query('sync', 'false'), FILTER_VALIDATE_BOOL);
        $timeoutSeconds = (int) config('docgen.sync_render_timeout', 15);

        if ($sync) {
            // Run inline within the timeout; if it overshoots we still 202.
            $deadline = microtime(true) + $timeoutSeconds;

            dispatch_sync(new RunRender($render->id, $data['data']));
            $render->refresh();

            if ($render->isTerminal() && microtime(true) <= $deadline) {
                return (new RenderResource($render))->response()->setStatusCode(200);
            }
        } else {
            dispatch(new RunRender($render->id, $data['data']));
            $render->refresh();
        }

        return (new RenderResource($render))->response()->setStatusCode(202);
    }

    public function show(Request $request, string $renderId): JsonResponse
    {
        $render = $this->find($request, $renderId);

        if ($render === null) {
            return ProblemResponse::notFound('Render not found.');
        }

        return new JsonResponse((new RenderResource($render))->toArray($request));
    }

    public function destroy(Request $request, string $renderId): JsonResponse
    {
        $render = $this->find($request, $renderId);

        if ($render === null) {
            return ProblemResponse::notFound('Render not found.');
        }

        if ($render->isTerminal()) {
            return ProblemResponse::conflict('Render is already in a terminal state.');
        }

        $render->forceFill([
            'status' => Render::STATUS_CANCELLED,
            'completed_at' => now(),
        ])->save();

        return new JsonResponse(null, 204);
    }

    public function download(Request $request, string $renderId, string $format): Response
    {
        if (! $request->hasValidSignature()) {
            return ProblemResponse::unauthorized('Signed URL is missing or expired.');
        }

        $render = Render::find($renderId);

        if ($render === null || $render->status !== Render::STATUS_SUCCEEDED) {
            return ProblemResponse::notFound('Render output not available.');
        }

        $output = collect($render->outputs ?? [])->firstWhere('format', $format);

        if ($output === null) {
            return ProblemResponse::notFound('Output format not produced for this render.');
        }

        $disk = Storage::disk(config('filesystems.default'));

        if (! $disk->exists($output['path'])) {
            return ProblemResponse::notFound('Output file no longer exists.');
        }

        return $disk->response($output['path'], "render-{$render->id}.{$format}", [
            'Content-Type' => $this->mimeFor($format),
        ]);
    }

    private function find(Request $request, string $renderId): ?Render
    {
        $workspace = $this->workspace($request);

        return Render::query()
            ->where('id', $renderId)
            ->where('workspace_id', $workspace->id)
            ->first();
    }

    private function workspace(Request $request): Workspace
    {
        return $request->attributes->get('workspace');
    }

    private function supportedFormatList(RenderEngine $engine): array
    {
        $supported = [];

        foreach (['html', 'pdf', 'docx'] as $candidate) {
            if ($engine->supports($candidate)) {
                $supported[] = $candidate;
            }
        }

        return $supported;
    }

    private function missingRequiredFields(TemplateVersion $version, array $data): array
    {
        $schema = $version->fields_schema['fields'] ?? [];
        $missing = [];

        foreach ($schema as $field) {
            if (! array_key_exists($field['name'], $data)) {
                $missing[] = $field['name'];
            }
        }

        return $missing;
    }

    private function requestHash(string $versionId, array $formats, string $dataHash, int $ttl): string
    {
        $formats = array_values(array_unique($formats));
        sort($formats);

        return hash('sha256', $versionId . '|' . implode(',', $formats) . '|' . $dataHash . '|' . $ttl);
    }

    private function mimeFor(string $format): string
    {
        return match ($format) {
            'html' => 'text/html; charset=utf-8',
            'pdf' => 'application/pdf',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            default => 'application/octet-stream',
        };
    }
}
