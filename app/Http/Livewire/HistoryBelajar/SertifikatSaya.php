<?php

namespace App\Http\Livewire\HistoryBelajar;

use App\Models\User;
use Livewire\Component;
use App\Models\SertifikatUser;
use App\Http\Livewire\Kuis\Sertifikat;

class SertifikatSaya extends Component
{
    public function render()
    {
        
        if(request()->trial){

            $user  = User::whereHas('sertifikatUser')->first();          

            $list_sertifikat = SertifikatUser::where('user_id', $user->id)->take(3)->get();

        }else{

            $list_sertifikat = SertifikatUser::where('user_id', auth()->user()->id)->get();
        } 

        $data['list_sertifikat'] = $list_sertifikat;


        return view('livewire.history-belajar.sertifikat-saya' , $data)->extends('layouts.app')->section('content');
    }
}
