<?php

namespace Database\Seeders;

use App\Models\Sertifikat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SertifikatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sertifikat::create([
            "nama" => "Sertifikat Materi 1",
            "bg" => asset('img/sertifikat.jpg')
        ]);
    }
}
