<?php

namespace Database\Seeders;

use App\Models\Gelombang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GelombangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gelombang::create([
            "gel" => 1,
            "tanggal_mulai" => now()
        ]);
    }
}
