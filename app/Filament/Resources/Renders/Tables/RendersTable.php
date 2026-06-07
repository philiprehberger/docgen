<?php

namespace App\Filament\Resources\Renders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RendersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('workspace.name')
                    ->searchable(),
                TextColumn::make('template.name')
                    ->searchable(),
                TextColumn::make('template_version_id')
                    ->searchable(),
                TextColumn::make('template_version_label')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('input_data_hash')
                    ->searchable(),
                TextColumn::make('input_data_size_bytes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('duration_ms')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('error_code')
                    ->searchable(),
                TextColumn::make('signed_url_ttl_seconds')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_by_api_key_id')
                    ->searchable(),
                TextColumn::make('queued_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('started_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
