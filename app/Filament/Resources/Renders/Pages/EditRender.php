<?php

namespace App\Filament\Resources\Renders\Pages;

use App\Filament\Resources\Renders\RenderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRender extends EditRecord
{
    protected static string $resource = RenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
