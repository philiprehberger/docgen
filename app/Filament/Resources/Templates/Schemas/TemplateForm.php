<?php

namespace App\Filament\Resources\Templates\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workspace_id')
                    ->relationship('workspace', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('description'),
                TextInput::make('engine')
                    ->required()
                    ->default('twig'),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('archived_at'),
            ]);
    }
}
