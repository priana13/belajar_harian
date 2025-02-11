<?php

namespace App\Filament\Resources\AngkatanResource\RelationManagers;

use App\Models\Belajar;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JadwalBelajarRelationManager extends RelationManager
{
    protected static string $relationship = 'jadwal_belajar';

    protected static ?string $recordTitleAttribute = 'tanggal';

    public static function form(Form $form): Form
    {      

        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')->required(),
                Forms\Components\Select::make('materi_detail_id')->relationship('materi_detail','pertemuan'),
                Forms\Components\Select::make('user_id')->relationship('user', 'name'),
                Forms\Components\Select::make('status')->options(Belajar::getOptions()),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date(),
                Tables\Columns\TextColumn::make('materi_detail.pertemuan')->label("Materi Pertemuan"),
                Tables\Columns\TextColumn::make('user.name')->label("Peserta")->searchable(),
                // Tables\Columns\TextColumn::make('menit_terakhir'),   
                Tables\Columns\TextColumn::make('status'), 
            ])
            ->filters([
                SelectFilter::make('user')->relationship('user', 'name', function($query){

                    return $query->whereHas('jadwal_belajar');

                })->searchable()->label("Peserta")
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
