<?php

namespace App\Http\Livewire\Kuis;
use App\Http\Controllers\API\UjianController;
use App\Models\JenisUjian;
use App\Models\Ujian;
use Livewire\Component;

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

        $ujian = new UjianController(); 
        $data['list_soal'] = json_decode($ujian->list_soal_materi($this->materi_id)->getContent(), true)[0]; 
        
        // $data['ujian'] = $ujian->lihat_nilai_ujian($this->materi_id)->first();  
        $data['ujian'] = Ujian::find($this->ujian_id);

        $data['jawaban_user'] = $ujian->jawaban_user($this->ujian_id);  
        return view('livewire.kuis.evaluasi-kuis',$data)->extends('layouts.app')->section('content');
    }
}
