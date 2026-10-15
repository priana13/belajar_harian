<?php

namespace App\Filament\Resources;

use App\Models\PremiumSubscription;
use App\Services\PremiumMembership;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PremiumSubscriptionResource extends AdminPremiumResource
{
    protected static ?string $model = PremiumSubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Langganan & Pembayaran';

    protected static ?string $modelLabel = 'Langganan Premium';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('id', 'desc')->columns([
            Tables\Columns\TextColumn::make('id')->label('Pesanan'),
            Tables\Columns\TextColumn::make('user.name')->label('Pengguna')->searchable(),
            Tables\Columns\TextColumn::make('plan_name')->label('Paket'),
            Tables\Columns\TextColumn::make('duration_months')->label('Bulan'),
            Tables\Columns\TextColumn::make('price')->label('Tagihan')->money('IDR'),
            Tables\Columns\TextColumn::make('payment_reference')->label('Referensi transfer')->wrap(),
            Tables\Columns\TextColumn::make('status')->badge()->formatStateUsing(fn ($state) => ['pending' => 'Menunggu verifikasi', 'paid' => 'Terbayar', 'cancelled' => 'Dibatalkan'][$state] ?? $state),
            Tables\Columns\TextColumn::make('starts_at')->label('Mulai')->dateTime(),
            Tables\Columns\TextColumn::make('ends_at')->label('Berakhir')->dateTime(),
        ])->filters([Tables\Filters\SelectFilter::make('status')->options(['pending' => 'Menunggu verifikasi', 'paid' => 'Terbayar', 'cancelled' => 'Dibatalkan'])])->actions([
            Tables\Actions\Action::make('approve')->label('Verifikasi & aktifkan')->icon('heroicon-o-check-circle')->requiresConfirmation()->modalDescription('Pastikan transfer sudah diterima sesuai nominal tagihan. Masa aktif ditambahkan setelah verifikasi.')->visible(fn ($record) => $record->status === 'pending' && filled($record->payment_reference))->action(fn ($record) => app(PremiumMembership::class)->approve($record, auth()->user())),
            Tables\Actions\Action::make('reject')->label('Tolak pesanan')->color('danger')->requiresConfirmation()->visible(fn ($record) => $record->status === 'pending')->action(fn ($record) => app(PremiumMembership::class)->reject($record, auth()->user())),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => PremiumSubscriptionResource\Pages\ManagePremiumSubscriptions::route('/')];
    }
}
