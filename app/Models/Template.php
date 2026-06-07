<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'workspace_id',
        'name',
        'slug',
        'description',
        'engine',
        'body',
        'archived_at',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function versions(): HasMany
    {
        // ULIDs are lexicographically time-ordered, so `id ASC` is also creation order.
        // Avoids ambiguous ordering when multiple versions share the same `created_at` second.
        return $this->hasMany(TemplateVersion::class)->orderBy('id');
    }

    public function latestVersion(): ?TemplateVersion
    {
        return $this->hasMany(TemplateVersion::class)->orderByDesc('id')->first();
    }

    public function findVersionByLabel(string $label): ?TemplateVersion
    {
        return $this->versions()->where('label', $label)->first();
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }
}
