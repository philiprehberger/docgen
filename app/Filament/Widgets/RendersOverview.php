<?php

namespace App\Filament\Widgets;

use App\Models\ApiKey;
use App\Models\Render;
use App\Models\Template;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RendersOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $since = now()->subDay();

        $total = Render::query()->where('created_at', '>=', $since)->count();
        $succeeded = Render::query()->where('created_at', '>=', $since)
            ->where('status', Render::STATUS_SUCCEEDED)->count();
        $failed = Render::query()->where('created_at', '>=', $since)
            ->where('status', Render::STATUS_FAILED)->count();
        $queued = Render::query()
            ->whereIn('status', [Render::STATUS_QUEUED, Render::STATUS_RENDERING])
            ->count();

        $successRate = $total > 0 ? round(($succeeded / $total) * 100) : 100;

        $avgDuration = Render::query()
            ->where('status', Render::STATUS_SUCCEEDED)
            ->where('created_at', '>=', $since)
            ->avg('duration_ms');

        return [
            Stat::make('Renders, 24h', number_format($total))
                ->description($queued > 0 ? "{$queued} in-flight" : 'no jobs running')
                ->color($queued > 0 ? 'warning' : 'gray'),

            Stat::make('Success rate, 24h', "{$successRate}%")
                ->description($failed > 0 ? "{$failed} failed" : 'no failures')
                ->color($successRate >= 95 ? 'success' : ($successRate >= 80 ? 'warning' : 'danger')),

            Stat::make('Avg render duration',
                $avgDuration ? number_format($avgDuration).' ms' : '—')
                ->description('succeeded renders, 24h'),

            Stat::make('Templates', Template::query()->whereNull('archived_at')->count())
                ->description(ApiKey::query()->whereNull('revoked_at')->count().' API keys active'),
        ];
    }
}
