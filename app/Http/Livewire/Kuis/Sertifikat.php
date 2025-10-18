<?php

namespace App\Http\Livewire\Kuis;

use App\Models\User;
use App\Models\Ujian;
use App\Models\Materi;
use Livewire\Component;
use App\Models\SertifikatUser;
use SimpleSoftwareIO\QrCode\Facades\QrCode;




class Sertifikat extends Component
{
    public $user;

    public $materi;

    public $ujian;

    public $sertifikat;

    public function mount($code)
    {          
             
        // $this->ujian = Ujian::where('kode_ujian', $code)->first(); 

        $this->sertifikat = SertifikatUser::where('code', $code)->first();
     
        $this->user = User::find($this->sertifikat->user_id);

        $this->materi = $this->sertifikat->materi;

        // dd('test');
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
