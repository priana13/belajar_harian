<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoalResource\Pages;
use App\Models\Soal;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;

class SoalResource extends Resource
{
    protected static ?string $model = Soal::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Soal';

    protected static ?string $navigationLabel  = 'Soal';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('materi_id')->relationship('materi', 'nama_materi')->columnSpanFull()->searchable()->preload(),
                Forms\Components\Select::make('jenis_ujian_id')->relationship('jenis_ujian', 'nama')->required()->columnSpan(2)->reactive(),                  
                Forms\Components\TextInput::make('pekan')->numeric()->visible(function(Closure $set , callable $get){

                   if( $get('jenis_ujian_id') == 2){

                    return true;

                   }else{

                    return false;
                   }
                    
                }),  
                Forms\Components\TextInput::make('nomor')->numeric()->required(),             

                Forms\Components\TextInput::make('judul')->required()->maxLength(255)->label('Soal')->columnSpanFull(),
                Forms\Components\RichEditor::make('detail')->columnSpanFull()->label('Detail Soal'),                       
                       
                Forms\Components\TextInput::make('a')->required()->maxLength(255)->columnSpanFull(),
                Forms\Components\TextInput::make('b')->required()->maxLength(255)->columnSpanFull(),
                Forms\Components\TextInput::make('c')->maxLength(255)->columnSpanFull(),
                Forms\Components\TextInput::make('d')->maxLength(255)->columnSpanFull(),

                Forms\Components\Select::make('kunci')->required()->options([
                    "a" => "A",
                    "b" => "B",
                    "c" => "C",
                    "d" => "D",
                    "e" => "E"
                ])->label("Kunci Jawaban"),
            ])->columns(6);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([                
                Tables\Columns\TextColumn::make('nomor'),                
                Tables\Columns\TextColumn::make('judul')->searchable()->label("Soal")->limit(20),
                Tables\Columns\TextColumn::make('jenis_ujian.nama'),
                Tables\Columns\TextColumn::make('a')->limit(20),
                Tables\Columns\TextColumn::make('b')->limit(20),
                Tables\Columns\TextColumn::make('c')->limit(20),
                Tables\Columns\TextColumn::make('d')->limit(20),
                Tables\Columns\TextColumn::make('kunci'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('jenis_ujian')->relationship('jenis_ujian', 'nama'),
                SelectFilter::make('materi')->relationship('materi', 'nama_materi')->searchable(),
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
            'index' => Pages\ListSoals::route('/'),
            'create' => Pages\CreateSoal::route('/create'),
            'edit' => Pages\EditSoal::route('/{record}/edit'),
        ];
    }    
}
