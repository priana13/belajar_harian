<?php

namespace App\Http\Livewire\Materi;

use App\Models\Belajar;
use Livewire\Component;

class HalamanMateriVideo extends Component
{
    public $jadwal_belajar;

    public $pertemuan;

    public $materi;

    public function mount($code){

        $this->jadwal_belajar = Belajar::where('code' , $code)->first();

        $this->pertemuan = $this->jadwal_belajar->materi_detail;

        $this->materi = $this->pertemuan->materi;
    }


    public function render()
    {
        return view('livewire.materi.halaman-materi-video')->extends('layouts.app')->section('content');
    }
}
