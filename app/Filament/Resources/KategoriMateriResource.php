<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriMateriResource\Pages;
use App\Models\KategoriMateri;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
class KategoriMateriResource extends Resource
{
    protected static ?string $model = KategoriMateri::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Konfigurasi';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kategori')
                    ->required()
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kategori'),
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
            'index' => Pages\ListKategoriMateris::route('/'),
            'create' => Pages\CreateKategoriMateri::route('/create'),
            'edit' => Pages\EditKategoriMateri::route('/{record}/edit'),
        ];
    }    
}
