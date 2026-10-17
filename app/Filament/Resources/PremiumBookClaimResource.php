<?php

namespace App\Filament\Resources;

use App\Models\PremiumBookClaim;
use App\Services\PremiumMembership;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PremiumBookClaimResource extends AdminPremiumResource
{
    protected static ?string $model = PremiumBookClaim::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Klaim & Pengiriman Buku';

    protected static ?string $modelLabel = 'Klaim Buku';

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
            Tables\Columns\TextColumn::make('id')->label('Klaim'),
            Tables\Columns\TextColumn::make('user.name')->label('Pengguna')->searchable(),
            Tables\Columns\TextColumn::make('items.book_title')->label('Buku')->listWithLineBreaks(),
            Tables\Columns\TextColumn::make('recipient_name')->label('Penerima'),
            Tables\Columns\TextColumn::make('phone')->label('HP'),
            Tables\Columns\TextColumn::make('address')->label('Alamat')->wrap(),
            Tables\Columns\TextColumn::make('city')->label('Kota'),
            Tables\Columns\TextColumn::make('province')->label('Provinsi'),
            Tables\Columns\TextColumn::make('postal_code')->label('Kode pos'),
            Tables\Columns\TextColumn::make('status')->badge()->formatStateUsing(fn ($state) => ['requested' => 'Menunggu ongkir', 'awaiting_payment' => 'Menunggu pembayaran', 'payment_review' => 'Verifikasi ongkir', 'shipped' => 'Dikirim'][$state] ?? $state),
            Tables\Columns\TextColumn::make('shipping_fee')->label('Ongkir')->money('IDR'),
            Tables\Columns\TextColumn::make('shipping_payment_reference')->label('Transfer ongkir')->wrap(),
            Tables\Columns\TextColumn::make('courier')->label('Kurir'),
            Tables\Columns\TextColumn::make('tracking_number')->label('Resi'),
        ])->actions([
            Tables\Actions\Action::make('quote')->label('Tentukan ongkir')->visible(fn ($record) => $record->status === 'requested')->form([
                TextInput::make('fee')->label('Ongkir (Rp)')->integer()->minValue(1)->maxValue(999999999)->required(),
                Textarea::make('instructions')->label('Instruksi pembayaran ongkir')->required()->maxLength(5000),
            ])->action(fn ($record, array $data) => app(PremiumMembership::class)->quoteShipping($record, auth()->user(), $data['fee'], $data['instructions'])),
            Tables\Actions\Action::make('ship')->label('Verifikasi ongkir & kirim')->requiresConfirmation()->modalDescription('Pastikan ongkir sudah diterima sebelum mencatat pengiriman.')->visible(fn ($record) => $record->status === 'payment_review')->form([
                TextInput::make('courier')->label('Kurir')->required()->maxLength(255),
                TextInput::make('tracking')->label('Nomor resi')->required()->maxLength(255),
            ])->action(fn ($record, array $data) => app(PremiumMembership::class)->ship($record, auth()->user(), $data['courier'], $data['tracking'])),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => PremiumBookClaimResource\Pages\ManagePremiumBookClaims::route('/')];
    }
}
