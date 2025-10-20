<?php

namespace App\Http\Livewire;

use App\Models\Belajar;
use Livewire\Component;
use App\Models\JadwalUjian;
use App\Models\JadwalRoadmap;

class MateriBerikutnya extends Component
{
    public function render()
    {

        $jadwal_roadmap = JadwalRoadmap::where('gelombang_id', auth()->user()->gelombang_id)->first();

        $jadwal_berikutnya = [];

        $jadwal_ujian = [];
                   
        if($jadwal_roadmap){

            $jadwal_berikutnya = Belajar::where('gelombang_id', auth()->user()->gelombang_id)->where('roadmap_id', $jadwal_roadmap->roadmap_id)->aktif()->whereDate('tanggal' , '>' , now())->take(1)->orderBy('id', 'asc')->get();


            $jadwal_ujian = JadwalUjian::whereIn('type' , ["Pekanan" , "Akhir"])->where('gelombang_id', auth()->user()->gelombang_id)->where('roadmap_id', $jadwal_roadmap->roadmap_id)->whereDate('tanggal' , '>' , now())->take(1)->orderBy('id', 'asc')->get();

       

        } 


        return view('livewire.materi-berikutnya' , compact('jadwal_berikutnya' , 'jadwal_ujian'));
    }
}
