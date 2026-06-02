<?php

namespace App\Filament\Resources\JadwalUjianResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JadwalUjianRelationManager extends RelationManager
{
    protected static string $relationship = 'jadwal_ujian';

    protected static ?string $recordTitleAttribute = 'urutan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('urutan')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('urutan')->label("Hari/Pekan"),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date("D, d M Y"),
                Tables\Columns\TextColumn::make('soal_ujian_count')->counts('soal_ujian')->label("Jumlah Soal"),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('Jadwal Ujian')->color('success')->url(function(){
                    return route('filament.resources.jadwal-ujians.index');
                })->openUrlInNewTab(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make("Ubah")->url(function($record){

                    return route('filament.resources.jadwal-ujians.edit', $record->id);
                })->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
