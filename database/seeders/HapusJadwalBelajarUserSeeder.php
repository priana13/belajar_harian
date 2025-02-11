<?php

namespace Database\Seeders;

use App\Models\Angkatan;
use App\Models\Belajar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HapusJadwalBelajarUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $angkatan_aktif = Angkatan::get();

       foreach ($angkatan_aktif as $key => $angkatan) {

            $user = $angkatan->angkatan_user->first()->user_id;

            $jadwal_belajar = Belajar::where('angkatan_id' , $angkatan->id)
            ->where('user_id', $user)
            ->pluck('id');

            // get jadwal belajar yg akan dihapus
            $jadwal_yg_akan_dihapus = Belajar::where('angkatan_id', $angkatan->id)
                                        ->whereNotIn('id', $jadwal_belajar)->delete();

        //    dd('oke');
       
       }

    //    $this->info('oke');
    }
}
