<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Angkatan;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AngkatanResource\Pages;
use App\Filament\Resources\AngkatanResource\RelationManagers\KelasRelationManager;
use App\Filament\Resources\AngkatanResource\RelationManagers\AngkatanUserRelationManager;
use App\Filament\Resources\AngkatanResource\RelationManagers\JadwalBelajarRelationManager;
use App\Filament\Resources\JadwalUjianResource\RelationManagers\JadwalUjianRelationManager;

class AngkatanResource extends Resource
{
    protected static ?string $model = Angkatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-trending-up';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([     
                Forms\Components\TextInput::make('kode_angkatan')->unique(ignoreRecord: true),                  
                Forms\Components\Select::make('materi_id')->relationship('materi', 'nama_materi')->searchable()->preload()
                    ->required(), 
                    
                Fieldset::make('Pendaftaran')->schema([

                    Forms\Components\DatePicker::make('mulai_pendaftaran')->required()->default(now()),
                    Forms\Components\DatePicker::make('akhir_pendaftaran')->required(),
                    Forms\Components\TextInput::make('kuota')->numeric()->required()->default(1000), 
                ])->columns(3),   
                
                Fieldset::make('KBM')->schema([

                    Forms\Components\DatePicker::make('tanggal_mulai')->required()->label("Tanggal Mulai Belajar"),
                    Forms\Components\DatePicker::make('tanggal_akhir')->required()->label("Akhir Pembelajaran"),
                ]), 


                Forms\Components\DatePicker::make('tanggal_ujian')
                    ->required(),
                Forms\Components\TextInput::make('kode_daftar')->default(uniqid())->disabled()->unique(ignoreRecord: true), 
                Forms\Components\Select::make('status')->options(Angkatan::opsiStatus())->default("Persiapan"),
                Forms\Components\Toggle::make('is_umum')->label('Umum')->helperText("Pendaftaran tampil di halaman homepage setelah user login")
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([  
                Tables\Columns\TextColumn::make('kode_angkatan')->label('Kode')->searchable(),
                Tables\Columns\TextColumn::make('materi.nama_materi')->searchable(),
                // Tables\Columns\TextColumn::make('jenis_ujian'),                
                Tables\Columns\TextColumn::make('akhir_pendaftaran')
                    ->date(),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date(),
                Tables\Columns\TextColumn::make('tanggal_ujian')
                    ->date(),
                Tables\Columns\TextColumn::make('angkatan_user_count')->counts('angkatan_user')->label("Peserta"),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    "success" => "Aktif",
                    "warning" => "Pendaftaran",
                    
                ])->searchable(),                
                Tables\Columns\TextColumn::make('created_at')->label("Tgl Pembuatan")
                    ->dateTime(),
            ])
            ->filters([    

            ])
            ->actions([
                ActionGroup::make([

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Url Pendaftaran')->color('warning')->url(function($record){
                       
                        return route('daftar_angkatan' , $record->kode_daftar);
                    }),
    
                    Tables\Actions\Action::make('Generate')->color('success')->url(function($record){
                       
                        return route('filament.resources.angkatans.generate' , $record->id);
                    }),
                ])

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
          AngkatanUserRelationManager::class, 
          KelasRelationManager::class,           
          JadwalBelajarRelationManager::class,         
          JadwalUjianRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAngkatans::route('/'),
            'create' => Pages\CreateAngkatan::route('/create'),
            'edit' => Pages\EditAngkatan::route('/{record}/edit'),
            'generate' => Pages\GeneratePage::route('/{record}/generate'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query->orderByDesc('id');
    }

}
