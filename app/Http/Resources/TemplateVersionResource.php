<?php

namespace App\Http\Resources;

use App\Models\TemplateVersion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin TemplateVersion
 */
class TemplateVersionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'template_id' => $this->template_id,
            'label' => $this->label,
            'body' => $this->body_snapshot,
            'fields_schema' => $this->fields_schema,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
