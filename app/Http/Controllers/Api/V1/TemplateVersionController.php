<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TemplateVersionResource;
use App\Models\Template;
use App\Models\TemplateVersion;
use App\Models\Workspace;
use App\Services\Twig\FieldDiscovery;
use App\Support\ProblemResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateVersionController extends Controller
{
    public function index(Request $request, string $templateId): JsonResponse
    {
        $template = $this->find($request, $templateId);

        if ($template === null) {
            return ProblemResponse::notFound('Template not found.');
        }

        return new JsonResponse([
            'data' => TemplateVersionResource::collection($template->versions()->get())->toArray($request),
        ]);
    }

    public function store(Request $request, string $templateId): JsonResponse
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

        $version = DB::transaction(function () use ($template, $schema, $request) {
            $next = $this->nextLabel($template);

            return TemplateVersion::create([
                'template_id' => $template->id,
                'label' => $next,
                'body_snapshot' => $template->body,
                'fields_schema' => $schema,
                'created_by_api_key_id' => optional($request->attributes->get('api_key'))->id,
                'created_at' => now(),
            ]);
        });

        return (new TemplateVersionResource($version))->response()->setStatusCode(201);
    }

    public function show(Request $request, string $templateId, string $label): JsonResponse
    {
        $template = $this->find($request, $templateId);

        if ($template === null) {
            return ProblemResponse::notFound('Template not found.');
        }

        $version = $template->findVersionByLabel($label);

        if ($version === null) {
            return ProblemResponse::notFound('Version not found.');
        }

        return new JsonResponse((new TemplateVersionResource($version))->toArray($request));
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

    private function nextLabel(Template $template): string
    {
        $latest = $template->latestVersion();

        if ($latest === null) {
            return 'v1';
        }

        $n = (int) substr($latest->label, 1);

        return 'v'.($n + 1);
    }
}
