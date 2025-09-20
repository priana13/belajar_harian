<?php

namespace Database\Seeders;

use App\Models\Materi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenerateKodeMateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Materi::all() as $materi) {
            if(!$materi->kode_materi){
                $materi->kode_materi = uniqid();
                $materi->save();
            }
        }
    }
}
