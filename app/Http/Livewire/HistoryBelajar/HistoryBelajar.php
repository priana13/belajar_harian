<?php

namespace App\Http\Livewire\HistoryBelajar;

use App\Models\AngkatanUser;
use Livewire\Component;
use App\Models\Materi;
use App\Models\Ujian;

class HistoryBelajar extends Component
{

    public $no = 1;

    public $activeTab = 'history';
    
    public function render()
    {

        $user = auth()->user();

        $data['user'] = $user;

        $ujian_akhir = Ujian::ujianAkhir()->where('user_id', $user->id)->pluck('kode_ujian' , 'angkatan_id')->toArray();   

        $angkatan_user = AngkatanUser::query()->where('user_id', $user->id);

        if($this->activeTab == 'sertifikat'){          

            $angkatan_user = $angkatan_user->where('predikat' , '!=' , 'Kurang'); 

        }

        if($this->activeTab == 'daftar_nilai'){

            $angkatan_user = $angkatan_user->whereIn('angkatan_id', Ujian::ujianAkhir()->where('user_id', $user->id)->pluck('angkatan_id'));

        }


        $data['angkatan_user'] = $angkatan_user->get();

                   
        $data['ujian_akhir'] = $ujian_akhir;     

        return view('livewire.history-belajar.history-belajar',$data)->extends('layouts.app')->section('content');
    }
}
