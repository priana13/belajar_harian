<?php

namespace App\Http\Controllers\Admin2;

use App\Admin\GenerateSchedule;
use App\Http\Controllers\Controller;
use App\Models\JadwalRoadmap;
use App\Models\JadwalUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    public function __invoke(Request $request, JadwalRoadmap $schedule)
    {
        DB::transaction(function () use ($request, $schedule) {
            $schedule = JadwalRoadmap::whereKey($schedule->id)->lockForUpdate()->firstOrFail();
            $scope = ['roadmap_id' => $schedule->roadmap_id, 'gelombang_id' => $schedule->gelombang_id];
            if ($schedule->jadwal_belajar()->exists() || JadwalUjian::where($scope)->where('materi_id', $schedule->materi_id)->exists()) {
                throw ValidationException::withMessages(['schedule' => 'Jadwal belajar atau ujian sudah dibuat untuk materi dan gelombang ini.']);
            }
            if (! $schedule->tanggal_ujian) {
                throw ValidationException::withMessages(['schedule' => 'Lengkapi tanggal ujian terlebih dahulu.']);
            }
            app(GenerateSchedule::class)->create($schedule->materi, $schedule->tanggal_mulai, $schedule->tanggal_ujian, $request->user()->id, $scope, $schedule->id);
        });

        return back()->with('success', 'Jadwal belajar dan ujian berhasil dibuat.');
    }
}
