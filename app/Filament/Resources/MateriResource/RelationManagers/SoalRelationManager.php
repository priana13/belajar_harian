<?php

namespace App\Filament\Resources\MateriResource\RelationManagers;

use App\Models\JenisUjian;
use App\Models\MateriDetail;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ReplicateAction;
use Livewire\Livewire;

class SoalRelationManager extends RelationManager
{
    protected static string $relationship = 'soal';

    protected static ?string $recordTitleAttribute = null;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jenis_ujian_id')->relationship('jenis_ujian', 'nama')->reactive()->required()->columnSpan(2),                
                Forms\Components\Select::make('urutan')->options([
                    "1" => "Pekan ke-1",
                    "2" => "Pekan ke-2",
                    "3" => "Pekan ke-3",
                    "4" => "Pekan ke-4",
                    "5" => "Pekan ke-5",
                    "6" => "Pekan ke-6",
        
                ])->label(function(callable $get , Closure $set){

                    if($get('jenis_ujian_id')){
                        $jenis = JenisUjian::find($get('jenis_ujian_id'));

                        if($jenis->nama == "Harian"){

                            $label = "Hari Ke";
                        }elseif($jenis->nama == "Pekanan"){

                            $label = "Pekan Ke";
                        }else{

                            $label = "Urutan Ke";
                        }

                        return $label;
                    }

                    return "Pekan/Hari";

                })
                ->visible(function(callable $get){
                    
                    if($get('jenis_ujian_id')){

                        $jenis = JenisUjian::find($get('jenis_ujian_id'));

                        if($jenis->nama == 'Pekanan'){
                            return true;
                        }else{
                            return false;
                        }

                    }
                    
                    
                   
                })->columnSpan(2),    // urutan hari dan pekan  
                
                Forms\Components\Select::make('materi_detail_id')->relationship('pertemuan', 'pertemuan' , function($query , RelationManager $livewire){

                    $materi = $livewire->ownerRecord;

                    return $query->where('materi_id' , $materi->id);
                })
                ->visible(function(callable $get){
                    
                    if($get('jenis_ujian_id')){

                        $jenis = JenisUjian::find($get('jenis_ujian_id'));

                        if($jenis->nama == 'Harian'){
                            return true;
                        }else{
                            return false;
                        }

                    }                    
                    
                   
                })->columnSpan(2)->searchable()->preload(),
                
                Forms\Components\TextInput::make('nomor')->numeric()->label("Nomor Soal")->required()->minValue(1),  

                Forms\Components\RichEditor::make('judul')->required()->label('Soal')->columnSpanFull(),
                // Forms\Components\RichEditor::make('detail')->columnSpanFull()->label('Detail Soal'),                       
                       
                Forms\Components\TextInput::make('a')->required()->maxLength(255)->columnSpanFull(),
                Forms\Components\TextInput::make('b')->required()->maxLength(255)->columnSpanFull(),
                Forms\Components\TextInput::make('c')->maxLength(255)->columnSpanFull(),
                // Forms\Components\TextInput::make('d')->maxLength(255)->columnSpanFull(),

                Forms\Components\Radio::make('kunci')->required()->options([
                    "a" => "A",
                    "b" => "B",
                    "c" => "C",
                    // "d" => "D",
                    // "e" => "E"
                ])->label("Kunci Jawaban"),
            ])->columns(6);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('urutan')->label("Hari/Pekan"), 
                Tables\Columns\TextColumn::make('nomor'),                
                Tables\Columns\TextColumn::make('judul')->searchable()->label("Soal")->limit(30)->html(),
                Tables\Columns\TextColumn::make('jenis_ujian.nama'),
                Tables\Columns\TextColumn::make('a')->limit(20),
                Tables\Columns\TextColumn::make('b')->limit(20),
                Tables\Columns\TextColumn::make('c')->limit(20),
                // Tables\Columns\TextColumn::make('d')->limit(20),
                // Tables\Columns\TextColumn::make('kunci'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('jenis_ujian')->relationship('jenis_ujian', 'nama'),
                SelectFilter::make('urutan')->options(function(){
                    $urutan = [];
                    for ($i=1; $i <= 20; $i++) { 

                       $urutan[$i] = $i;

                    }

                    return $urutan;

                })->label("Hari/Pekan")->searchable(),                
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->after(function ($record) {
                    // Runs after the form fields are saved to the database.

                    $jenis_ujian = $record->jenis_ujian->nama;

                    if($jenis_ujian == 'Harian'){

                        $pertemuan = $record->pertemuan->pertemuan;

                        $record->urutan = $pertemuan;
                        $record->save();

                    }


                   
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->after(function ($record) {
                    // Runs after the form fields are saved to the database.

                    $jenis_ujian = $record->jenis_ujian->nama;
                  

                    if($jenis_ujian == 'Harian'){

                        $pertemuan = $record->pertemuan->pertemuan;

                        $record->urutan = $pertemuan;
                        $record->save();

                    }


                   
                }),
                Tables\Actions\DeleteAction::make(),
                ReplicateAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }   
    
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('nomor', 'desc');
    }

}
