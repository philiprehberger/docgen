<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Render extends Model
{
    use HasFactory, HasUlids;

    public const STATUS_QUEUED = 'queued';

    public const STATUS_RENDERING = 'rendering';

    public const STATUS_SUCCEEDED = 'succeeded';

    public const STATUS_FAILED = 'failed';

    public const STATUS_CANCELLED = 'cancelled';

    public const TERMINAL_STATUSES = [
        self::STATUS_SUCCEEDED,
        self::STATUS_FAILED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'workspace_id',
        'template_id',
        'template_version_id',
        'template_version_label',
        'status',
        'formats_requested',
        'outputs',
        'input_data_hash',
        'input_data_size_bytes',
        'duration_ms',
        'error_code',
        'error_message',
        'error_details',
        'signed_url_ttl_seconds',
        'created_by_api_key_id',
        'queued_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'formats_requested' => 'array',
        'outputs' => 'array',
        'error_details' => 'array',
        'queued_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(TemplateVersion::class, 'template_version_id');
    }

    public function isTerminal(): bool
    {
        return in_array($this->status, self::TERMINAL_STATUSES, true);
    }
}
