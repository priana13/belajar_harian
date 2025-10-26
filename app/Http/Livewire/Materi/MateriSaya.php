<?php

namespace App\Http\Livewire\Materi;

use App\Models\Belajar;
use Livewire\Component;
use App\Models\AngkatanUser;
use Livewire\WithPagination;
use App\Models\JadwalRoadmap;
use App\Models\AbsensiKegiatan;

class MateriSaya extends Component
{
    use WithPagination;

    public $paginate = 20;

    public function render()
    {     

        if(request()->paginate){

            $this->paginate = request()->paginate;
        }

        
        // $angkatan_saya = AngkatanUser::where('user_id', auth()->user()->id)->pluck('angkatan_id');      
        
        $jadwal_roadmap = JadwalRoadmap::where('gelombang_id', auth()->user()->gelombang_id)->first();

      
        // materi saya
        $data['materi_detail'] = [];
        
        if($jadwal_roadmap){

            $jadwal_belajar = Belajar::where('gelombang_id', auth()->user()->gelombang_id)->where('roadmap_id', $jadwal_roadmap->roadmap_id)->aktif()->whereDate('tanggal' , '<=' , now())->orderBy('id', 'desc');

            // $jadwal_belajar = $jadwal_belajar->whereIn('angkatan_id', $angkatan_saya);

            $data['materi_detail'] = $jadwal_belajar->simplePaginate($this->paginate);
        }         
       
        $evalusi = auth()->user()->ujian()->whereHas('jadwal_ujian')->orderBy('id', 'desc')->get();

        // evaluasi saya
        $data['evaluasi'] = $evalusi; 

        return view('livewire.materi.materi-saya',$data)->extends('layouts.app')->section('content');
    }


    public function tambahPaginate(){

        $this->paginate += 20;

        return redirect("/materiku?paginate=" . $this->paginate);
        
    }
}
