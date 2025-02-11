<?php

namespace App\Http\Livewire\Materi;

use Livewire\Component;
use App\Models\AngkatanUser;
use App\Models\AbsensiKegiatan;
use App\Models\Belajar;
use Livewire\WithPagination;

class MateriSaya extends Component
{
    use WithPagination;

    public function render()
    {            

        $angkatan_aktif = AngkatanUser::aktif()->where('user_id', auth()->user()->id)->first();      

      
        // materi saya
        $data['materi'] = [];
        
        if($angkatan_aktif){

            $jadwal_belajar = Belajar::aktif()->whereDate('tanggal' , '<=' , now())->orderBy('id', 'desc');

            $jadwal_belajar = $jadwal_belajar->where('angkatan_id', $angkatan_aktif->angkatan_id);

            $data['materi'] = $jadwal_belajar->simplePaginate(40);
        }         
       

        // evaluasi saya
        $data['evaluasi'] = auth()->user()->ujian()->orderBy('id', 'desc')->get(); 

        return view('livewire.materi.materi-saya',$data)->extends('layouts.app')->section('content');
    }
}
