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
        // materi id MMI = 56
        $sertifikatUser = SertifikatUser::where('materi_id' , 56)->update([
            'sertifikat_id' => 7,
            'ttd_nama' => 'Ust Muhammad Fatih Haidaril Iltihzam.M.Pd.I',
            'ttd_jabatan' => 'Ketua DPP',
            'ttd_image' => 'sertifikat/ttd/0Z431HEBHcDqYXla7B1z5QJufKa4aaXks0U9iJpC.png',
            'ttd_nama2' => 'Jami Furqon',
            'ttd_jabatan2' => 'KaDiv  Pembinaan & Aktivasi',
            'ttd_image2' => 'sertifikat/ttd/u3A1QlDqW0e0aMd5eIpeJFSYFMZZ6ysRf17kWM48.png',
        ]);
    }
}
