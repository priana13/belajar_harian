<?php

namespace Database\Seeders;

use App\Models\Soal;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                // insert data soal
                $kunci = ['a', 'b', 'c', 'd'];
                for ($i = 1; $i < 13; $i++) {
                    for ($j = 1; $j < rand(0, 11); $j++) {
                        Soal::create([
                            'jenis_ujian_id' => rand(1,3),
                            'nomor' => $j,
                            'judul' => fake()->realText($maxNbChars = 50, $indexSize = 2) . '?',
                            'a' => fake()->realText($maxNbChars = 10, $indexSize = 2),
                            'b' => fake()->realText($maxNbChars = 10, $indexSize = 2),
                            'c' => fake()->realText($maxNbChars = 10, $indexSize = 2),
                            'd' => fake()->realText($maxNbChars = 10, $indexSize = 2),
                            'kunci' => $kunci[rand(0, 3)],
                            'materi_id' => $i
                        ]);
                    }
                }
    }
}
