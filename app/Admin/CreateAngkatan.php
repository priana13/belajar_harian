<?php

namespace App\Admin;

use App\Models\Angkatan;
use App\Models\AngkatanUser;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Str;

class CreateAngkatan
{
    // Called inside the resource transaction: no partially created angkatan.
    public function handle(Angkatan $angkatan, int $adminId): void
    {
        $kelas = Kelas::create(['angkatan_id' => $angkatan->id, 'nama_kelas' => $angkatan->kode_angkatan.'-Kelas 1']);
        app(GenerateSchedule::class)->create($angkatan->materi, $angkatan->tanggal_mulai, $angkatan->tanggal_ujian, $adminId, ['angkatan_id' => $angkatan->id]);
        User::where('gelombang_id', $angkatan->gelombang_id)->each(function ($user) use ($angkatan, $kelas) {
            AngkatanUser::firstOrCreate(['angkatan_id' => $angkatan->id, 'user_id' => $user->id], ['kelas_id' => $kelas->id, 'kode_angkatan' => $angkatan->kode_angkatan.$user->id, 'code' => (string) Str::uuid()]);
        });
    }
}
