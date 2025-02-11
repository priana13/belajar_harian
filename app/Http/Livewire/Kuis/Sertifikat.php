<?php

namespace App\Http\Livewire\Kuis;

use Livewire\Component;
use App\Models\User;
use App\Models\Materi;
use App\Models\Ujian;
use SimpleSoftwareIO\QrCode\Facades\QrCode;




class Sertifikat extends Component
{
    public $user;

    public $materi;

    public $ujian;

    public function mount(Ujian $ujian)
    {      
      
        $this->ujian = $ujian;       

        if($ujian->predikat == 'Kurang'){

           abort(403 , "Data tidak tersedia");

        }

        $this->user = User::find($this->ujian->user_id);

        $this->materi = $ujian->materi;
    }


    
    public function render()
    {
        $barcodeData = base64_encode(QrCode::format('png')->generate(url()->current()));
        
        return view('livewire.kuis.sertifikat',compact('barcodeData'))->extends('layouts.app-full')->section('content');
    }


    public function getPredikat($nilai){

        // Grade Nilai:
        // >= 10 : Cumlaude
        // >= 9.5 : Sangat Baik
        // >= 8.5: Baik
        // > 6: Cukup
        // ---------- tidak dapat sertifikat tapi dapat daftar nilai
        // <= 6: Kurang


    }
}
