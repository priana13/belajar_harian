<?php

namespace App\Http\Livewire\Homepage;

use DateTime;
use Carbon\Carbon; 
use App\Models\Soal;
use App\Models\Belajar;
use Livewire\Component;
use App\Models\Angkatan;
use App\Models\JadwalUjian;

use App\Models\AngkatanUser;
use App\Models\AbsensiKegiatan;
use App\Models\JadwalRoadmap;
use App\Models\Roadmap;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class HomeNew extends Component
{

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
        $soal_harian = 0;

        if (Auth::check()) {

            // $angkatan_aktif = AngkatanUser::aktif()->where('user_id', auth()->user()->id)->first();  
            
            $jadwal_roadmap = JadwalRoadmap::where('gelombang_id', auth()->user()->gelombang_id)->first();

                      
            if($jadwal_roadmap){

                $materi = Belajar::where('gelombang_id', auth()->user()->gelombang_id)->where('roadmap_id', $jadwal_roadmap->roadmap_id)->where('tanggal', date('Y-m-d'))->latest()->first();

           
                // dd($materi->materi_detail->pertemuan);
                
                if($materi){
                    
                    $ujian_harian = JadwalUjian::where('type', 'Harian')
                                    // ->where('angkatan_id', $angkatan_aktif->angkatan_id)
                                    ->where('gelombang_id', auth()->user()->gelombang_id)->where('roadmap_id', $jadwal_roadmap->roadmap_id)
                                    ->where('urutan', $materi->materi_detail->pertemuan )
                                    ->first();

                    

                    $soal_harian = ($ujian_harian) ?  Soal::where('materi_id' , $materi->id)->where('jenis_ujian_id' , 1)->where('urutan' , $ujian_harian->urutan)->count() : 0;
                   

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

            $jadwal_ujian = JadwalUjian::where('gelombang_id', auth()->user()->gelombang_id)->where('roadmap_id', $jadwal_roadmap->roadmap_id)
            // ->where('angkatan_id', $angkatan_aktif->angkatan_id)
            ->whereIn('type', ["Pekanan", "Akhir"])->duaHari()->get();             
           

        }

        $pengumuman = Setting::getValue('pengumuman');

        return view('livewire.homepage.home-new',compact('materi', 'angkatan' , 'jadwal_ujian' , 'ujian_harian' , 'soal_harian' , 'pengumuman'))->extends('layouts.app')->section('content');
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

    }

}
