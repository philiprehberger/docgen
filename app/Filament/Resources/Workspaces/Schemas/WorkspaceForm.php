<?php

namespace App\Filament\Resources\Workspaces\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WorkspaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('default_signed_url_ttl_seconds')
                    ->required()
                    ->numeric()
                    ->default(3600),
                TextInput::make('max_signed_url_ttl_seconds')
                    ->required()
                    ->numeric()
                    ->default(86400),
                Toggle::make('is_sandbox')
                    ->required(),
            ]);
    }
}
