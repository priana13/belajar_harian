<?php

namespace App\Filament\Resources;

use App\Models\PremiumBook;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class PremiumBookResource extends AdminPremiumResource
{
    protected static ?string $model = PremiumBook::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationLabel = 'Buku Gratis';

    protected static ?string $modelLabel = 'Buku Gratis';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('materi_id')->label('Materi')->relationship('materi', 'nama_materi')->searchable()->required()->unique(ignoreRecord: true)->disabled(fn ($record) => $record !== null)->dehydrated(fn ($record) => $record === null)->helperText('Satu materi menyediakan satu buku. Materi tidak dapat diganti setelah buku dibuat.'),
            TextInput::make('title')->label('Judul buku')->required()->maxLength(255),
            Toggle::make('is_available')->label('Buku tersedia untuk diklaim')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title')->label('Judul')->searchable(),
            Tables\Columns\TextColumn::make('materi.nama_materi')->label('Materi'),
            Tables\Columns\IconColumn::make('is_available')->label('Tersedia')->boolean(),
        ])->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => PremiumBookResource\Pages\ManagePremiumBooks::route('/')];
    }
}
