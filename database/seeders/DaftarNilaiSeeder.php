<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ujian;
use App\Models\SoalUjian;
use App\Models\DaftarNilai;
use App\Models\AngkatanUser;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DaftarNilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ujian_akhir = Ujian::ujianAkhir()->get();

        $daftar_nilai = [];
        $total = 0;

        foreach ($ujian_akhir as $key => $row) {           

            $ujian = $row;  
            
       
            $angkatan = $ujian->angkatan;

            $angkatan_user = AngkatanUser::where('user_id', $ujian->user_id)->where('angkatan_id', $ujian->angkatan_id)->first();

            $user = User::find($ujian->user_id);

            $materi = $ujian->materi;

            $jumlah_pertemuan = 20;
          
            $nilai_ujian_harian = Ujian::where('angkatan_id', $angkatan->id)->where('user_id', $ujian->user_id)->harian()->sum('nilai');

            $nilai_ujian_pekanan = Ujian::where('angkatan_id', $angkatan->id)->where('user_id', $ujian->user_id)->pekanan()->sum("nilai");

            $nilai_ujian_akhir = Ujian::where('angkatan_id', $angkatan->id)->where('user_id', $ujian->user_id)->ujianAkhir()->sum("nilai");
            
            

            $total_nilai = ( $nilai_ujian_harian / $jumlah_pertemuan ) + ( $nilai_ujian_pekanan / 4 ) + $nilai_ujian_akhir;        

            $ipk = ($total_nilai / 3) / 25;

            // dd($total_nilai  / 3, $ipk);

            $nilai_ujian = [
                "harian" => $nilai_ujian_harian / $jumlah_pertemuan,
                "pekanan" => $nilai_ujian_pekanan / 4,
                "akhir" => $nilai_ujian_akhir,
                "total" => number_format($total_nilai, 0), 
                "ipk" => \number_format( $ipk , 2, '.' , ',')
            ];
            
          
            $predikat_harian = $this->getPredikat( $nilai_ujian['harian'] );
            $predikat_pekanan = $this->getPredikat( $nilai_ujian['pekanan'] );
            $predikat_akhir = $this->getPredikat( $nilai_ujian['akhir'] );
            $predikat_total = $this->getPredikat( $nilai_ujian['total'] );     

            $cek = DaftarNilai::where('user_id', $user->id)->where('materi_id', $materi->id)->first();

            if($cek){

                continue;
            }
            $daftar_nilai = DaftarNilai::create([
                'user_id' => $user->id,
                'materi_id' => $materi->id,
                'ujian_harian' => $nilai_ujian['harian'],
                'predikat_ujian_harian' => $predikat_harian,
                'ujian_pekanan' => $nilai_ujian['pekanan'],
                'predikat_ujian_pekanan' => $predikat_pekanan,
                'ujian_akhir' => $nilai_ujian['akhir'],
                'predikat_ujian_akhir' => $predikat_akhir,
                'nilai_akhir' => $nilai_ujian['total'],
                'ipk' => $nilai_ujian['ipk'],
                'predikat' => $predikat_total,
                'tanggal' => $row->created_at,
                'code' => strtoupper(uniqid('DN-')),
                'ttd_image' => 'img/ttd2.png',
                'ttd_nama' => 'Irfan Bahar Nurdin, S.Th.I, M.M.,',
                'ttd_jabatan' => 'Manager',
            ]);

            $total++;

        }

        $this->command->info('Selesai membuat daftar nilai untuk ' . $total . ' peserta.');
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
