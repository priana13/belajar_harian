<?php

namespace App\Http\Livewire\HistoryBelajar;

use Livewire\Component;
use App\Models\Materi;
use App\Models\Ujian;

class HistoryBelajar extends Component
{

    public $no = 1;
    
    public function render()
    {

        $user = auth()->user();

        $data['user'] = $user;

        $angkatan_user = $user->angkatan_user;

        $data['angkatan_user'] = $angkatan_user;

        $ujian_akhir = Ujian::ujianAkhir()->where('user_id', $user->id)->pluck('kode_ujian' , 'angkatan_id')->toArray();     
       
        $data['ujian_akhir'] = $ujian_akhir;     

        return view('livewire.history-belajar.history-belajar',$data)->extends('layouts.app')->section('content');
    }
}
