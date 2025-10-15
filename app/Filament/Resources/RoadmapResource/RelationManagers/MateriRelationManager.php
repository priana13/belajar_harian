<?php

namespace App\Filament\Resources\RoadmapResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MateriRelationManager extends RelationManager
{
    protected static string $relationship = 'materi';

    protected static ?string $recordTitleAttribute = 'nama_materi'; // Ubah ke 'judul' agar lebih bermakna

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_materi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->limit(50)
                    ->searchable(),              
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()->label("Tambah")->preloadRecordSelect(), // Untuk attach existing materi
            ])
            ->actions([
                Tables\Actions\DetachAction::make()->label("Hapus"), // Untuk many-to-many relationship
               
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()->label("Hapus"),
            ]);
    }    
}