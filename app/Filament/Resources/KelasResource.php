<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kelas')->required()->maxLength(255),
                Forms\Components\Select::make('angkatan_id')->relationship('angkatan', 'tanggal_mulai')
                    ->required(),
                Forms\Components\Select::make('admin1')->relationship('admin_satu', 'name')->searchable()->preload(),
                Forms\Components\Select::make('admin2')->relationship('admin_dua', 'name')->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kelas')->searchable(),
                Tables\Columns\TextColumn::make('angkatan.tanggal_mulai')->date(),
                Tables\Columns\TextColumn::make('admin_satu.name'),
                Tables\Columns\TextColumn::make('admin_dua.name'),                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('angkatan')->relationship('angkatan', 'tanggal_mulai')
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
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }    
}
