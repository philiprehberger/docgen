<?php

namespace App\Filament\Resources\Renders\Pages;

use App\Filament\Resources\Renders\RenderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRenders extends ListRecords
{
    protected static string $resource = RenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
