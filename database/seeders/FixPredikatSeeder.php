<?php

namespace Database\Seeders;

use App\Models\SertifikatUser;
use App\Models\Ujian;
use Illuminate\Database\Seeder;

class FixPredikatSeeder extends Seeder
{
    public function run(): void
    {
        $materiId = env('MATERI_ID', 56);

        $list_ujian = Ujian::where('materi_id', $materiId)
                           ->where('jenis_ujian_id', 3)
                           ->get();

        foreach ($list_ujian as $ujian) {
            $nilai = $ujian->nilai ?? 0;
            $predikat = Ujian::tentukanPredikat($ujian->id, $nilai, 8);
            SertifikatUser::where('ujian_id', $ujian->id)->update(['predikat' => $predikat]);
        }
    }
}