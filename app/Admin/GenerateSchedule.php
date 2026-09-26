<?php

namespace App\Admin;

use App\Models\Belajar;
use App\Models\JadwalUjian;
use App\Models\Materi;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GenerateSchedule
{
    public function create(Materi $materi, string $start, string $end, int $adminId, array $scope, ?int $jadwalRoadmapId = null): void
    {
        $days = [1 => [0], 2 => [0, 3], 3 => [0, 2, 4], 4 => [0, 1, 2, 3], 5 => [0, 1, 2, 3, 4], 6 => [0, 1, 2, 3, 4, 5]];
        $perWeek = (int) $materi->materi_per_pekan;
        if (! isset($days[$perWeek]) || ! $materi->pertemuan()->exists()) {
            throw ValidationException::withMessages(['materi_id' => 'Materi harus memiliki pertemuan dan jumlah materi per pekan antara 1–6.']);
        }
        $monday = Carbon::parse($start)->startOfWeek();
        $exam = function (string $type, int $order, $date) use ($materi, $scope) {
            $schedule = JadwalUjian::create([...$scope, 'materi_id' => $materi->id, 'type' => $type, 'urutan' => $order, 'tanggal' => $date]);
            $questions = $materi->soal()->where('jenis_ujian_id', ['Harian' => 1, 'Pekanan' => 2, 'Akhir' => 3][$type]);
            if ($type !== 'Akhir') {
                $questions->where('urutan', $order);
            }
            $schedule->soal()->sync($questions->pluck('id'));
        };
        foreach ($materi->pertemuan()->orderBy('pertemuan')->get() as $i => $meeting) {
            $date = $monday->copy()->addWeeks(intdiv($i, $perWeek))->addDays($days[$perWeek][$i % $perWeek]);
            $exam('Harian', $i + 1, $date);
            Belajar::create([...$scope, 'jadwal_roadmap_id' => $jadwalRoadmapId, 'tanggal' => $date, 'materi_detail_id' => $meeting->id, 'user_id' => $adminId, 'code' => (string) Str::uuid()]);
        }
        for ($week = 1; $week <= 4; $week++) {
            $exam('Pekanan', $week, Carbon::parse($start)->addDays(($jadwalRoadmapId ? 5 : 6) + ($week - 1) * 7));
        }
        $exam('Akhir', 1, $end);
    }
}
