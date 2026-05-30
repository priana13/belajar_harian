<?php

namespace Database\Seeders;

use App\Models\SertifikatUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FixSertifikatBaruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // materi id MMI = 27
        $sertifikatUser = SertifikatUser::where('materi_id' , 27)->update([
            'sertifikat_id' => 7,
            'ttd_nama' => 'Ust Muhammad Fatih Haidaril Iltihzam.M.Pd.I',
            'ttd_jabatan' => 'Ketua DPP',
            'ttd_image' => '',
            'ttd_nama2' => 'Jami Furqon',
            'ttd_jabatan2' => 'KaDiv  Pembinaan & Aktivasi',
            'ttd_image2' => '',
        ]);
    }
}
