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
         // path file SQL
        $provinsi = database_path('seeders/wilayah/provinsi.sql');
        $kota     = database_path('seeders/wilayah/wilayah_kota.sql');

        // eksekusi provinsi
        if (file_exists($provinsi)) {
            $sql = file_get_contents($provinsi);
            DB::unprepared($sql);
            $this->command->info("Data provinsi berhasil diimport.");
        } else {
            $this->command->error("File provinsi.sql tidak ditemukan!");
        }

        // eksekusi kota
        if (file_exists($kota)) {
            $sql2 = file_get_contents($kota);
            DB::unprepared($sql2);
            $this->command->info("Data kota berhasil diimport.");
        } else {
            $this->command->error("File wilayah_kota.sql tidak ditemukan!");
        }

    }
}
