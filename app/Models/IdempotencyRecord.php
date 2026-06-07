<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdempotencyRecord extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'workspace_id',
        'idempotency_key',
        'request_hash',
        'resource_type',
        'resource_id',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public static function findForKey(string $workspaceId, string $key): ?self
    {
        return self::query()
            ->where('workspace_id', $workspaceId)
            ->where('idempotency_key', $key)
            ->where('expires_at', '>', now())
            ->first();
    }
}
