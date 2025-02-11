<?php

namespace App\Filament\Resources\AngkatanResource\RelationManagers;

use DateTime;
use Filament\Forms;
use Filament\Tables;
use Livewire\Livewire;
use App\Models\Belajar;
use App\Models\Angkatan;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Models\Ujian;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Livewire\LivewireManager;

class AngkatanUserRelationManager extends RelationManager
{
    protected static string $relationship = 'angkatan_user';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Peserta';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([                
                // Forms\Components\Select::make('user_id')->relationship('user', 'name', function(){

                //     dd( $query->getQuery() );

                // })->searchable()->required()->preload(),

                 Forms\Components\Select::make('user_id')->options(function(RelationManager $livewire){

                    $user_terdaftar = $livewire->ownerRecord->angkatan_user()->pluck('user_id');

                    $users = User::whereNotIn('id', $user_terdaftar)->pluck('name', 'id');

                    return $users;

                 })->searchable()->required()->preload(),

                // Forms\Components\Select::make('kelas_id')->relationship('kelas', 'nama_kelas')->searchable()->required()->preload(),

                Forms\Components\Select::make('kelas_id')->options(function(RelationManager $livewire){

                    $kelas = $livewire->ownerRecord->kelas()->pluck('nama_kelas', 'id');

                    return $kelas;

                })->searchable()->required()->preload(),

            Forms\Components\Select::make('status')->options([
                "Aktif" => "Aktif",
                "Off" => "Off"
            ])->required()->default("Aktif"),
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label("Id Angkatan User"),
                Tables\Columns\TextColumn::make('user.name')->label('Peserta')->searchable(),
                Tables\Columns\TextColumn::make('user.no_hp')->searchable()->label("No HP"),
                Tables\Columns\TextColumn::make('user.kota')->searchable()->label("Kota"),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')->label('Kelas'),
                Tables\Columns\TextColumn::make('user.jenis_kelamin')->searchable(),
                Tables\Columns\TextColumn::make('user.pekerjaan')->searchable(),
                // Tables\Columns\TextColumn::make('jenis_user.nama_jenis')->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\BadgeColumn::make('keterangan')->colors([
                    "success" => "Lulus"
                ]),
                Tables\Columns\TextColumn::make('nilai'),
                Tables\Columns\TextColumn::make('ipk'),
                Tables\Columns\TextColumn::make('predikat'),
            ])
            ->filters([
                SelectFilter::make('kelas')->relationship('kelas', 'nama_kelas'),
                SelectFilter::make('status')->options([
                    "Aktif" => "Aktif",
                    "Off" => "Off"
                ]),
                SelectFilter::make('keterangan')->options([
                    "Lulus" => "Lulus", 
                    "Tidak Lulus" => "Tidak Lulus"
                ]),
                SelectFilter::make('predikat')->options([
                    "Cumlaude" => "Cumlaude", 
                    "Sangat Baik" => "Sangat Baik",
                    "Baik" => "Baik",
                    "Cukup" => "Cukup",
                    "Kurang" => "Kurang"
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah')
                    ->after(function( CreateAction $action){

                        $record = $action->getRecord();

                        $angkatan = $record->angkatan;

                                    
                        // dapatkan jumlah hari
                        // $tanggal_mulai = $angkatan->tanggal_mulai;
                        // $tanggal_akhir = $angkatan->tanggal_akhir;
                    
                        // $tgl1 = new DateTime($tanggal_mulai);
                        // $tgl2 = new DateTime($tanggal_akhir);
                        // $selisih = $tgl2->diff($tgl1);
                        // $jumlah_hari = $selisih->d;       

                        // $tanggal = $tanggal_mulai;

                        // // dd($tanggal);

                        // $materi_pertemuan = $angkatan->materi->materi_detail()->orderBy('pertemuan')->get();     

                        // $hari_ke = 1;

                        // foreach ($materi_pertemuan as $materi_detail) {

                        //     //batasi sampai 20 pertemuan saja
                        //     if($hari_ke == 21){
                        //         break;
                        //     }


                        //     Belajar::create([
                        //         "tanggal" => $tanggal,
                        //         "materi_detail_id" => $materi_detail->id,
                        //         "user_id" => $record->user_id,
                        //         "angkatan_id" => $angkatan->id         
                        //     ]);


                        //     // cek apakah termasuk hari ujian atau bukan, jika tanggal ujian lewati 2 hari
                        //     if(in_array($hari_ke , [5,10,15])){

                        //         $penambah_hari = 3; // ditambah 2 hari

                        //     }else{

                        //     $penambah_hari = 1;

                        //     }


                        //     $tanggal = date('Y-m-d', strtotime('+'. $penambah_hari .' day' , strtotime($tanggal)));
                    
                        //     //    $tanggal = date('Y-m-d', strtotime( $tanggal .'+ 1 day' ));
                        


                        //     $hari_ke ++;
                            
                        // }
                    

                    }),
            ])
            ->actions([
                Tables\Actions\Action::make("Sertifikat")->url(function($record){

                    $ujian = Ujian::where('angkatan_id', $record->angkatan_id)->where('user_id', $record->user_id)->ujianAkhir()->first();

                    if($ujian){

                        return route('sertifikat',$ujian->kode_ujian);
                    }
                    
                    return '#';

                })->openUrlInNewTab()
                ->visible(function($record){

                    return ($record->keterangan == 'Lulus');
                }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                FilamentExportBulkAction::make('export')
            ]);
    }    


    public static function getTitle(): string
    {      

        return static::$title ?? Str::headline(static::getPluralModelLabel());
    }
}
