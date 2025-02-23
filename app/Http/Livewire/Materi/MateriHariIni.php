<?php

namespace App\Http\Livewire\Materi;

use App\Models\Belajar;
use Livewire\Component;

class MateriHariIni extends Component
{
    public $jadwal_belajar;

    public $pertemuan;

    public $materi;

    public $jenis_konten;

    public $code;

    public function mount($code){
   

        $this->jadwal_belajar = Belajar::where('code' , $code)->first();

        $this->pertemuan = $this->jadwal_belajar->materi_detail;

        $this->materi = $this->pertemuan->materi;

        $this->jenis_konten =  $this->pertemuan->jenis_kontent;

        $this->code = $code;
    }

    public function render()
    {
        return view('livewire.materi.materi-hari-ini')->extends('layouts.app')->section('content');
    }
}
