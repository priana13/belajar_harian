<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Materi;
use Livewire\Livewire;
use App\Models\Angkatan;
use App\Traits\HookAngkatan;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AngkatanResource\Pages;
use App\Filament\Resources\AngkatanResource\RelationManagers\KelasRelationManager;
use App\Filament\Resources\AngkatanResource\RelationManagers\AngkatanUserRelationManager;
use App\Filament\Resources\AngkatanResource\RelationManagers\JadwalBelajarRelationManager;
use App\Filament\Resources\JadwalUjianResource\RelationManagers\JadwalUjianRelationManager;

class AngkatanResource extends Resource
{
    use HookAngkatan;

    protected static ?string $model = Angkatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-trending-up';

    protected static ?string $navigationLabel = 'Kelas - Angkatan';

    protected static ?string $breadcrumb = "Kelas";

    protected static ?string $slug = "kelas";

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
                    // Forms\Components\DatePicker::make('tanggal_akhir')->required()->label("Akhir Pembelajaran"),
                    Forms\Components\DatePicker::make('tanggal_ujian')->required(),
                ]), 


                // Forms\Components\DatePicker::make('tanggal_ujian')->required(),

                Forms\Components\TextInput::make('kode_daftar')->default(uniqid())->disabled()->unique(ignoreRecord: true), 

                Forms\Components\Select::make('sertifikat_id')->relationship('sertifikat', 'nama')->searchable()->preload()->required()->label("Template Sertifikat"),

                Forms\Components\Select::make('gelombang_id')->relationship('gelombang' , 'gel')->label("Gelombang"),
                Forms\Components\Select::make('status')->options(Angkatan::opsiStatus())->default("Persiapan"),

                Forms\Components\Toggle::make('is_umum')->label('Umum')->helperText("Pendaftaran tampil di halaman homepage setelah user login"),
               
                Fieldset::make("Ads")->schema([
                    Forms\Components\TextInput::make("fb_pixel_id"),
                    // Forms\Components\TextInput::make("fb_capi")
                ])
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([ 
                Tables\Columns\TextColumn::make('gelombang.gel')->label("Gel"), 
                Tables\Columns\TextColumn::make('kode_angkatan')->label('Kode')->searchable(),
                Tables\Columns\TextColumn::make('materi.nama_materi')->searchable(),
                // Tables\Columns\TextColumn::make('jenis_ujian'),                
                // Tables\Columns\TextColumn::make('akhir_pendaftaran')
                //     ->date(),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date(),
                Tables\Columns\ViewColumn::make('tanggal_ujian')->view('tables.columns.tanggal-ujian'),
                Tables\Columns\TextColumn::make('angkatan_user_count')->counts('angkatan_user')->label("Peserta"),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    "success" => "Aktif",
                    "warning" => "Pendaftaran",
                    
                ])->searchable(),                
            ])
            ->filters([    
                SelectFilter::make('status')->options(Angkatan::opsiStatus()),
                SelectFilter::make('materi_id')->relationship('materi' , 'nama_materi')->label("Materi"),
                SelectFilter::make('gelombang_id')->relationship('gelombang' , 'gel')->label("Gelombang")
            ])
            ->actions([
                ActionGroup::make([

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Url Pendaftaran')->color('warning')->url(function($record){
                       
                        return route('daftar_angkatan' , $record->kode_daftar);
                    }),
    
                    Tables\Actions\Action::make('Generate')->color('success')->url(function($record){
                       
                        return route('filament.resources.kelas.generate' , $record->id);
                    }),
                    Tables\Actions\Action::make('Selesaikan')->color('danger')->requiresConfirmation()
                    ->visible(fn ($record) => $record->status == 'Aktif')
                    ->action(fn ($record) => $record->update(["status" => "Selesai"])),

                    Tables\Actions\Action::make('Aktifkan')->color('warning')->requiresConfirmation()
                    ->visible(fn ($record) => $record->status == 'Pendaftaran')
                    ->action(fn ($record) => $record->update(["status" => "Aktif"])),


                    Tables\Actions\Action::make('Buat Angkatan Berikutnya')->color('success')
                    ->form([
                        Forms\Components\TextInput::make('kode_angkatan')->unique(ignoreRecord: true),
                        Forms\Components\DatePicker::make('tanggal_mulai')->required()->label("Tanggal Mulai Belajar"),
                        Forms\Components\DatePicker::make('tanggal_ujian')->required(),
                    ])                    
                    ->action( function ($record , array $data){

                        $urutan_materi = $record->materi->urutan;

                        $materi_berikutnya = Materi::where('urutan' , ">" , $urutan_materi)->where('kelas_intensif' , true)->orderBy('urutan')->first();

                       if($materi_berikutnya){

                           // 1. Buat angkatan 
   
                           $angkatan_baru = Angkatan::create([                          
                               'kode_angkatan' => $data['kode_angkatan'],
                               'kode_daftar' => uniqid(),
                               'materi_id' => $materi_berikutnya->id,
                               'mulai_pendaftaran' => now(),
                               'akhir_pendaftaran' => now(),
                               'kuota' => 1000,
                               'is_umum' => false,                          
                               'tanggal_mulai' => $data['tanggal_mulai'],
                               'tanggal_ujian' => $data['tanggal_ujian'],                           
                                'status' => "Pendaftaran", 
                                "gelombang_id" => $record->gelombang_id   
               
                           ]);
   
                           HookAngkatan::hookAfterCreate($record , $angkatan_baru);
   
                           // 5. Daftarkan peserta
                           HookAngkatan::hookDaftarkanPeserta($record , $angkatan_baru);

                       }
                       
                    } ),
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
        //   KelasRelationManager::class,           
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
