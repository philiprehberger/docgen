<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateVersion extends Model
{
    use HasFactory, HasUlids;

    public $timestamps = false;

    protected $fillable = [
        'template_id',
        'label',
        'body_snapshot',
        'fields_schema',
        'created_by_api_key_id',
        'created_at',
    ];

    protected $casts = [
        'fields_schema' => 'array',
        'created_at' => 'datetime',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function createdByApiKey(): BelongsTo
    {
        return $this->belongsTo(ApiKey::class, 'created_by_api_key_id');
    }
}
