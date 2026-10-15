<?php

namespace App\Filament\Resources;

use App\Models\PremiumPlan;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class PremiumPlanResource extends AdminPremiumResource
{
    protected static ?string $model = PremiumPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Paket Premium';

    protected static ?string $modelLabel = 'Paket Premium';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->label('Nama paket')->required()->maxLength(255),
            TextInput::make('duration_months')->label('Durasi (bulan)')->integer()->minValue(1)->maxValue(120)->required(),
            TextInput::make('price')->label('Harga (Rp)')->integer()->minValue(0)->maxValue(999999999999)->required(),
            Textarea::make('payment_instructions')->label('Instruksi transfer')->helperText('Bank, nomor rekening, nama penerima, dan cara konfirmasi.')->required()->maxLength(5000)->columnSpanFull(),
            Toggle::make('is_active')->label('Tersedia untuk dibeli')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->label('Paket')->searchable(),
            Tables\Columns\TextColumn::make('duration_months')->label('Bulan'),
            Tables\Columns\TextColumn::make('price')->label('Harga')->money('IDR'),
            Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
        ])->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => PremiumPlanResource\Pages\ManagePremiumPlans::route('/')];
    }
}
