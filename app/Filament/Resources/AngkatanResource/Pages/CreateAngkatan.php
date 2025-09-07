<?php

namespace App\Filament\Resources\AngkatanResource\Pages;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Belajar;
use App\Models\Angkatan;
use App\Models\JadwalUjian;
use Filament\Pages\Actions;
use App\Models\JadwalUjianSoal;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\AngkatanResource;

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
        $materi_per_pekan = $materi->materi_per_pekan;

        // Pastikan tanggal mulai adalah hari Senin
        $dayOfWeek = date('N', strtotime($tanggal)); // 1 = Senin, 7 = Minggu
        if ($dayOfWeek != 1) {
            // Jika bukan Senin, mundur ke Senin sebelumnya
            $daysToSubtract = $dayOfWeek - 1;
            $tanggal = date('Y-m-d', strtotime('-' . $daysToSubtract . ' days', strtotime($tanggal)));
        }

        foreach ($materi->pertemuan()->orderBy('pertemuan')->get() as $pertemuan) {

            $jadwal_ujian = JadwalUjian::create([
                "tanggal" => $tanggal,
                "type" => "Harian",
                "angkatan_id" => $this->record->id,
                "urutan" => $hari_ke                
            ]);

            // tambahkan jadwal belajar
            Belajar::create([
                "tanggal" => $tanggal,
                "materi_detail_id" => $pertemuan->id,
                "user_id" => auth()->user()->id,
                "angkatan_id" => $this->record->id,
                "code" => uniqid()    
            ]);

            // tambahkan soal ke jadwal ujian
            $list_soal = $materi->soal()->where('urutan', $hari_ke)->harian()->get();

            foreach ($list_soal as $soal) {
                JadwalUjianSoal::create([
                    "jadwal_ujian_id" => $jadwal_ujian->id,
                    "soal_id" => $soal->id
                ]);
            }           
            
            // Tentukan tanggal berikutnya berdasarkan pola materi per pekan
            $posisi_dalam_pekan = (($hari_ke - 1) % $materi_per_pekan) + 1;
            
            if ($materi_per_pekan == 2) {
                // Pola: Senin dan Kamis
                if ($posisi_dalam_pekan == 1) {
                    // Dari Senin ke Kamis (tambah 3 hari)
                    $penambah_hari = 3;
                } else {
                    // Dari Kamis ke Senin minggu berikutnya (tambah 4 hari)
                    $penambah_hari = 4;
                }
            } elseif ($materi_per_pekan == 3) {
                // Pola: Senin, Rabu, Jumat
                if ($posisi_dalam_pekan == 1) {
                    // Dari Senin ke Rabu (tambah 2 hari)
                    $penambah_hari = 2;
                } elseif ($posisi_dalam_pekan == 2) {
                    // Dari Rabu ke Jumat (tambah 2 hari)
                    $penambah_hari = 2;
                } else {
                    // Dari Jumat ke Senin minggu berikutnya (tambah 3 hari)
                    $penambah_hari = 3;
                }
            } else {
                // Fallback untuk kasus lain (setiap hari)
                $penambah_hari = 1;
            }

            $tanggal = date('Y-m-d', strtotime('+' . $penambah_hari . ' day', strtotime($tanggal)));
            $hari_ke++;
        }

        // Buat Jadwal Ujian Pekanan
        // ujian pekanan mulai dari hari ke-6,7 ; 

        $ujian_pekanan = [];
        $tanggal_ujian = $this->record->tanggal_mulai;

        for ($i=1; $i <= 4; $i++) { 

            if($i == 1){

                $penambah = 6;

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

         // Daftarkan Para Peserta

        $list_peserta = User::where('gelombang_id', $this->record->gelombang_id)->get();      

        // daftarkan peserta ke angkatan

        foreach ($list_peserta as $peserta) {

            Angkatan::daftarkanPeserta($this->record , $peserta->id);
        }

       }


    }



}
