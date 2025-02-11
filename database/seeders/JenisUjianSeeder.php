<?php

namespace Database\Seeders;

use App\Models\JenisUjian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisUjianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenis_ujian = ["Harian", "Pekanan", "Akhir"];

        foreach ($jenis_ujian as $key => $value) {
            JenisUjian::create([
                'nama' => $value,
                'waktu' => 25
            ]);
        }
    }
}
