<?php

namespace App\Http\Livewire\Kuis;
use App\Models\Soal;
use App\Models\Ujian;
use Livewire\Component;
use App\Models\SoalUjian;
use App\Models\JenisUjian;
use App\Models\JadwalUjian;
use App\Http\Controllers\API\UjianController;

class EvaluasiKuis extends Component
{

    public $materi_id;
    public $ujian_id;


 
    public function mount($materi_id,$ujian_id)
    {      

        $this->materi_id = $materi_id;
        $this->ujian_id = $ujian_id;

    }
    public function render()
    {
        $data['jenis_ujian'] = JenisUjian::pluck('nama', 'id');    
     
        $data['list_soal'] = $this->list_soal_materi($this->ujian_id); 
        
        $data['ujian'] = Ujian::find($this->ujian_id);

        $data['jawaban_user'] = $this->jawaban_user();  

        return view('livewire.kuis.evaluasi-kuis',$data)->extends('layouts.app')->section('content');
    }



    public function list_soal_materi()
    {

        $ujian = Ujian::find($this->ujian_id);

        $jadwal = JadwalUjian::find($ujian->jadwal_ujian_id);   

        // dd($jadwal);

        $jenis_ujian = JenisUjian::where('nama', $jadwal->type)->first(); 
        
        if($jenis_ujian->id == 3){

            $soal = Soal::where('materi_id', $this->materi_id)
            ->where('jenis_ujian_id', $jenis_ujian->id)            
            ->get(); 

        }else{

            $soal = Soal::where('materi_id', $this->materi_id)
            ->where('jenis_ujian_id', $jenis_ujian->id)
            ->where('urutan',$jadwal->urutan)
            ->get(); 

        } 

        return $soal;
    }


    public function jawaban_user(){

        $jawaban_user = SoalUjian::where('ujian_id', $this->ujian_id)
                        ->where('user_id', auth()->user()->id )
                        ->get();
        
        return $jawaban_user;
    }



}
