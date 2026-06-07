<?php

namespace App\Http\Resources;

use App\Models\Render;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

/**
 * @mixin Render
 */
class RenderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'template_id' => $this->template_id,
            'template_version_label' => $this->template_version_label,
            'formats_requested' => $this->formats_requested,
            'outputs' => $this->signedOutputs(),
            'duration_ms' => $this->duration_ms,
            'input_data_hash' => $this->input_data_hash,
            'input_data_size_bytes' => $this->input_data_size_bytes,
            'error' => $this->error_code === null ? null : [
                'code' => $this->error_code,
                'message' => $this->error_message,
                'details' => $this->error_details,
            ],
            'poll_url' => route('v1.renders.show', ['renderId' => $this->id]),
            'created_at' => $this->created_at?->toIso8601String(),
            'completed_at' => $this->completed_at?->toIso8601String(),
        ];
    }

    /**
     * Annotate the stored outputs with signed download URLs + expiry.
     */
    private function signedOutputs(): array
    {
        if ($this->outputs === null) {
            return [];
        }

        $ttl = $this->signed_url_ttl_seconds ?? config('docgen.default_signed_url_ttl');
        $expiresAt = now()->addSeconds($ttl);

        return array_map(function (array $output) use ($expiresAt) {
            return [
                'format' => $output['format'],
                'url' => URL::temporarySignedRoute(
                    'v1.renders.download',
                    $expiresAt,
                    ['renderId' => $this->id, 'format' => $output['format']],
                ),
                'expires_at' => $expiresAt->toIso8601String(),
                'bytes' => $output['bytes'],
                'sha256' => $output['sha256'],
            ];
        }, $this->outputs);
    }
}
