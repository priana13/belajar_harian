<?php

namespace Database\Seeders;

use App\Models\Angkatan;
use App\Models\Belajar;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KoreksiJadwalBelajar extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $angkatanId = 9;
        $angkatan = Angkatan::find($angkatanId);
        $tanggal = "2024-03-11";
        $materi_detail_id = 111 ;

        $sudah_punya_jadwal = Belajar::whereDate('tanggal', $tanggal)->where('angkatan_id' , $angkatanId)->pluck('user_id');

        $users = User::whereNotIn('id', $sudah_punya_jadwal)->get();

        foreach ($users as $key => $user) {           
            // buat jadwal belajar user

            Belajar::create([
                "tanggal" => $tanggal,
                "materi_detail_id" => $materi_detail_id,
                "user_id" => $user->id,
                "angkatan_id" => $angkatan->id         
            ]);

        }

        $this->info('oke');

    }
}
