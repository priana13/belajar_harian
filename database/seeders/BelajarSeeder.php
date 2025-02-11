<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Belajar;
use App\Models\MateriDetail;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BelajarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                // insert data belajar
                for ($i = 0; $i < 20; $i++) {
                    $user_id = User::where('jenis_user_id', '!=', 1)->inRandomOrder()->limit(1)->first()->id;
                    $absensi = rand(1, 3);
        
                    for ($j = 0; $j < $absensi; $j++) {
                        Belajar::create([
                            'tanggal' => date("Y-m-d", strtotime("2023-09-10 +" . rand(0, 30) . " days")),
                            'materi_detail_id' => MateriDetail::all()->random()->id,
                            'user_id' => $user_id,
                            'halaman_terakhir' => rand(1, 6),
                            'angkatan_id' => rand(1,3)

                        ]);
                    }
                }
    }
}
