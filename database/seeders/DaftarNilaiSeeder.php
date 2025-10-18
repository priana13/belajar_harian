<?php

namespace Database\Seeders;

use App\Models\Ujian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DaftarNilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ujian_akhir = Ujian::ujianAkhir()->get();


        foreach ($ujian_akhir as $key => $row) {
            dd($row);
        }
    }
}
