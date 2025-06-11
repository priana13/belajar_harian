<?php

namespace Database\Seeders;

use App\Models\MateriDetail;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Storage;

class PerbaikiFilderAudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materi_detail = MateriDetail::all();



        foreach ($materi_detail as $key => $value) {

    

            // pindahkan $value->multimedia_url ke dalam $dir dan sesuaikan juga data di database

            if($value->multimedia_url == null){
                continue;
            }

            $exis = Storage::exists( 'public/' . $value->multimedia_url);

            if($exis){

                $materi = $value->materi;
                $pertemuan = $value->pertemuan;

                $dir = $materi->kode_materi;

                $path ='/' . $dir . '/' . $pertemuan . '-' . $value->multimedia_url;

                 Storage::disk('public')->move($value->multimedia_url, $path);

                $value->multimedia_url = $path;
                $value->save();

            }

          

        }
    }
}
