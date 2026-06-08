<?php

namespace App\Filament\Resources\Renders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RenderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workspace_id')
                    ->relationship('workspace', 'name')
                    ->required(),
                Select::make('template_id')
                    ->relationship('template', 'name')
                    ->required(),
                TextInput::make('template_version_id')
                    ->required(),
                TextInput::make('template_version_label')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('queued'),
                Textarea::make('formats_requested')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('outputs')
                    ->columnSpanFull(),
                TextInput::make('input_data_hash'),
                TextInput::make('input_data_size_bytes')
                    ->numeric(),
                TextInput::make('duration_ms')
                    ->numeric(),
                TextInput::make('error_code'),
                Textarea::make('error_message')
                    ->columnSpanFull(),
                Textarea::make('error_details')
                    ->columnSpanFull(),
                TextInput::make('signed_url_ttl_seconds')
                    ->required()
                    ->numeric(),
                TextInput::make('created_by_api_key_id'),
                DateTimePicker::make('queued_at'),
                DateTimePicker::make('started_at'),
                DateTimePicker::make('completed_at'),
            ]);
    }
}
