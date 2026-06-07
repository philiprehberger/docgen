<?php

namespace App\Filament\Resources\Renders;

use App\Filament\Resources\Renders\Pages\CreateRender;
use App\Filament\Resources\Renders\Pages\EditRender;
use App\Filament\Resources\Renders\Pages\ListRenders;
use App\Filament\Resources\Renders\Schemas\RenderForm;
use App\Filament\Resources\Renders\Tables\RendersTable;
use App\Models\Render;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RenderResource extends Resource
{
    protected static ?string $model = Render::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RenderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RendersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRenders::route('/'),
            'create' => CreateRender::route('/create'),
            'edit' => EditRender::route('/{record}/edit'),
        ];
    }
}
