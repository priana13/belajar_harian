<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;
    protected $table = 'ujian';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }

    public function jenis_ujian()
    {
        return $this->belongsTo(JenisUjian::class);
    }

    public function soal_ujian(){

        return $this->hasMany(SoalUjian::class, 'ujian_id');
    }

    public function jadwal_ujian(){
        return $this->belongsTo(JadwalUjian::class, 'jadwal_ujian_id');
    }

    public function scopeStatusUjian($query, $materi_id, $user_id)
    {
        return $this->where('materi_id', $materi_id)
            ->where('user_id', $user_id);
    }

    public function scopeHarian($query){

        return $query->where('jenis_ujian_id', 1);
    }

    public function scopePekanan($query){

        return $query->where('jenis_ujian_id', 2);
    }

    public function scopeUjianAkhir($query){

        return $query->where('jenis_ujian_id', 3);
    }

    public function scopeLulus($query){

        return $query->where('keterangan', 'Lulus');
    }

    public function scopeTidakLulus($query){

        return $query->where('keterangan', 'Tidak Lulus');
    }

    public static function hitungNilaiUjian($ujian_id){

        $ujian = Ujian::find($ujian_id);            

        // Evaluasi / Ujian Akhir
        if($ujian->jenis_ujian_id == 3){

            $jumlahSoal = Soal::where('materi_id', $ujian->materi_id)->akhir()->count();

        // Evaluasi Pekanan
        }else if($ujian->jenis_ujian_id == 2){

            $jumlahSoal = Soal::where('materi_id', $ujian->materi_id)->pekanan()->where('urutan' , $ujian->urutan)->count();            

        // Evaluasi Harian
        }else {

            $jumlahSoal = Soal::where('materi_id', $ujian->materi_id)->harian()->where('urutan' , $ujian->urutan)->count(); 
        }
      

        $jawabanBenar = SoalUjian::select('soal_id')
            ->where('ujian_id', $ujian_id)
            ->where('user_id', auth()->user()->id)
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

  
        if( $ujian->jenis_ujian_id == 3 ){

            // buat predikat dan IPK untuk ujian akhir
            $predikat = self::tentukanPredikat( $ujian->id, $nilai);

            // $ipk = 0;

            $ujian->predikat = $predikat;

        }         

        $ujian->save();


        // Buat Sertifikat untuk peserta
        if($predikat !== 'Kurang'){

            $materi = Materi::find($ujian->materi_id);

            $sertifikat = $materi->sertifikat;

            SertifikatUser::create([
                'user_id' => $ujian->user_id,
                'sertifikat_id' => $sertifikat->id, // Asumsikan ada sertifikat default dengan ID 1
                'materi_id' => $ujian->materi_id,
                'predikat' => $predikat, // Contoh predikat
                'tanggal' => $ujian->tanggal,
                'code' => uniqid(),
                'ttd_image' => 'img/ttd2.png',
                'ttd_nama' => 'Irfan Bahar Nurdin, S.Th.I, M.M.,',
                'ttd_jabatan' => 'Manager',
            ]);
        }

        

    }

    public static function tentukanPredikat(int $ujian_id ,int $nilai): string 
    {

        // Grade Nilai:
        // >= 10 : Cumlaude
        // >= 9.5 : Sangat Baik
        // >= 8.5: Baik
        // > 6: Cukup
        // ---------- tidak dapat sertifikat tapi dapat daftar nilai
        // <= 6: Kurang      

        $data_ujian = Ujian::find($ujian_id);

        // $nilai_harian = Ujian::where('angkatan_id', $data_ujian->angkatan_id)->where('user_id', $data_ujian->user_id)->harian()->sum('nilai');
        // $nilai_pekanan = Ujian::where('angkatan_id', $data_ujian->angkatan_id)->where('user_id', $data_ujian->user_id)->pekanan()->sum('nilai');


        $nilai_harian = Ujian::where('materi_id', $data_ujian->materi_id)->harian()
                        ->whereMonth('created_at', date('m'))
                        ->sum('nilai');

        $nilai_pekanan = Ujian::where('materi_id', $data_ujian->materi_id)->pekanan()
                        ->whereMonth('created_at', date('m'))
                        ->sum('nilai');


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


}
