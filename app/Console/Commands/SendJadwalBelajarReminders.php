<?php

namespace App\Console\Commands;

use App\Jobs\SendTelegramReminderJob;
use App\Models\Belajar;
use App\Models\JadwalRoadmap;
use App\Models\JadwalUjian;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SendJadwalBelajarReminders extends Command
{
    protected $signature = 'reminder:jadwal-belajar';

    protected $description = 'Kirim pengingat Telegram untuk jadwal belajar, ujian pekanan, dan ujian akhir hari ini';

    public function handle(): int
    {
        if (! Setting::isTelegramReminderActive()) {
            $this->info('Reminder Telegram sedang dimatikan lewat Setting.');

            return self::SUCCESS;
        }

        if (now()->format('H:i') < Setting::getTelegramReminderJam()) {
            $this->info('Belum masuk jam pengingat.');

            return self::SUCCESS;
        }

        $dispatched = $this->kirimPengingatBelajar() + $this->kirimPengingatUjian();

        $this->info("{$dispatched} pengingat dijadwalkan untuk dikirim.");

        return self::SUCCESS;
    }

    private function kirimPengingatBelajar(): int
    {
        $jadwalHariIni = Belajar::hariIni()->whereNull('reminder_sent_at')->get();

        $dispatched = 0;

        foreach ($jadwalHariIni as $jadwal) {
            foreach ($this->resolveRecipients($jadwal) as $user) {
                SendTelegramReminderJob::dispatch($user, $jadwal);
                $dispatched++;
            }

            $jadwal->update(['reminder_sent_at' => now()]);
        }

        return $dispatched;
    }

    /**
     * Ujian harian tidak diingatkan terpisah karena sudah tercakup pengingat jadwal belajar.
     */
    private function kirimPengingatUjian(): int
    {
        $ujianHariIni = JadwalUjian::hariIni()
            ->whereIn('type', ['Pekanan', 'Akhir'])
            ->whereNull('reminder_sent_at')
            ->get();

        $dispatched = 0;

        foreach ($ujianHariIni as $ujian) {
            foreach ($this->resolveUjianRecipients($ujian) as $user) {
                SendTelegramReminderJob::dispatch($user, $ujian);
                $dispatched++;
            }

            $ujian->update(['reminder_sent_at' => now()]);
        }

        return $dispatched;
    }

    private function resolveRecipients(Belajar $jadwal)
    {
        if ($jadwal->gelombang_id) {
            return User::where('gelombang_id', $jadwal->gelombang_id)
                ->whereNotNull('telegram_chat_id')
                ->get();
        }

        if ($jadwal->jadwal_roadmap_id && $jadwal->jadwal_roadmap?->group) {
            return $jadwal->jadwal_roadmap->group->users()
                ->whereNotNull('telegram_chat_id')
                ->get();
        }

        return collect();
    }

    /**
     * Penerima mengikuti logika tampilan ujian di HomeNew:
     * - Jalur standar: user di gelombang ujian, selama gelombang itu memakai roadmap ujian di bulan tersebut.
     * - Jalur khusus: user di group yang memakai roadmap ujian di bulan tersebut.
     */
    private function resolveUjianRecipients(JadwalUjian $ujian): Collection
    {
        if (! $ujian->roadmap_id) {
            return collect();
        }

        $tanggal = Carbon::parse($ujian->tanggal);

        $jadwalRoadmapBulanIni = JadwalRoadmap::where('roadmap_id', $ujian->roadmap_id)
            ->whereMonth('tanggal_mulai', $tanggal->month)
            ->whereYear('tanggal_mulai', $tanggal->year)
            ->get();

        $penerima = collect();

        if ($ujian->gelombang_id && $jadwalRoadmapBulanIni->contains('gelombang_id', $ujian->gelombang_id)) {
            $penerima = $penerima->merge(
                User::where('gelombang_id', $ujian->gelombang_id)
                    ->whereNotNull('telegram_chat_id')
                    ->get()
            );
        }

        foreach ($jadwalRoadmapBulanIni->whereNotNull('group_id') as $jadwalRoadmap) {
            if ($jadwalRoadmap->group) {
                $penerima = $penerima->merge(
                    $jadwalRoadmap->group->users()
                        ->whereNotNull('telegram_chat_id')
                        ->get()
                );
            }
        }

        return $penerima->unique('id')->values();
    }
}
