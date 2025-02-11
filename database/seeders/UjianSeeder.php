<?php

namespace Database\Seeders;

use App\Models\Soal;
use App\Models\User;
use App\Models\Ujian;
use App\Models\SoalUjian;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UjianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kunci = ['a', 'b', 'c', 'd'];
        // insert data ujian
        $jenis_ujian=['harian','mingguan','akhir'];
        for ($i = 1; $i < 35; $i++) {

            $absensi = rand(1, 5);

            for ($j = 0; $j < $absensi; $j++) {
                $nilai = rand(10, 100);
                if ($nilai > 70) {
                    $keterangan = 'Lulus';
                } else $keterangan = 'Tidak Lulus';

                // Ujian::create([
                //     "nama_ujian" => rand(0, 1) ? 'UTS' : 'UAS',
                //     'user_id' => $i,
                //     'materi_id' => Soal::all()->random()->materi_id,
                //     'nilai' => $nilai,
                //     'keterangan' => $keterangan,
                //     'jenis' => $jenis_ujian[rand(0, 2)],
                // ]);
            }
        }

        // insert data soal_ujian
        for ($i = 0; $i < 20; $i++) {
            // bukan jenis user ustad pemateri
            $user_id = User::where('jenis_user_id', '!=', 2)
                ->inRandomOrder()
                ->limit(1)->first()->id;
            $absensi = rand(1, 3);

            // jumlah ujian user
            for ($k = 0; $k < $absensi; $k++) {
                $materi_id = Soal::all()->random()->materi_id;
                // $ujian_id = Ujian::all()->random()->id;
                $ujian_id = Ujian::create([
                    'nama_ujian' => "UAS",
                    'user_id' => $user_id,
                    'materi_id' => $materi_id,
                    'angkatan_id' => rand(1,3)
                ])->id;

                $soal_id = Soal::where('materi_id', $materi_id)->get();
                foreach ($soal_id as $key => $value) {
                    $jawaban = $kunci[rand(0, 3)];

                    if ($jawaban == Soal::find($value->id)->kunci) {
                        $istrue = 1;
                    } else $istrue = 0;

                    // SoalUjian::create([
                    //     'soal_id' => $value->id,
                    //     'ujian_id' => $ujian_id,
                    //     'user_id' => $user_id,
                    //     'jawaban' => $jawaban,
                    //     'istrue' => $istrue
                    // ]);
                }
            }
        }
    }
}
