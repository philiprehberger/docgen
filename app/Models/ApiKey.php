<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use HasFactory, HasUlids;

    public const PREFIX_LIVE = 'docgen_live';

    public const PREFIX_TEST = 'docgen_test';

    protected $fillable = [
        'workspace_id',
        'name',
        'prefix',
        'last_four',
        'key_hash',
        'last_used_at',
        'revoked_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    protected $hidden = ['key_hash'];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Mint a new API key. Returns [ApiKey, plaintext]. Plaintext is shown
     * once and never persisted; we hash for storage.
     *
     * @return array{0: self, 1: string}
     */
    public static function mint(Workspace $workspace, string $name = '', bool $sandbox = false): array
    {
        $prefix = $sandbox ? self::PREFIX_TEST : self::PREFIX_LIVE;
        $secret = Str::random(40);
        $plaintext = "{$prefix}_{$secret}";

        $key = self::create([
            'workspace_id' => $workspace->id,
            'name' => $name,
            'prefix' => $prefix,
            'last_four' => substr($secret, -4),
            'key_hash' => hash('sha256', $plaintext),
        ]);

        return [$key, $plaintext];
    }

    public static function findByPlaintext(string $plaintext): ?self
    {
        return self::query()
            ->whereNull('revoked_at')
            ->where('key_hash', hash('sha256', $plaintext))
            ->first();
    }

    public function isSandbox(): bool
    {
        return $this->prefix === self::PREFIX_TEST;
    }

    public function touchLastUsed(): void
    {
        $this->forceFill(['last_used_at' => now()])->saveQuietly();
    }
}
