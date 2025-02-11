<?php

namespace App\Http\Livewire\Kuis;

use App\Models\Angkatan;
use Livewire\Component;
use App\Models\AngkatanUser;
use App\Models\Kelas;
use App\Models\SoalUjian;
use App\Models\Ujian;

class Peringkat extends Component
{
    public $nomor_urut = 1;

    public function mount(){

        //sementara di offkan dulu
        \abort(403 , 'Ups, Mohon maaf, halaman peringkat masih dalam perbaikan');
    }

    public function render()
    {
        $user =  auth()->user();
        $data['user'] = $user;
        $angkatan_user =$user->angkatan_user->last();      

        $kelas = Kelas::find($angkatan_user->kelas_id);

        // dd($kelas->ujian);

        $peringkat_user = [];

        if($angkatan_user){

            $data['angkatan'] = Angkatan::find($angkatan_user->angkatan_id);
          
            // $data['angkatan_user']=AngkatanUser::where('kelas_id',$angkatan_user->kelas_id)->where('angkatan_id',$angkatan_user->angkatan_id)->whereHas('user', function ($subQuery) {
            //     $subQuery->whereHas('ujian');
            // })->get();

            $data['angkatan_user'] = $kelas->user;           
            
            $jawaban_benar = $user->ujian->last()->soal_ujian()->benar()->count();

            // $data['nilai_saya'] = number_format($jawaban_benar / $jumlah_soal * 100,2 , ',' , '.');


            $peringkat_user = $this->getPeringkat($angkatan_user);         

            // $ranking = 



        }else{

            $data['angkatan_user'] = [];
        }

        $data['peringkat_user']  = $peringkat_user;

        $data['jumlah_soal'] = $user->ujian->last()->soal_ujian->count();

        $get_data_nilai_saya = $peringkat_user->where('user_id', $user->id)->first();

        // dd($get_data_nilai_saya);

        

        $urutan = 1;

        foreach ($peringkat_user as $key => $value) {            

           if($value["user_id"] == $user->id){
            
            $data['peringkat_saya'] = $urutan;

           }   

           $urutan ++;            
            
        }

        $data['nilai_saya'] = $get_data_nilai_saya["total_nilai"];
       

        // dd($kelas);
        return view('livewire.kuis.peringkat',$data)->extends('layouts.app')->section('content');
    }

    public function getPeringkat($angkatan_user){

        $angkatan = Angkatan::find($angkatan_user->angkatan_id);      

        $angkatan_user = $angkatan->angkatan_user()->whereHas('user', function ($subQuery) {
                 $subQuery->whereHas('ujian');
             })->get();
       
        $nilai_ujian_harian = Ujian::where('angkatan_id', $angkatan->id)->harian()->get();

        $nilai_ujian_pekanan = Ujian::where('angkatan_id', $angkatan->id)->pekanan()->get();

        $nilai_ujian_akhir = Ujian::where('angkatan_id', $angkatan->id)->ujianAkhir()->get();

        $ujian_ids = Ujian::where('angkatan_id', $angkatan->id)->pluck('id');

        $soal_ujian = SoalUjian::whereIn('ujian_id', $ujian_ids)->get();        
        
        
        foreach ($angkatan_user as $key => $angkatan) {

            $nilai_harian = $nilai_ujian_harian->where('user_id', $angkatan->user_id)->average('nilai');
            
            $nilai_pekanan = $nilai_ujian_pekanan->where('user_id', $angkatan->user_id)->average('nilai');
            
            $nilai_akhir = $nilai_ujian_akhir->where('user_id', $angkatan->user_id)->average('nilai');
                      
            $total_nilai = $nilai_harian + $nilai_pekanan + $nilai_akhir;

            $total_soal = $soal_ujian->where('user_id', $angkatan->user_id)->count();

            $jawaban_benar = $soal_ujian->where('user_id', $angkatan->user_id)->where('istrue' , 1)->count();
          

            $users[] = [
                "user_id" => $angkatan->user_id,
                "nama" => $angkatan->user->name,
                "nilai_harian" =>  $nilai_harian,
                "nilai_pekanan" => $nilai_pekanan,
                "nilai_akhir" => $nilai_akhir,
                "total_nilai" => intval($total_nilai),
                "total_soal" => $total_soal,
                "jawaban_benar" => $jawaban_benar,
            ];
        }

        $users = collect($users)->reverse();
        $nilai_users = $users->sortBy(["total_nilai" , "desc"])->reverse();


        // $sorted = $collection->sortBy([
        //     ['name', 'asc'],
        //     ['age', 'desc'],
        // ]);
        
        return $nilai_users;

        
    }
}
