<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Angkatan;
use App\Models\AngkatanUser;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $list_angkatan = Angkatan::all();
        $users = User::all();

        foreach ($list_angkatan as $angkatan) {

            $list_kelas = [];

            for ($i=1; $i <= 3; $i++) { 

                $kelas = Kelas::create([
                    'nama_kelas' => "Kelas " . $i,
                    'angkatan_id' => $angkatan->id
                ]);

                $list_kelas[] = $kelas->id;

            }

            foreach($users as $user){
                        
                AngkatanUser::create([
                    'user_id' => $user->id,
                    'angkatan_id' => $angkatan->id,
                    'kelas_id' => $list_kelas[rand(0,2)]
                ]);

            }
            
        }
        
    }
}
