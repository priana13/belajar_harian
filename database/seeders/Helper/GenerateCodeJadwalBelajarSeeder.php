<?php

namespace Database\Seeders\Helper;

use App\Models\Belajar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenerateCodeJadwalBelajarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list_jadwal = Belajar::whereNull('code')->get();

        foreach ($list_jadwal as $jadwal) {

            $jadwal->code = uniqid();
            $jadwal->save();

        }


        
    }
}
