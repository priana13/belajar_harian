<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisUjianResource\Pages;
use App\Models\JenisUjian;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
class JenisUjianResource extends Resource
{
    protected static ?string $model = JenisUjian::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Konfigurasi';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel  = 'Jenis Ujian';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('keterangan')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('keterangan'),               
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
            'index' => Pages\ListJenisUjians::route('/'),
            'create' => Pages\CreateJenisUjian::route('/create'),
            'edit' => Pages\EditJenisUjian::route('/{record}/edit'),
        ];
    }    
}
