<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriKegiatanResource\Pages;
use App\Models\KategoriKegiatan;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class KategoriKegiatanResource extends Resource
{
    protected static ?string $model = KategoriKegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Konfigurasi';

    protected static ?string $navigationLabel  = 'Kategori Kegiatan';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(150),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->label('Tanggal Input'),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKategoriKegiatans::route('/'),
            // 'create' => Pages\CreateKategoriKegiatan::route('/create'),
            // 'edit' => Pages\EditKategoriKegiatan::route('/{record}/edit'),
        ];
    }
}
