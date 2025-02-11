<?php

namespace App\Http\Livewire\Kuis;

use App\Models\AngkatanUser;
use App\Models\User;
use App\Models\Ujian;
use Livewire\Component;
use App\Models\SoalUjian;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DaftarNilai extends Component
{   
    
    public $user;

    public $materi;

    public $ujian;

    public $barcodeData;

    public $angkatan;

    public array $nilai_ujian;

    public $predikat = [];

    public $angkatan_user;

    public function mount(Ujian $ujian)
    {      
        // dd($ujian);
        $this->ujian = $ujian;       

        $this->angkatan = $ujian->angkatan;

        $this->angkatan_user = AngkatanUser::where('user_id', $this->ujian->user_id)->where('angkatan_id', $ujian->angkatan_id)->first();

        // dd($this->angkatan_user);

        $this->user = User::find($this->ujian->user_id);

        $this->materi = $ujian->materi;
    }

    
    public function render()
    {

        $nilai_ujian_harian = Ujian::where('angkatan_id', $this->angkatan->id)->where('user_id', $this->ujian->user_id)->harian()->sum('nilai');

        $nilai_ujian_pekanan = Ujian::where('angkatan_id', $this->angkatan->id)->where('user_id', $this->ujian->user_id)->pekanan()->sum("nilai");

        $nilai_ujian_akhir = Ujian::where('angkatan_id', $this->angkatan->id)->where('user_id', $this->ujian->user_id)->ujianAkhir()->sum("nilai");

        $total_nilai = ( $nilai_ujian_harian / 20 ) + ( $nilai_ujian_pekanan / 4 ) + $nilai_ujian_akhir;        

        $ipk = ($total_nilai / 3) / 25;

        // dd($total_nilai  / 3, $ipk);

        $this->nilai_ujian = [
            "harian" => $nilai_ujian_harian / 20,
            "pekanan" => $nilai_ujian_pekanan / 4,
            "akhir" => $nilai_ujian_akhir,
            "total" => number_format($total_nilai, 0), 
            "ipk" => \number_format( $ipk , 2, '.' , ',')
        ];
     
        // dd($nilai_ujian_harian, $nilai_ujian_harian, $nilai_ujian_akhir);        

        $ujian_ids = Ujian::where('angkatan_id', $this->angkatan->id)->where('user_id', $this->ujian->user_id)->pluck('id');

        $soal_ujian = SoalUjian::whereIn('ujian_id', $ujian_ids)->get(); 

        // $this->nilai_ujian['harian'];
        
        
        // dd( $this->getPredikat( $this->nilai_ujian['pekanan'] ) );


        $this->barcodeData = base64_encode(QrCode::format('png')->generate(url()->current()));

        return view('livewire.kuis.daftar-nilai')->extends('layouts.app-full')->section('content');
    }



    public function getPredikat($nilai){

        $nilai = \intval($nilai);

        if($nilai <= 60){

            $predikat = "Kurang";

        }elseif($nilai >60 && $nilai < 85){

            $predikat = "Cukup";

        }elseif($nilai >= 85 && $nilai < 95){

            $predikat = "Baik";
        }elseif($nilai >= 95){

            $predikat = "Sangat Baik";
        }else{

            $predikat = "Kurang";
        }        

          return $predikat;
    }


}
