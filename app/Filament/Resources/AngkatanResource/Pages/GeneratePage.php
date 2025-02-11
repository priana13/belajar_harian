<?php

namespace App\Filament\Resources\AngkatanResource\Pages;

use App\Models\Ujian;
use App\Models\Angkatan;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\AngkatanResource;
use App\Http\Resources\ListPesertaKegiatanResource;
use App\Models\JadwalUjianSoal;
use App\Models\MateriDetail;
use App\Models\User;
use Filament\Notifications\Notification;
use App\Models\Belajar;

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

        $peserta_tanpa_kelas = User::whereDoesntHave('angkatan')->count();


        return [
            "angkatan" => Angkatan::pendaftaran()->get(),
            "peserta_tanpa_kelas" => $peserta_tanpa_kelas
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

    public function ambilPeserta(){

        $peserta_tanpa_kelas = User::whereDoesntHave('angkatan')->get();

        foreach ($peserta_tanpa_kelas as $peserta) { 

            Angkatan::daftarkanPeserta($this->record , $peserta->id);

        }

        Notification::make()
            ->title( 'Pesrta Sudah Diaftarkan')
            ->success()
            ->send();

       
    }

    public function getJadwal(){

        $angkatan = $this->record;


        $materi_detail = MateriDetail::where('materi_id', $angkatan->materi_id)->where('pertemuan' , '>' , 20)->pluck('id');




        foreach ($angkatan->angkatan_user as $angkatan_user) {
            // code...

            $tanggal = '2024-06-03';

            $hari_ke = 1;

            foreach ($materi_detail as $row) {
                 
                    
                   $cek_jadwal = Belajar::where('angkatan_id' , $angkatan->id)->where('user_id', $angkatan_user->user_id)->where('materi_detail_id', $row)->first();

                   if(!$cek_jadwal){

                    // dd('test');

                     // buat jadwal baru untuk user ini

                    Belajar::create([
                        "tanggal" => $tanggal,
                        "materi_detail_id" => $row,
                        "user_id" => $angkatan_user->user_id,
                        "angkatan_id" => $angkatan_user->angkatan_id   
                    ]);

                     

                   }

                           // cek apakah termasuk hari ujian atau bukan, jika tanggal ujian lewati 2 hari
                if(in_array($hari_ke , [5,10,15])){

                    $penambah_hari = 3; // ditambah 2 hari

                  }else{

                   $penambah_hari = 1;

                  }


                    $tanggal = date('Y-m-d', strtotime('+'. $penambah_hari .' day' , strtotime($tanggal)));
            
                    //    $tanggal = date('Y-m-d', strtotime( $tanggal .'+ 1 day' ));
                   
                    $hari_ke ++;

              // dd($cek_jadwal);

            }

            // dd($user);
        }



      
    }


    public function buatJadwalBelajar(){

            $angkatan = $this->record;

            $materi_detail = MateriDetail::where('materi_id', $angkatan->materi_id)->pluck('id'); 

            $tanggal = $angkatan->tanggal_mulai;

            $hari_ke = 1;

            foreach ($materi_detail as $row) {
                                     
                   $cek_jadwal = Belajar::where('angkatan_id' , $angkatan->id)->where('materi_detail_id', $row)->first();

                   if(!$cek_jadwal){              
                     // buat jadwal baru untuk user ini
                        Belajar::create([
                            "tanggal" => $tanggal,
                            "materi_detail_id" => $row,                      
                            "angkatan_id" => $angkatan->id   
                        ]);                     

                   }

                // cek apakah termasuk hari ujian atau bukan, jika tanggal ujian lewati 2 hari
                if(in_array($hari_ke , [6,12,18,24,30,36])){

                    $penambah_hari = 2; // ditambah 1 hari

                  }else{

                   $penambah_hari = 1;

                  }


                    $tanggal = date('Y-m-d', strtotime('+'. $penambah_hari .' day' , strtotime($tanggal)));
            
                    //    $tanggal = date('Y-m-d', strtotime( $tanggal .'+ 1 day' ));
                   
                    $hari_ke ++;

              // dd($cek_jadwal);

            }

     
            Notification::make()
                ->title( 'Jadwal berhasil dibuat')
                ->success()
                ->send();

    }
    
}
