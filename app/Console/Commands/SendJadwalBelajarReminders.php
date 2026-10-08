<?php

namespace App\Console\Commands;

use App\Jobs\SendTelegramReminderJob;
use App\Models\Belajar;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Command;

class SendJadwalBelajarReminders extends Command
{
    protected $signature = 'reminder:jadwal-belajar';

    protected $description = 'Kirim pengingat Telegram untuk jadwal belajar hari ini';

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

        $jadwalHariIni = Belajar::hariIni()->whereNull('reminder_sent_at')->get();

        $dispatched = 0;

        foreach ($jadwalHariIni as $jadwal) {
            $penerima = $this->resolveRecipients($jadwal);

            foreach ($penerima as $user) {
                SendTelegramReminderJob::dispatch($user, $jadwal);
                $dispatched++;
            }

            $jadwal->update(['reminder_sent_at' => now()]);
        }

        $this->info("{$dispatched} pengingat dijadwalkan untuk dikirim.");

        return self::SUCCESS;
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
}
