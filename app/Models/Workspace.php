<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workspace extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'default_signed_url_ttl_seconds',
        'max_signed_url_ttl_seconds',
        'is_sandbox',
    ];

    protected $casts = [
        'is_sandbox' => 'bool',
        'default_signed_url_ttl_seconds' => 'int',
        'max_signed_url_ttl_seconds' => 'int',
    ];

    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }

    public function templates(): HasMany
    {
        return $this->hasMany(Template::class);
    }
}
