<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Belajar;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\BelajarResource\Pages;

class BelajarResource extends Resource
{
    protected static ?string $model = Belajar::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    // protected static ?string $navigationGroup = 'Aktivasi';

    protected static ?string $navigationLabel  = 'Jadwal Belajar';

    protected static bool $shouldRegisterNavigation = true;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('materidetail_id')->relationship('materi_detail', 'judul')
                    ->required(),
                Forms\Components\Select::make('user_id')->relationship('user', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal'),
                Forms\Components\Select::make('status')->options(Belajar::getOptions()),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('tanggal')->date(),
                // Tables\Columns\TextColumn::make('angkatan.tanggal_mulai')->date(),
                // Tables\Columns\TextColumn::make('angkatan.kode_angkatan')->label("Kode"),

                Tables\Columns\TextColumn::make('roadmap.nama_roadmap')->label("Roadmap"),
                Tables\Columns\TextColumn::make('gelombang.gel')->label("Gelombang"),
                Tables\Columns\TextColumn::make('materi_detail.pertemuan')->label("Materi Pertemuan"),
                Tables\Columns\TextColumn::make('user.name')->label("Peserta"),
                // Tables\Columns\TextColumn::make('menit_terakhir'),   
                Tables\Columns\TextColumn::make('status'),             
            ])
            ->filters([
                SelectFilter::make('user')->relationship('user', 'name', function($query){

                    return $query->whereHas('jadwal_belajar');

                })->searchable(),
                SelectFilter::make('status')->options(Belajar::getOptions()),
                SelectFilter::make('angkatan')->relationship('angkatan', 'kode_angkatan'),
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
            'index' => Pages\ListBelajars::route('/'),
            'create' => Pages\CreateBelajar::route('/create'),
            'edit' => Pages\EditBelajar::route('/{record}/edit'),
            'rekap' => Pages\Rekap::route('/sumary'),
           
        ];
    }    
}
