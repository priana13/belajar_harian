<?php

namespace App\Http\Livewire;

use App\Models\Kota;
use Livewire\Component;
use App\Models\Angkatan;
use App\Models\Provinsi;

class Pendaftaran extends Component
{
    public $angkatan;

    public $provinsi;

    public $kota;

    public $provinsi_id;

    public $kode_daftar;

    public function mount($kode_daftar = null){ 
        
        if(request()->kodeangkatan){

           $this->angkatan = Angkatan::pendaftaran()->where('kode_daftar', request()->kodeangkatan)->first();

           if(!$this->angkatan){

            return redirect()->route('info-pendaftaran');

           }
        }

        if($kode_daftar){

            $this->kode_daftar = $kode_daftar;

            $angkatan = Angkatan::where('kode_daftar', $kode_daftar)->first();

            if(!$angkatan){
                abort(404);
            }
        }


    }


    public function render()
    {

        $this->provinsi = Provinsi::all();

        $kota = Kota::orderBy('id', 'desc');

        if($this->provinsi_id){

            $kota = $kota->where('provinsi_id' , $this->provinsi_id);
        }

        $this->kota = $kota->get();

        
        return view('livewire.pendaftaran')->extends('layouts.app')->section('content');
    }
}
