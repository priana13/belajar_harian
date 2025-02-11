<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinsi = 'database/seeders/wilayah/provinsi.sql'; // Ganti dengan path file SQL Anda

        // Baca isi file SQL
        $sql = file_get_contents($provinsi);

        DB::unprepared($sql);



    }
}
