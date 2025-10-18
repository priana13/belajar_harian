<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Materi;
use App\Models\Sertifikat;
use App\Models\AngkatanUser;
use App\Models\SertifikatUser;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SertifikatUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate Sertifikat User
        // 1. Cari User yang memiliki sertifikat
        // 2. Untuk setiap user, buat entri di tabel SertifikatUser

        $angkatan_users = AngkatanUser::lulus()->get();

        $jumlah = 0;

        foreach ($angkatan_users as $row) {         

            $user = User::find($row->user_id);
            $materi = Materi::find($row->angkatan->materi_id);
            $sertifikat = Sertifikat::find($row->angkatan->sertifikat_id);
         
            // Cek apakah user sudah memiliki sertifikat untuk materi ini
            $existing_sertifikat = SertifikatUser::where('user_id', $user->id)
                ->where('materi_id', $materi->id)
                ->first();

            if (!$existing_sertifikat) {
                // Buat sertifikat baru
                SertifikatUser::create([
                    'user_id' => $user->id,
                    'sertifikat_id' => $sertifikat->id, // Asumsikan ada sertifikat default dengan ID 1
                    'materi_id' => $materi->id,
                    'predikat' => $row->predikat, // Contoh predikat
                    'tanggal' => $row->angkatan->tanggal_ujian,
                    'code' => ($row->kode_ujian) ? $row->code :uniqid(),
                    'ttd_image' => 'img/ttd2.png',

                    'ttd_nama' => 'Irfan Bahar Nurdin, S.Th.I, M.M.,',
                    'ttd_jabatan' => 'Manager',
                ]);

                $jumlah++;
            }
        }

        $this->command->info('Selesai membuat sertifikat untuk ' . $jumlah . ' peserta.');




        // Generate Daftar Nilai

        

    }
}
