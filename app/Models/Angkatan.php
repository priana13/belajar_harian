<?php

namespace App\Models;

use DateTime;
use App\Models\Belajar;
use App\Models\AngkatanUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Angkatan extends Model
{
    use HasFactory;
    protected $table = "angkatan";
    protected $guarded = [];

    public function angkatan_user(){

        return $this->hasMany(AngkatanUser::class, 'angkatan_id');
    }

    public function peserta(){      

        return $this->belongsToMany(User::class, "angkatan_users" , 'angkatan_id' , 'user_id');
    }


    public function kelas(){

        return $this->hasMany(Kelas::class, 'angkatan_id');
    }

    public function materi(){

        return $this->belongsTo(Materi::class, 'materi_id');
    }

    public function jadwal_belajar(){

        return $this->hasMany(Belajar::class, 'angkatan_id');
    }

    public function jadwal_ujian(){

        return $this->hasMany(JadwalUjian::class, 'angkatan_id');
    }

    public function scopePendaftaran($query){

        return $query->where('status', 'Pendaftaran');
    }

    public static function opsiStatus():array 
    {
        return [
            "Persiapan" => "Persiapan",
            "Pendaftaran" => "Pendaftaran",
            "Full" => "Full",
            "Aktif" => "Aktif",
            "Selesai" => "Selesai"
        ];
    }

    public function scopeLulus($query){

        return $query->where('keterangan', 'Lulus');
    }

    public function scopeTidakLulus($query){

        return $query->where('keterangan', 'Tidak Lulus');
    }

    public static function daftarkanPeserta(Angkatan $angkatan , int $userId){


            // cek dulu peserta di angkatan ini
            // self::cekPesertaTerdaftaran($angakatan , $userId);

            $user_exis = $angkatan->angkatan_user()->where('user_id', $userId)->first();

            if(!$user_exis){
                // jika user belum terdaftar

                $kelas = $angkatan->kelas->first();

                AngkatanUser::create([
                    "kode_angkatan" => $angkatan->kode_angkatan . $userId,
                    "user_id" => $userId,
                    "angkatan_id" => $angkatan->id,
                    "kelas_id" => $kelas->id
                ]);
                

                // dapatkan jumlah hari
                $tanggal_mulai = $angkatan->tanggal_mulai;
                $tanggal_akhir = $angkatan->tanggal_akhir;
            
                $tgl1 = new DateTime($tanggal_mulai);
                $tgl2 = new DateTime($tanggal_akhir);
                $selisih = $tgl2->diff($tgl1);
                $jumlah_hari = $selisih->d;       

                $tanggal = $tanggal_mulai;

                // dd($tanggal);

                $materi_pertemuan = $angkatan->materi->materi_detail()->orderBy('pertemuan')->get();     

                $hari_ke = 1;

                foreach ($materi_pertemuan as $materi_detail) {

                    Belajar::create([
                        "tanggal" => $tanggal,
                        "materi_detail_id" => $materi_detail->id,
                        "user_id" => $userId,
                        "angkatan_id" => $angkatan->id         
                    ]);


                // cek apakah termasuk hari ujian atau bukan, jika tanggal ujian lewati 2 hari
                if(in_array($hari_ke , [5,10,15])){

                    $penambah_hari = 3; // ditambah 2 hari

                }else{

                $penambah_hari = 1;

                }


                    $tanggal = date('Y-m-d', strtotime('+'. $penambah_hari .' day' , strtotime($tanggal)));
            
                    //    $tanggal = date('Y-m-d', strtotime( $tanggal .'+ 1 day' ));
                
                    $hari_ke ++;
                    
                }


            }
            // user sudah terdaftar sebelumnya


    }
  

    
}
