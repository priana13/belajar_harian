<?php

namespace App\Filament\Resources\AngkatanResource\Pages;

use App\Models\Ujian;
use App\Models\Angkatan;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\AngkatanResource;
use App\Models\JadwalUjianSoal;
use Filament\Notifications\Notification;

class GeneratePage extends Page
{
    protected static string $resource = AngkatanResource::class;

    protected static string $view = 'filament.resources.angkatan-resource.pages.generate-page';

    public $record; 

    public $peserta;

    public int $peserta_terpilih; 

    // properti daftarakan peserta ke angkatan berikutnya

    public $angkatan_selected;

    public $jenis_peserta;

    public function mount(Angkatan $record){

        $this->record = $record;

    }


    protected function getViewData(): array
    {
        // dd( $this->record->peserta->count() );

        if($this->jenis_peserta == 'lulus'){

            $this->peserta_terpilih = $this->record->angkatan_user()->lulus()->count();

        }elseif( $this->jenis_peserta == 'tidak_lulus'){
            
            $this->peserta_terpilih = $this->record->angkatan_user()->tidakLulus()->count();

        }else{

            $this->peserta_terpilih = $this->record->peserta->count();

        }


        return [
            "angkatan" => Angkatan::pendaftaran()->get()
        ];
    }

    public function kalkulasiKelulusan(){

        $para_peserta = $this->record->angkatan_user;

        foreach ($para_peserta as $peserta) {
           
            // tentukan nilai untuk para peserta               
            $this->tentukanPredikat($peserta);

        }               


    }


    public function tentukanPredikat($peserta_angkatan): string 
    {

        // dd($peserta_angkatan);

        // Grade Nilai:
        // >= 10 : Cumlaude
        // >= 9.5 : Sangat Baik
        // >= 8.5: Baik
        // > 6: Cukup
        // ---------- tidak dapat sertifikat tapi dapat daftar nilai
        // <= 6: Kurang      

        // $data_ujian = Ujian::find($ujian_id);

        $nilai_harian = Ujian::where('angkatan_id', $peserta_angkatan->angkatan_id)->where('user_id', $peserta_angkatan->user_id)->harian()->sum('nilai');
        $nilai_pekanan = Ujian::where('angkatan_id', $peserta_angkatan->angkatan_id)->where('user_id', $peserta_angkatan->user_id)->pekanan()->sum('nilai');
        $nilai_ujian_akhir = Ujian::where('angkatan_id', $peserta_angkatan->angkatan_id)->where('user_id', $peserta_angkatan->user_id)->ujianAkhir()->sum('nilai');
       

        $total_nilai_pekanan = $nilai_pekanan / 4;
        $total_nilai_harian = $nilai_harian / 20;

        $total_nilai = $nilai_ujian_akhir + $total_nilai_pekanan  + $total_nilai_harian; 
        $nilai_akhir = $total_nilai / 3;        

        // $nilai_akhir = $nilai_akhir ;  

        if($nilai_akhir <= 60){

            $predikat = "Kurang";

        }elseif($nilai_akhir >60 && $nilai_akhir < 85){

            $predikat = "Cukup";

        }elseif($nilai_akhir >= 85 && $nilai_akhir < 95){

            $predikat = "Baik";
        }elseif($nilai_akhir >= 95 && $nilai_akhir < 100){

            $predikat = "Sangat Baik";

        }elseif($nilai_akhir >= 100){

            $predikat = "Cumlaude";
            
        }else{

            $predikat = "Kurang";
        }      
        
        if( in_array($predikat , ["Cukup", "Baik", "Sangat Baik" , "Cumlaude"]) ){

            $peserta_angkatan->keterangan = "Lulus";

        }else{

            $peserta_angkatan->keterangan = "Tidak Lulus";

        }


        $peserta_angkatan->nilai = $nilai_akhir;
        $peserta_angkatan->ipk = number_format($nilai_akhir / 25 , 2) ;
        $peserta_angkatan->predikat = $predikat;
        $peserta_angkatan->status = "Selesai";
        $peserta_angkatan->save();


        return $predikat;

    }



    public function daftarkanPeserta(){
        // dd('test');

        $this->validate([
            'jenis_peserta' => "required",
            'angkatan_selected' => 'required'
        ]);


        if($this->jenis_peserta == "lulus"){

            $list_peserta = $this->record->angkatan_user()->lulus()->get();

        }else{

            $list_peserta = $this->record->angkatan_user()->tidakLulus()->get();
        }       


        $angkatan = Angkatan::find($this->angkatan_selected);        

        // daftarkan peserta ke angkatan

        foreach ($list_peserta as $peserta) {

            Angkatan::daftarkanPeserta($angkatan , $peserta->user_id);
        }

        Notification::make()
            ->title('Peserta telah didaftaran ke angkatan')
            ->success()
            ->send();
        
    }

    public function getSoal(){


        $jadwal_ujian = $this->record->jadwal_ujian;

        $materi = $this->record->jadwal_ujian->first()->angkatan->materi;

        $total_soal = 0 ;


       foreach ($jadwal_ujian as $ujian) {

        if($ujian->type == 'Harian'){

            $list_soal = $materi->soal()->harian()->urutan($ujian->urutan)->get();

        }elseif($ujian->type == 'Pekanan'){

            $list_soal = $materi->soal()->pekanan()->urutan($ujian->urutan)->get();

        }elseif($ujian->type == 'Akhir'){


            $list_soal = $materi->soal()->akhir()->urutan($ujian->urutan)->get();

        }

        //insert soal ke jadwal_ujian_soal

        foreach ($list_soal as $soal) {

            $cek_jadwal = JadwalUjianSoal::where('jadwal_ujian_id', $ujian->id)->where('soal_id', $soal->id)->first();

           if(!$cek_jadwal){

                JadwalUjianSoal::create([

                    "jadwal_ujian_id" => $ujian->id ,
                    "soal_id" => $soal->id
                ]);

                $total_soal ++;

           }

        }  

       
       } // end foreach

       if($total_soal > 0){

            Notification::make()
                ->title( $total_soal . ' Soal telah dimasukan ke angkatan')
                ->success()
                ->send();

       }else{

            Notification::make()
            ->title( 'Tidak ada Soal yg ditambahkan')
            ->warning()
            ->send();

       }



    }
    
}
