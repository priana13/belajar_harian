<?php

namespace Database\Seeders;

use App\Models\Materi;
use App\Models\Sertifikat;
use App\Models\SertifikatUser;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SertifikatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $sertifikat = Sertifikat::create([
            "nama" => "Sertifikat Materi 1",
            "bg" => asset('img/sertifikat.jpg')
        ]);

        $materi = Materi::first();

        SertifikatUser::create([
        'user_id' => 2,
        'sertifikat_id' => $sertifikat->id, // Asumsikan ada sertifikat default dengan ID 1
        'materi_id' => $materi->id,
        'predikat' => "Cukup", // Contoh predikat
        'tanggal' => date("Y-m-d"),
        'code' => uniqid(),
        'ttd_image' => 'img/ttd2.png',

        'ttd_nama' => 'Irfan Bahar Nurdin, S.Th.I, M.M.,',
        'ttd_jabatan' => 'Manager',
    ]);
    }
}
