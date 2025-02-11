<?php

namespace Database\Seeders;

use App\Models\Materi;
use App\Models\MateriDetail;
use App\Models\KategoriMateri;
use Illuminate\Database\Seeder;
use App\Models\MateriDetailUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // insert data sample materi diklat
       $materi = [
        "KHUTBAH JUM'AT", "PIDATO", "SAR", "PENGURUSAN JENAZAH", "LEADERSHIP"
    ];
    foreach ($materi as $row) {
        Materi::create([
            "nama_materi" => $row,
            'type' => 'Diklat',
            'jenis_materi' => 'multimedia',
            'sinopsis' => fake()->words(10, true),
        ]);
    }

    // insert data sample materi_detail diklat
    $materi_detail = [
        'Materi 1', 'Materi 2', 'Materi 3', 'Materi 4',
        'Materi 5', 'Materi 6', 'Materi 7'
    ];
   

    // insert data sample kategori materi pembinaan
    $kategori = ["Fiqih", "Aqidah", "Muamalah"];
    foreach ($kategori as $row) {
        KategoriMateri::create(["nama_kategori" => $row]);
    }

    // insert data sample materi pembinaan
    $materi = [
        ["RJMI", 1],
        ["HDH", 1],
        ["MMI", 1],
        ["SIROTUL MUSTAQIM", 1],
        ["TORIQUNA", 1],
        ["Tafsir Al-Qur'an", 3],
        ["Hadits Shahih Bukhari", 3],
        ["Fiqh Al-Sunnah", 2],
        ["Tafsir Jalalain", 3],
        ["Ihya Ulum Al-Din", 2]
    ];
    foreach ($materi as $row) {
        Materi::create([
            "nama_materi" => $row[0],
            'kategori_id' => $row[1],
            // 'sinopsis' => 'aaaaa'
        ]);
    }


    // insert data sample materi_detail_user
    for ($i = 0; $i < 1; $i++) {
        $randMateri = rand(1, 15);
        $jumlahMateriDetail = MateriDetail::where('materi_id', $randMateri)->count();

        for ($j = 0; $j < rand(0, $jumlahMateriDetail); $j++) {
            MateriDetailUser::create([
                'materi_detail_id' => $j + 1,
                'user_id' => 1,
                'status' => 'sudah baca'
            ]);
        }
    }
    }
}
