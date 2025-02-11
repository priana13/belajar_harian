<?php

namespace App\Filament\Resources\AngkatanResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KelasRelationManager extends RelationManager
{
    protected static string $relationship = 'kelas';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([              

                Forms\Components\TextInput::make('nama_kelas')->required()->maxLength(255),                    
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

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
