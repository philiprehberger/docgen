<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TemplateResource;
use App\Models\Template;
use App\Models\Workspace;
use App\Services\Twig\FieldDiscovery;
use App\Support\ProblemResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TemplateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $workspace = $this->workspace($request);

        $perPage = (int) $request->query('per_page', 25);
        $perPage = max(1, min(100, $perPage));

        $templates = Template::query()
            ->where('workspace_id', $workspace->id)
            ->whereNull('archived_at')
            ->orderByDesc('created_at')
            ->cursorPaginate($perPage);

        return new JsonResponse([
            'data' => TemplateResource::collection($templates->items())->toArray($request),
            'next_cursor' => $templates->nextCursor()?->encode(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $workspace = $this->workspace($request);

        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:120'],
                'slug' => ['nullable', 'string', 'max:80', 'regex:/^[a-z0-9-]+$/i'],
                'description' => ['nullable', 'string', 'max:500'],
                'engine' => ['nullable', 'in:twig'],
                'body' => ['required', 'string', 'max:' . config('docgen.template_body_max_bytes')],
            ]);
        } catch (ValidationException $e) {
            return ProblemResponse::validation('Invalid template payload.', $e->errors());
        }

        $slug = $data['slug'] ?? Str::slug($data['name']);

        if (Template::where('workspace_id', $workspace->id)->where('slug', $slug)->exists()) {
            return ProblemResponse::validation('A template with this slug already exists in this workspace.', [
                'slug' => ['Slug must be unique within the workspace.'],
            ]);
        }

        if (! $this->bodyParses($data['body'])) {
            return ProblemResponse::unprocessable('Template body could not be parsed as Twig.');
        }

        $template = Template::create([
            'workspace_id' => $workspace->id,
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'engine' => $data['engine'] ?? 'twig',
            'body' => $data['body'],
        ]);

        return (new TemplateResource($template))->response()->setStatusCode(201);
    }

    public function show(Request $request, string $templateId): JsonResponse
    {
        $template = $this->find($request, $templateId);

        if ($template === null) {
            return ProblemResponse::notFound('Template not found.');
        }

        return new JsonResponse((new TemplateResource($template))->toArray($request));
    }

    public function update(Request $request, string $templateId): JsonResponse
    {
        $template = $this->find($request, $templateId);

        if ($template === null) {
            return ProblemResponse::notFound('Template not found.');
        }

        try {
            $data = $request->validate([
                'name' => ['sometimes', 'string', 'max:120'],
                'slug' => ['sometimes', 'string', 'max:80', 'regex:/^[a-z0-9-]+$/i'],
                'description' => ['sometimes', 'nullable', 'string', 'max:500'],
                'body' => ['sometimes', 'string', 'max:' . config('docgen.template_body_max_bytes')],
            ]);
        } catch (ValidationException $e) {
            return ProblemResponse::validation('Invalid template payload.', $e->errors());
        }

        if (isset($data['slug']) && $data['slug'] !== $template->slug) {
            if (Template::where('workspace_id', $template->workspace_id)
                ->where('slug', $data['slug'])
                ->where('id', '!=', $template->id)
                ->exists()) {
                return ProblemResponse::validation('A template with this slug already exists in this workspace.', [
                    'slug' => ['Slug must be unique within the workspace.'],
                ]);
            }
        }

        if (isset($data['body']) && ! $this->bodyParses($data['body'])) {
            return ProblemResponse::unprocessable('Template body could not be parsed as Twig.');
        }

        $template->fill($data)->save();

        return new JsonResponse((new TemplateResource($template))->toArray($request));
    }

    public function destroy(Request $request, string $templateId): JsonResponse
    {
        $template = $this->find($request, $templateId);

        if ($template === null) {
            return ProblemResponse::notFound('Template not found.');
        }

        $template->forceFill(['archived_at' => now()])->save();

        return new JsonResponse(null, 204);
    }

    public function fields(Request $request, string $templateId): JsonResponse
    {
        $template = $this->find($request, $templateId);

        if ($template === null) {
            return ProblemResponse::notFound('Template not found.');
        }

        try {
            $schema = (new FieldDiscovery)->discover($template->body);
        } catch (\Throwable $e) {
            return ProblemResponse::unprocessable("Template body could not be parsed: {$e->getMessage()}");
        }

        return new JsonResponse($schema);
    }

    private function find(Request $request, string $templateId): ?Template
    {
        $workspace = $this->workspace($request);

        return Template::query()
            ->where('id', $templateId)
            ->where('workspace_id', $workspace->id)
            ->whereNull('archived_at')
            ->first();
    }

    private function workspace(Request $request): Workspace
    {
        return $request->attributes->get('workspace');
    }

    private function bodyParses(string $body): bool
    {
        try {
            (new FieldDiscovery)->discover($body);

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
