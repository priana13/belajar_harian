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

    public $data_sertifikat;

    public $ttd = [];

    public function mount(Ujian $ujian)
    {      
        // dd($ujian);
        $this->ujian = $ujian;       

        $this->angkatan = $ujian->angkatan;

        $this->angkatan_user = AngkatanUser::where('user_id', $this->ujian->user_id)->where('angkatan_id', $ujian->angkatan_id)->first();

        // dd($this->angkatan_user);

        $this->user = User::find($this->ujian->user_id);

        $this->materi = $ujian->materi;

        $this->data_sertifikat = $this->materi->sertifikat;

        $this->ttd = $this->getTtd($this->data_sertifikat->ttd_daftar_nilai);       

    }

    
    public function render()
    {      

        $pertemuan = $this->materi->materi_detail->count() ?? 20;      

        $tahun  = date('Y' , strtotime($this->ujian->created_at));
        $bulan = date('m' , strtotime($this->ujian->created_at));

        $nilai_ujian_harian = Ujian::where('materi_id', $this->ujian->materi_id)
                                ->whereYear('created_at', $tahun)
                                // ->whereMonth('created_at', $bulan)
                                ->where('user_id', $this->ujian->user_id)->harian()->take($pertemuan)->sum('nilai');


        $nilai_ujian_pekanan = Ujian::where('materi_id', $this->ujian->materi_id)
                                ->whereYear('created_at', $tahun)
                                // ->whereMonth('created_at', $bulan)
                                ->where('user_id', $this->ujian->user_id)->pekanan()->pluck("nilai");

        $nilai_ujian_pekanan = $nilai_ujian_pekanan->take(4)->sum();

        $nilai_ujian_akhir = Ujian::where('materi_id', $this->ujian->materi_id)
                                ->whereYear('created_at', $tahun)
                                ->whereMonth('created_at', $bulan)
                                ->where('user_id', $this->ujian->user_id)->ujianAkhir()->avg("nilai");

      
        $total_nilai = ( $nilai_ujian_harian / $pertemuan ) + ( $nilai_ujian_pekanan / 4 ) + $nilai_ujian_akhir;    
        
        $ipk = ($total_nilai / 3) / 25;

        // dd($total_nilai  / 3, $ipk);

        $this->nilai_ujian = [
            "harian" => floor($nilai_ujian_harian / $pertemuan),
            "pekanan" => floor($nilai_ujian_pekanan / 4),
            "akhir" => floor($nilai_ujian_akhir),
            "total" => number_format($total_nilai, 0), 
            "ipk" => \number_format( $ipk , 2, '.' , ',')
        ];
     

        $ujian_ids = Ujian::where('materi_id', $this->ujian->materi_id)->where('user_id', $this->ujian->user_id)->pluck('id');

        $soal_ujian = SoalUjian::whereIn('ujian_id', $ujian_ids)->get(); 


        $this->barcodeData = base64_encode(QrCode::format('svg')->generate(url()->current()));

  
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


    public function getTtd($ttd_daftar_nilai) : array
    {
        // ttd_image, ttd_nama, ttd_jabawan

        if($ttd_daftar_nilai == "default"){

            $data = [
                "ttd_image" => "/img/ttd2.png",
                "ttd_nama" => "Irfan Bahar Nurdin, S.Th.I., M.M.,",
                "ttd_jabatan" => "Manajer",
            ];

        }elseif($ttd_daftar_nilai == "ttd1"){

            $data = [
                "ttd_image" => $this->data_sertifikat->ttd_image1,
                "ttd_nama" => $this->data_sertifikat->ttd_nama1,
                "ttd_jabatan" => $this->data_sertifikat->ttd_jabatan1,
            ];

        }elseif($ttd_daftar_nilai == "ttd2"){

            $data = [
                "ttd_image" => $this->data_sertifikat->ttd_image2,
                "ttd_nama" => $this->data_sertifikat->ttd_nama2,
                "ttd_jabatan" => $this->data_sertifikat->ttd_jabatan2,
            ];

        }else{

            $data = [
                "ttd_image" => "/img/ttd2.png",
                "ttd_nama" => "Irfan Bahar Nurdin, S.Th.I., M.M.,",
                "ttd_jabatan" => "Manajer",
            ];
        }

        return $data;
    }


}
