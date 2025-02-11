<?php

namespace App\Filament\Resources\AngkatanResource\Pages;

use App\Filament\Resources\AngkatanResource;
use App\Models\JadwalUjian;
use App\Models\JadwalUjianSoal;
use App\Models\Kelas;
use App\Models\Materi;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAngkatan extends CreateRecord
{
    protected static string $resource = AngkatanResource::class;

    protected function afterCreate(): void
    { 

        Kelas::create([
            "angkatan_id" => $this->record->id,
            "nama_kelas" => $this->record->kode_angkatan . '-' . "Kelas 1"           

        ]);

        // Buat Jadwal Ujian Harian
        $materi = Materi::find($this->record->materi_id);

        $hari_ke = 1;
        $tanggal = $this->record->tanggal_mulai;

        foreach ($materi->pertemuan()->orderBy('pertemuan')->get() as $pertemuan) {

           $jadwal_ujian = JadwalUjian::create([
                "tanggal" => $tanggal,
                "type" => "Harian",
                "angkatan_id" => $this->record->id,
                "urutan" => $hari_ke                
           ]);

           // tambahkan soal ke jadwal ujian
           
           $list_soal = $materi->soal()->where('urutan', $hari_ke)->harian()->get();

           foreach ($list_soal as $soal) {

                JadwalUjianSoal::create([

                    "jadwal_ujian_id" => $jadwal_ujian->id,
                    "soal_id" => $soal->id
                ]);

           }           
           
           // cek apakah termasuk hari ujian atau bukan, jika tanggal ujian lewati 2 hari
           if(in_array($hari_ke , [5,10,15])){

             $penambah_hari = 3; // ditambah 2 hari

           }else{

            $penambah_hari = 1;

           }

           $tanggal = date('Y-m-d', strtotime('+'. $penambah_hari.' day' , strtotime($tanggal)));

           $hari_ke ++;

        }

        // Buat Jadwal Ujian Pekanan
        // ujian pekana mulai dari hari ke-6,7 ; 

        $ujian_pekanan = [];
        $tanggal_ujian = $this->record->tanggal_mulai;

        for ($i=1; $i <= 4; $i++) { 

            if($i == 1){

                $penambah = 5;

            }else{
                $penambah = 7;
            }
           
            $tanggal_ujian = date('Y-m-d', strtotime('+'.$penambah.' day' , strtotime($tanggal_ujian)));

            $jadwal_ujian = JadwalUjian::create([
                "tanggal" => $tanggal_ujian,
                "type" => "Pekanan",
                "angkatan_id" => $this->record->id,
                "urutan" => $i                
           ]);

           //tambahkan soal ke jadwal ujian

                      
           $list_soal_pekanan = $materi->soal()->where('urutan', $jadwal_ujian->urutan)->pekanan()->get();

           foreach ($list_soal_pekanan as $soal) {

                JadwalUjianSoal::create([

                    "jadwal_ujian_id" => $jadwal_ujian->id,
                    "soal_id" => $soal->id
                ]);

           }


        }


            // Buat jadwal ujian Akhir
            $jadwal_ujian_akhir = JadwalUjian::create([
                "tanggal" => $this->record->tanggal_ujian,
                "type" => "Akhir",
                "angkatan_id" => $this->record->id,
                "urutan" => 1               
           ]);

           $soal_ujian_akhir = $materi->soal()->akhir()->get();

           foreach ($soal_ujian_akhir as $soal) {

            JadwalUjianSoal::create([

                "jadwal_ujian_id" => $jadwal_ujian_akhir->id,
                "soal_id" => $soal->id
            ]);

       }







    }
}
