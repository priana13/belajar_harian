<?php

namespace App\Http\Controllers\api;

use App\Models\Soal;
use App\Models\Ujian;
use App\Models\SoalUjian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CekJawabanUjianUserResource;
use App\Http\Resources\CekNilaiUjianResource;
use App\Http\Resources\LihatNilaiUjianResource;
use App\Models\Materi;
use App\Models\Jadwal;
use App\Models\JadwalUjian;
use App\Models\JenisUjian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    function list_soal_materi($jadwal_id)
    {

        $jadwal = JadwalUjian::find($jadwal_id);      
        $jenis_ujian = JenisUjian::where('nama', $jadwal->type)->first(); 
        
        if($jenis_ujian->id == 3){

            $soal = Soal::where('materi_id', $jadwal->angkatan->materi_id)
            ->where('jenis_ujian_id', $jenis_ujian->id)            
            ->get(); 

        }else{

            $soal = Soal::where('materi_id', $jadwal->angkatan->materi_id)
            ->where('jenis_ujian_id', $jenis_ujian->id)
            ->where('urutan',$jadwal->urutan)
            ->get(); 

        }
        

        // $today = Carbon::now();
        // $soal = Soal::whereHas('jadwal', function ($Query) use  ($today) {
        //         $Query->whereDate('tanggal', $today->toDateString());     
        //     })->get();
        
        // dd($soal);


        return response()->json([
            $soal
        ], 200);
    }

    // function lihat_nilai_ujian($materi_id)
    // {
    //     $nilai = Ujian::where('materi_id', $materi_id)->whereDate('created_at', now())
    //         // ->where('user_id', 1)->get();
    //         ->where('user_id', Auth::id())->get();

    //     return LihatNilaiUjianResource::collection($nilai);
    // }

    function insert_jawaban($soal_id, $ujian_id, $jawaban)
    {
        if ($jawaban == Soal::find($soal_id)->kunci) {
            $istrue = 1;
        } else $istrue = 0;

        SoalUjian::updateOrCreate(
        ['user_id' =>  Auth::id(), 'soal_id' => $soal_id, 'ujian_id' =>$ujian_id],
        [
            'user_id' => Auth::id(),
            'soal_id' => $soal_id,
            'ujian_id' => $ujian_id,
            'jawaban' => $jawaban,
            'istrue' => $istrue
        ]);

        return response()->json([
            'message' => 'berhasil diinput',
        ], 200);
    }

    function jawaban_user($ujian_id){
        $jawaban_user = SoalUjian::where('ujian_id', $ujian_id)
        ->where('user_id', Auth::id())
        ->get();
        
        return $jawaban_user;
    }


    function update_nilai_ujian($ujian_id)
    {        

        $ujian = Ujian::find($ujian_id);            

        if($ujian->jenis_ujian_id == 3){

            $jumlahSoal = Soal::where('materi_id', $ujian->materi_id)->akhir()->count();

        }else if($ujian->jenis_ujian_id == 2){

            $jumlahSoal = Soal::where('materi_id', $ujian->materi_id)->pekanan()->where('urutan' , $ujian->urutan)->count();            

        }else {

            $jumlahSoal = Soal::where('materi_id', $ujian->materi_id)->harian()->where('urutan' , $ujian->urutan)->count(); 
        }
      

        // $jumlahSoal = Soal::where('materi_id', $ujian->materi_id)->where('urutan' , $ujian->urutan)->count();

        $jawabanBenar = SoalUjian::select('soal_id')
            ->where('ujian_id', $ujian_id)
            ->where('user_id', Auth::id())
            ->where('istrue', 1)
            // ->groupby('soal_id')
            ->count();       

        $nilai = ($jawabanBenar / $jumlahSoal) * 100;       

        if ($nilai > 59) {
            $keterangan = 'Lulus';
        } else $keterangan = 'Tidak Lulus';


        $waktu_awal = strtotime( $ujian->created_at ) ;
        
        $sekarang = \strtotime( now() );

        $selisih_waktu = $sekarang - $waktu_awal;

        $waktu_mengerjakan = \number_format($selisih_waktu / 60 , 2);

        $siwa_detik = $selisih_waktu % 60;             

        $ujian = Ujian::find($ujian_id);
        $ujian->nilai = $nilai;
        $ujian->keterangan = $keterangan;
        $ujian->status = "Selesai";
        $ujian->waktu_mengerjakan = $waktu_mengerjakan;

        // Ujian::find($ujian_id)
        //     ->update([
        //         'nilai' => $nilai,
        //         'keterangan' => $keterangan,
        //         'status' => "Selesai",
        //         'waktu_mengerjakan' => $waktu_mengerjakan
        //     ]);


        if( $ujian->jenis_ujian_id == 3 ){

            // buat predikat dan IPK untuk ujian akhir
            $predikat = $this->tentukanPredikat( $ujian->id, $nilai);

            // $ipk = 0;

            $ujian->predikat = $predikat;

        }         

        $ujian->save();


        return CekNilaiUjianResource::collection(Ujian::where('id', $ujian_id)
            ->get());
    }

    public function tentukanPredikat(int $ujian_id ,int $nilai): string 
    {

        // Grade Nilai:
        // >= 10 : Cumlaude
        // >= 9.5 : Sangat Baik
        // >= 8.5: Baik
        // > 6: Cukup
        // ---------- tidak dapat sertifikat tapi dapat daftar nilai
        // <= 6: Kurang      

        $data_ujian = Ujian::find($ujian_id);

        $nilai_harian = Ujian::where('angkatan_id', $data_ujian->angkatan_id)->where('user_id', $data_ujian->user_id)->harian()->sum('nilai');
        $nilai_pekanan = Ujian::where('angkatan_id', $data_ujian->angkatan_id)->where('user_id', $data_ujian->user_id)->pekanan()->sum('nilai');

        $total_nilai_pekanan = $nilai_pekanan / 4;
        $total_nilai_harian = $nilai_harian / 20;

        $total_nilai = $nilai + $total_nilai_pekanan  + $total_nilai_harian; 
        $nilai_akhir = $total_nilai / 3;        

        $nilai_akhir = intval( $nilai_akhir );  

        if($nilai_akhir <= 60){

            $predikat = "Kurang";

        }elseif($nilai_akhir >60 && $nilai_akhir < 85){

            $predikat = "Cukup";

        }elseif($nilai_akhir >= 85 && $nilai_akhir < 95){

            $predikat = "Baik";
        }elseif($nilai_akhir >= 95 && $nilai_akhir < 100){

            $predikat = "Sangat Baik";

        }elseif($nilai_akhir >= 100){

            $predikat = "Cumlaude";
            
        }else{

            $predikat = "Kurang";
        }      
        
        if( in_array($predikat , ["Cukup", "Baik", "Sangat Baik" , "Cumlaude"]) ){

            $data_ujian->keterangan = "Lulus";

        }else{

            $data_ujian->keterangan = "Tidak Lulus";

        }


        $data_ujian->nilai_akhir = $nilai_akhir;
        $data_ujian->ipk = \number_format( $nilai_akhir / 25 , 2, '.' , ',');
        $data_ujian->predikat = $predikat;
        $data_ujian->save();


        return $predikat;

    }


    function cek_jawaban_ujian($ujian_id)
    {
        // $soal = Soal::where('materi_id', 3)->get()->toArray();
        // dd($soal);
        $materi_id = Ujian::find($ujian_id)->materi_id;
        $soal = Soal::where('materi_id', $materi_id)->get();
        return CekJawabanUjianUserResource::collection($soal);
    }


    public function koreksiNilai(){

        $list_ujian_akhir = Ujian::ujianAkhir()->whereNotNull('nilai')->get();

        foreach ($list_ujian_akhir as $ujian) {
           
            $this->tentukanPredikat($ujian->id , $ujian->nilai);

        }
    }
    

}
