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
        "FIQIH", "AQIDAH"
    ];

    $no = 1;

    foreach ($materi as $row) {
        Materi::create([
            "nama_materi" => $row,
            'type' => 'Umum',
            'jenis_materi' => 'multimedia',
            'sinopsis' => fake()->words(10, true),
            'kode_materi' => uniqid(),
            'urutan' => $no
        ]);

        $no++;
    }

    // insert data sample materi_detail
    // $materi_detail = [
    //     'Materi 1', 'Materi 2', 'Materi 3', 'Materi 4',
    //     'Materi 5', 'Materi 6', 'Materi 7'
    // ];


    for ($i=0; $i < 20; $i++) { 

       MateriDetail::create([
        "materi_id" => 1,
        "pertemuan" => $i +1,
        "judul" => "BAB " . $i +1,
        "isi" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta vitae fugit earum temporibus expedita rerum necessitatibus error tempore, aperiam dolores blanditiis saepe possimus, ad quis! Corrupti molestiae eius error commodi.",
        "audio" => "audio.mp3"
       ]);
    }   

    // insert data sample kategori materi pembinaan
    $kategori = ["Fiqih", "Aqidah", "Muamalah"];
    foreach ($kategori as $row) {
        KategoriMateri::create(["nama_kategori" => $row]);
    }  


    // insert data sample materi_detail_user
    for ($i = 0; $i < 1; $i++) {
        $randMateri = rand(1, 5);
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
