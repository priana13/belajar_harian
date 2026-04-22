<?php

namespace Database\Seeders;

use App\Http\Livewire\Kuis\Sertifikat as KuisSertifikat;
use App\Models\AngkatanUser;
use App\Models\Sertifikat;
use App\Models\SertifikatUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class addUjianIdToSertifikatUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list_sertifikat_user = \App\Models\SertifikatUser::take(1)->get();

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

        // cek berdasarkan ujian dengan nilai cukup atau cumloude tapi belum memiliki sertifikat 

        $sertifikat_user_ids = SertifikatUser::pluck('ujian_id');

        $list_ujian = \App\Models\Ujian::where('predikat', '<>', "Kurang")
            ->where('jenis_ujian_id', 3)
            ->whereNotIn('id', $sertifikat_user_ids)
            ->get();

       
 
        $sertifikat_user = null;
        $ujian = null;

        $total = 0;

        foreach($list_ujian as $ujian){        
           
            $sertifikat_user = \App\Models\SertifikatUser::where('user_id', $ujian->user_id)
                ->where('materi_id', $ujian->materi_id)
                ->first();

            $sertifikat_id = ($ujian->sertifikat_id) ? $ujian->sertifikat_id : Sertifikat::first()->id;
         

            if(!$sertifikat_user){            
                  

                 $sertifikat_user = SertifikatUser::create([
                    'user_id' => $ujian->user_id,
                    'sertifikat_id' => $sertifikat_id, // Asumsikan ada sertifikat default dengan ID 1
                    'materi_id' => $ujian->materi_id,
                    'predikat' => $ujian->predikat, // Contoh predikat
                    'tanggal' => date("Y-m-d" , strtotime($ujian->created_at)),
                    'code' => uniqid(),
                    'ttd_image' => 'img/ttd2.png',

                    'ttd_nama' => 'Irfan Bahar Nurdin, S.Th.I, M.M.,',
                    'ttd_jabatan' => 'Manager',
                ]);

                $total++;

            }

            $sertifikat_user->ujian_id = $ujian->id;
            $sertifikat_user->save();

        }

        $this->command->info("Data dari Ujian: " . $list_ujian->count());
        $this->command->info("Total Sertifikat baru: $total");
        $this->command->info("Sertifikat User with Ujian ID: " . $list_sertifikat_user->whereNotNull('ujian_id')->count());
    }
}
