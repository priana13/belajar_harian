<?php

namespace Database\Seeders\Helper;

use App\Models\User;
use App\Models\Angkatan;
use App\Models\Gelombang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InsertGelombangUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list_gelombang = Gelombang::get();


        foreach ($list_gelombang as $key => $gelombang) {            

            $angkatan = Angkatan::where('gelombang_id' , $gelombang->id)->first();

            $list_peserta = $angkatan->peserta;

            foreach ($list_peserta as $key => $peserta) {

                if($peserta->gelombang_id == 0){
                    $peserta->gelombang_id = $gelombang->id;
                    $peserta->save();
                }
               

            }

        }


        // untuk peserta yang masih nol gelombang id nya

        $list_peserta = User::where('gelombang_id' , 0)->whereHas('angkatan')->get();

        foreach ($list_peserta as $key => $peserta) {
           
            $angkatan = $peserta->angkatan[0];

            dd($angkatan->id);
           

        }

    }
}
