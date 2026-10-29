<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QueueResource\Pages;
use App\Models\QueueJob;
use Carbon\Carbon;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QueueResource extends Resource
{
    protected static ?string $model = QueueJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Antrian';

    protected static ?string $modelLabel = 'Antrian';

    protected static ?string $pluralModelLabel = 'Antrian';

    protected static ?int $navigationSort = 100;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('job_name')
                    ->label('Job')
                    ->searchable(false),
                Tables\Columns\TextColumn::make('queue')
                    ->badge(),
                Tables\Columns\TextColumn::make('attempts')
                    ->label('Percobaan'),
                Tables\Columns\TextColumn::make('available_at')
                    ->label('Dijalankan Pada')
                    ->formatStateUsing(fn ($state) => $state ? Carbon::createFromTimestamp($state)->format('d M Y H:i:s') : '-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Masuk Antrian')
                    ->formatStateUsing(fn ($state) => $state ? Carbon::createFromTimestamp($state)->format('d M Y H:i:s') : '-'),
            ])
            ->filters([
                SelectFilter::make('queue')
                    ->options(fn () => QueueJob::query()->distinct()->pluck('queue', 'queue')->toArray()),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQueueJobs::route('/'),
        ];
    }
}
