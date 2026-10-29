<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FailedJobResource\Pages;
use App\Models\FailedJob;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Queue;

class FailedJobResource extends Resource
{
    protected static ?string $model = FailedJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Antrian Gagal';

    protected static ?string $modelLabel = 'Antrian Gagal';

    protected static ?string $pluralModelLabel = 'Antrian Gagal';

    protected static ?int $navigationSort = 101;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('job_name')
                    ->label('Job'),
                Tables\Columns\TextColumn::make('queue')
                    ->badge(),
                Tables\Columns\TextColumn::make('exception')
                    ->label('Error')
                    ->limit(80)
                    ->tooltip(fn (FailedJob $record) => $record->exception),
                Tables\Columns\TextColumn::make('failed_at')
                    ->label('Gagal Pada')
                    ->dateTime('d M Y H:i:s'),
            ])
            ->filters([
                SelectFilter::make('queue')
                    ->options(fn () => FailedJob::query()->distinct()->pluck('queue', 'queue')->toArray()),
            ])
            ->actions([
                Tables\Actions\Action::make('lihat_error')
                    ->label('Lihat Error')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading('Detail Error')
                    ->modalContent(fn (FailedJob $record) => view('filament.queue.failed-job-exception-modal', [
                        'jobName' => $record->job_name,
                        'exception' => $record->exception,
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
                Tables\Actions\Action::make('retry')
                    ->label('Coba Lagi')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Job ini akan dikirim ulang ke antrian untuk dijalankan lagi.')
                    ->action(function (FailedJob $record) {
                        $payload = json_decode($record->payload, true);

                        if (isset($payload['attempts'])) {
                            $payload['attempts'] = 0;
                        }

                        Queue::connection($record->connection)->pushRaw(
                            json_encode($payload),
                            $record->queue
                        );

                        $record->delete();

                        Notification::make()
                            ->title('Job dikirim ulang ke antrian')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
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
            'index' => Pages\ListFailedJobs::route('/'),
        ];
    }
}
