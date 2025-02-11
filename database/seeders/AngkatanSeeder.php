<?php

namespace Database\Seeders;

use App\Models\Soal;
use App\Models\User;
use App\Models\Angkatan;
use App\Models\AngkatanUser;
use App\Models\Materi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AngkatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                // insert data sample Angkatan

                $users = User::all();              
        
                for ($i = 0; $i <= 2; $i++) {
                    $tanggal_mulai = date("Y-m-d", strtotime("2023-08-01 +" . rand(0, 15) . " days"));
                    $tanggal_akhir = date("Y-m-d", strtotime("2023-08-01 +" . rand(15, 25) . " days"));
                    $tanggal_ujian = date("Y-m-d", strtotime("2023-09-01 +" . rand(0, 15) . " days"));
                    $user_id = User::all()->random()->id;
                    $materi_id = Soal::all()->random()->materi_id;
                    
                    $angkatan = Angkatan::create([
                        // 'user_id' => $user_id,
                        'kode_angkatan' => uniqid(),
                        'kode_daftar' => uniqid(),
                        'materi_id' => $materi_id,
                        'tanggal_mulai' => $tanggal_mulai,
                        'tanggal_akhir' => $tanggal_akhir,
                        'tanggal_ujian' => $tanggal_ujian,
                        // 'admin1' => $user_id,        
        
                    ]);

                    
                }
    }
}
