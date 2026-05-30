<?php

namespace Database\Seeders;

use App\Models\Ujian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FixPredikatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $list_ujian = Ujian::where('materi_id' , 56)->where('jenis_ujian_id' , 3)->get();
   
        foreach($list_ujian as $ujian){

            $nilai = $ujian->nilai ?? 0;

            Ujian::tentukanPredikat($ujian->id, $nilai , 8);
        }
    }
}
