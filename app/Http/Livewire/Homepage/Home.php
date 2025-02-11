<?php

namespace App\Http\Livewire\Homepage;

use App\Models\AbsensiKegiatan;
use App\Models\Angkatan;
use App\Models\AngkatanUser;
use App\Models\Belajar;
use App\Models\JadwalUjian;
use Carbon\Carbon; 
use DateTime;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Home extends Component
{
    protected $listeners = ['absen' => 'absen'];
    public $status_absen=null;

    public function render()
    {
        
        /**
         * Daftar jadi peserta di angkatan
         */
        $angkatan = Angkatan::pendaftaran()->where('is_umum', true)->get();

        // dd($angkatan);

        $hari_ini = Carbon::today(); 

        $angkatan_aktif = null;
        $materi = null;
        $ujian_harian = null;

        if (Auth::check()) {

            $angkatan_aktif = AngkatanUser::aktif()->where('user_id', auth()->user()->id)->first();              
           
            // dd($angkatan_aktif);

            //cari jadwal belajar

            if($angkatan_aktif){

                $materi = Belajar::where('angkatan_id', $angkatan_aktif->angkatan_id)->where('tanggal', date('Y-m-d'))->latest()->first(); 

                // dd($materi->materi_detail->pertemuan);
                
                if($materi){
                    
                    $ujian_harian = JadwalUjian::where('type', 'Harian')
                                    ->where('angkatan_id', $angkatan_aktif->angkatan_id)
                                    ->where('urutan', $materi->materi_detail->pertemuan)
                                    ->first();

                    // dd($ujian_harian);

                }
              


            }

            // dd($materi);

                    
        if($materi){
            
            $this->status_absen = AbsensiKegiatan::where('user_id',auth()->id())->where('materi_detail_id', $materi->materi_detail->id)->first();
        }
            
        }

        // pastikan 1 orang user hanya boleh ikut 1 angkatan dalam waktu 1 bulan       
       
        
        $jadwal_ujian = []; 

        if($angkatan_aktif){

            $jadwal_ujian = JadwalUjian::where('angkatan_id', $angkatan_aktif->angkatan_id)->whereIn('type', ["Pekanan", "Akhir"])->duaHari()->get();             
           

        }

        return view('livewire.homepage.home',compact('materi', 'angkatan' , 'jadwal_ujian' , 'ujian_harian'))->extends('layouts.app')->section('content');
    }

    public function absen($id){
        if ( $this->status_absen == null){
             $this->status_absen = AbsensiKegiatan::create(['user_id' => auth()->id(),'materi_detail_id'=>$id,'status_kehadiran'=>'hadir'])->first();
        }
    }

    public function daftar(){
     
    }

    public function login(){

        return redirect()->route('login');
    }

    public function register(){

        return redirect()->route('register');
    }

    public function mendaftar(Angkatan $angkatan){
        
        $kelas = $angkatan->kelas->first();

        AngkatanUser::create([
            "kode_angkatan" => $angkatan->kode_angkatan . auth()->user()->id,
            "user_id" => auth()->user()->id,
            "angkatan_id" => $angkatan->id,
            "kelas_id" => $kelas->id
        ]);
        

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

        //     Belajar::create([
        //         "tanggal" => $tanggal,
        //         "materi_detail_id" => $materi_detail->id,
        //         "user_id" => auth()->user()->id,
        //         "angkatan_id" => $angkatan->id         
        //        ]);


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
       


    }
}
