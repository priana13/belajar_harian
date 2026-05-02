<?php

namespace App\Http\Livewire\Kuis;

use App\Models\Angkatan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\AngkatanUser;
use App\Models\Kelas;
use App\Models\SoalUjian;
use App\Models\Ujian;
use App\Models\User;

class Peringkat extends Component
{
    public $nomor_urut = 1;

    public function mount(){

        //sementara di offkan dulu
        // \abort(403 , 'Ups, Mohon maaf, halaman peringkat masih dalam perbaikan');
    }

    public function render()
    {
        $user =  auth()->user();
        $data['user'] = $user;
        $angkatan_user =$user->angkatan_user->last();      

        $kelas = Kelas::find($angkatan_user->kelas_id);
;
        $peringkat_user = []; 

        $peringkat_user = $this->getPeringkat("Umum");   


        $data['peringkat_user']  = $peringkat_user;

        $data['jumlah_soal'] = $user->ujian->last()->soal_ujian->count();

        $get_data_nilai_saya = $peringkat_user->where('user_id', $user->id)->first();        

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

    public function getPeringkat($type = "Umum"){

        $jumlah_tahun = 1;


        $nilai_harian = Ujian::filterTahunSebelumnya($jumlah_tahun)->harian()
            ->select('user_id', DB::raw('AVG(nilai) as nilai_harian'))
            ->groupBy('user_id')
            ->pluck('nilai_harian', 'user_id');

        $nilai_pekanan = Ujian::filterTahunSebelumnya($jumlah_tahun)->pekanan()
            ->select('user_id', DB::raw('AVG(nilai) as nilai_pekanan'))
            ->groupBy('user_id')
            ->pluck('nilai_pekanan', 'user_id');

        $nilai_akhir = Ujian::filterTahunSebelumnya($jumlah_tahun)->ujianAkhir()
            ->select('user_id', DB::raw('AVG(nilai) as nilai_akhir'))
            ->groupBy('user_id')
            ->pluck('nilai_akhir', 'user_id');

        $soal_ujian_agg = SoalUjian::select(
                'user_id',
                DB::raw('COUNT(*) as total_soal'),
                DB::raw('SUM(CASE WHEN istrue = 1 THEN 1 ELSE 0 END) as jawaban_benar')
            )
            ->whereHas('ujian', function($query) use ($jumlah_tahun) {
                $query->filterTahunSebelumnya($jumlah_tahun);
            })
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $users = User::select('id', 'name')->get();

        $list = $users->map(function ($user) use ($nilai_harian, $nilai_pekanan, $nilai_akhir, $soal_ujian_agg) {
            $harian = floatval($nilai_harian->get($user->id, 0));
            $pekanan = floatval($nilai_pekanan->get($user->id, 0));
            $akhir = floatval($nilai_akhir->get($user->id, 0));
            $total_nilai = $harian + $pekanan + $akhir;

            $soal = $soal_ujian_agg->get($user->id);
          
            return [
                'user_id' => $user->id,
                'nama' => $user->name,
                'nilai_harian' => $harian,
                'nilai_pekanan' => $pekanan,
                'nilai_akhir' => $akhir,
                'total_nilai' => intval($total_nilai),
                'total_soal' => $soal->total_soal ?? 0,
                'jawaban_benar' => $soal->jawaban_benar ?? 0,
            ];
        });

        return $list->sortByDesc('total_nilai')->values();
    }
}
