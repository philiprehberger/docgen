<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // RFC-7807 + spec-conformant responses — no top-level `data` wrapper.
        // List endpoints construct their own `{data, next_cursor}` shape explicitly.
        JsonResource::withoutWrapping();
    }
}
