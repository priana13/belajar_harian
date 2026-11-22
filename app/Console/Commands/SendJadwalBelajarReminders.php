<?php

namespace App\Console\Commands;

use App\Jobs\SendTelegramReminderJob;
use App\Jobs\JobKirimEmail;
use App\Models\Belajar;
use App\Models\JadwalRoadmap;
use App\Models\JadwalUjian;
use App\Models\LoginLink;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SendJadwalBelajarReminders extends Command
{
    protected $signature = 'reminder:jadwal-belajar';

    protected $description = 'Kirim pengingat Telegram dan email untuk jadwal belajar, ujian pekanan, dan ujian akhir hari ini';

    public function handle(): int
    {
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
                $dispatched += $this->dispatchReminder($user, $jadwal);
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
                $dispatched += $this->dispatchReminder($user, $ujian);
            }

            $ujian->update(['reminder_sent_at' => now()]);
        }

        return $dispatched;
    }

    private function resolveRecipients(Belajar $jadwal)
    {
        if ($jadwal->gelombang_id) {
            return User::where('gelombang_id', $jadwal->gelombang_id)
                ->get();
        }

        if ($jadwal->jadwal_roadmap_id && $jadwal->jadwal_roadmap?->group) {
            return $jadwal->jadwal_roadmap->group->users()
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
                    ->get()
            );
        }

        foreach ($jadwalRoadmapBulanIni->whereNotNull('group_id') as $jadwalRoadmap) {
            if ($jadwalRoadmap->group) {
                $penerima = $penerima->merge(
                    $jadwalRoadmap->group->users()
                        ->get()
                );
            }
        }

        return $penerima->unique('id')->values();
    }

    private function dispatchReminder(User $user, Belajar|JadwalUjian $jadwal): int
    {
        $dispatched = 0;

        if (Setting::isTelegramReminderActive() && $user->telegram_chat_id) {
            SendTelegramReminderJob::dispatch($user, $jadwal);
            $dispatched++;
        }

        if ($user->email) {
            $isBelajar = $jadwal instanceof Belajar;
            $materiDetail = $isBelajar ? $jadwal->materi_detail : null;
            $destination = $isBelajar && $jadwal->code
                ? route('link_materi', $jadwal->code)
                : route('home');
            $loginLink = $user->loginLinks()->create([
                'token' => \Illuminate\Support\Str::random(48),
                'expires_at' => now()->addDay(),
            ]);
            $link = route('login-link', $loginLink->token, ['redirect' => parse_url($destination, PHP_URL_PATH)]);
            $tanggal = Carbon::parse($jadwal->tanggal)->format('d M Y');
            $subject = $isBelajar ? 'Pengingat materi belajar hari ini' : 'Pengingat ujian hari ini';
            $lines = [
                $isBelajar ? 'Hari ini ada materi belajar untuk Anda.' : 'Hari ini ada ujian untuk Anda.',
                '',
            ];

            if ($isBelajar) {
                if ($materiDetail?->judul) {
                    $lines[] = 'Materi: ' . $materiDetail->judul;
                }
                if ($materiDetail?->materi?->nama_materi) {
                    $lines[] = 'Nama materi: ' . $materiDetail->materi->nama_materi;
                }
                if ($materiDetail?->pertemuan) {
                    $lines[] = 'Pertemuan ke-' . $materiDetail->pertemuan;
                }
                $lines[] = 'Buka materi: ' . $link;
            } else {
                $lines[] = ($jadwal->type === 'Akhir' ? 'Ujian Akhir' : 'Ujian Pekanan') . ' — ' . ($jadwal->materi?->nama_materi ?? '');
                $lines[] = 'Buka BISI Online dan login untuk mengerjakan: ' . $link;
            }

            $lines[] = 'Tanggal: ' . $tanggal;
            JobKirimEmail::dispatch($user, $subject, implode("\n", $lines));
            $dispatched++;
        }

        return $dispatched;
    }
}
