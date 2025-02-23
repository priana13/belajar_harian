<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GelombangResource\Pages;
use App\Filament\Resources\GelombangResource\RelationManagers;
use App\Models\Gelombang;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GelombangResource extends Resource
{
    protected static ?string $model = Gelombang::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('gel')
                    ->required()
                    ->maxLength(191),
                Forms\Components\DatePicker::make('tanggal_mulai')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gel'),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date(),
                    Tables\Columns\TextColumn::make('peserta_count')->counts('peserta')->label("Peserta"),
                Tables\Columns\TextColumn::make('angkatan_count')->counts('angkatan')->label("Kelas/Angkatan"),
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
            'index' => Pages\ListGelombangs::route('/'),
            // 'create' => Pages\CreateGelombang::route('/create'),
            // 'edit' => Pages\EditGelombang::route('/{record}/edit'),
        ];
    }    
}
