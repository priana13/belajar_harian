<?php

namespace Database\Seeders;

use App\Models\AngkatanUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class addUjianIdToSertifikatUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list_sertifikat_user = \App\Models\SertifikatUser::all();

        $total = 0;

        foreach($list_sertifikat_user as $sertifikat_user){

            $ujian = \App\Models\Ujian::where('user_id', $sertifikat_user->user_id)
                ->where('materi_id', $sertifikat_user->materi_id)
                ->whereDate('created_at', date('Y-m-d', strtotime($sertifikat_user->tanggal)))
                ->first();  
                
            if(!$ujian){

                $angkatan_user = AngkatanUser::where('user_id', $sertifikat_user->user_id)
                    ->where("predikat" , "!=" , 'Kurang')
                    ->get();

                foreach($angkatan_user as $row){   

                    $start_date =   date('Y-m-d', strtotime($row->angkatan->tanggal_ujian));
                    // tambah 1 hari                   
                    $end_date = date('Y-m-d', strtotime('+2 days', strtotime($row->angkatan->tanggal_ujian)));

                    $ujian = \App\Models\Ujian::where('user_id', $sertifikat_user->user_id)
                        ->where('materi_id', $sertifikat_user->materi_id)
                        ->whereDate('created_at', ">=" , $start_date)
                        ->whereDate('created_at', "<" , $end_date)
                        ->first();                    

                    if($ujian){
                        break;
                    }
                }


            }     

            if($ujian){
                $sertifikat_user->update([
                    'ujian_id' => $ujian->id
                ]);
            }

            $total++;
        }

        $this->command->info("Total Sertifikat User: $total");
        $this->command->info("Sertifikat User with Ujian ID: " . $list_sertifikat_user->whereNotNull('ujian_id')->count());

        // end for code
    }
}
