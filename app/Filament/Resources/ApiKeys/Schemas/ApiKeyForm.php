<?php

namespace App\Filament\Resources\ApiKeys\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ApiKeyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workspace_id')
                    ->relationship('workspace', 'name')
                    ->required(),
                TextInput::make('name'),
                TextInput::make('prefix')
                    ->required(),
                TextInput::make('last_four')
                    ->required(),
                TextInput::make('key_hash')
                    ->required(),
                DateTimePicker::make('last_used_at'),
                DateTimePicker::make('revoked_at'),
            ]);
    }
}
