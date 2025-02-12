<?php

namespace App\Filament\Resources\AngkatanResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Livewire\Livewire;
use App\Models\Belajar;
use App\Models\MateriDetail;
use Closure;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class JadwalBelajarRelationManager extends RelationManager
{
    protected static string $relationship = 'jadwal_belajar';

    protected static ?string $recordTitleAttribute = 'tanggal';

    public static function form(Form $form): Form
    {      

        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')->required(),
                Forms\Components\Select::make('materi_detail_id')->options(function(RelationManager $livewire){
                    $materi_id = $livewire->getOwnerRecord()->materi_id;
                    //   return ["data" => $livewire->getOwnerRecord() ];

                    $materiDetail = MateriDetail::where('materi_id', $materi_id)->pluck('pertemuan' , 'id');

                    return $materiDetail;

                })->label("Pertemuan")->searchable(),
                // Forms\Components\Select::make('user_id')->relationship('user', 'na--me'),
                Forms\Components\Select::make('status')->options(Belajar::getOptions())->default("Berikutnya"),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date(),
                Tables\Columns\TextColumn::make('materi_detail.pertemuan')->label("Materi Pertemuan"),
                // Tables\Columns\TextColumn::make('user.name')->label("Peserta")->searchable(),
                Tables\Columns\TextColumn::make('code'),   
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
                Tables\Actions\Action::make("link")->url(function($record){

                    return url( route('link_materi' , $record->code) );

                })->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
