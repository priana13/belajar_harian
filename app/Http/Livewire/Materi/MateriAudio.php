<?php

namespace App\Http\Livewire\Materi;

use App\Models\Belajar;
use Livewire\Component;
use App\Models\JadwalUjian;

class MateriAudio extends Component
{
    public $jadwal_belajar;

    public $pertemuan;

    public $materi;

    public $ujian_harian;

    public function mount($code){


        $this->jadwal_belajar = Belajar::where('code' , $code)->first();

        $this->pertemuan = $this->jadwal_belajar->materi_detail;

        $this->materi = $this->pertemuan->materi;

        $this->ujian_harian = JadwalUjian::where('type', 'Harian')
                                    ->where('angkatan_id', $this->jadwal_belajar->angkatan_id)->where('urutan', $this->pertemuan->pertemuan )
                                    ->first();

    }

    
    public function render()
    {
        return view('livewire.materi.materi-audio');
    }
}
