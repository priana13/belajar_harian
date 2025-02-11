<?php

namespace App\Filament\Resources\UjianResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SoalUjianRelationManager extends RelationManager
{
    protected static string $relationship = 'soal_ujian';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Jawaban';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('soal_id')->searchable()->label('Soal'),
                // Tables\Columns\TextColumn::make('ujian.nama_ujian')->label('Ujian'),
                Tables\Columns\TextColumn::make('user.name')->label('User'),
                Tables\Columns\TextColumn::make('jawaban'),
                Tables\Columns\IconColumn::make('istrue')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Waktu Kirim')
                    ->dateTime()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
