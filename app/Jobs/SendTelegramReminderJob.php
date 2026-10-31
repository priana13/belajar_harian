<?php

namespace App\Jobs;

use App\Models\Belajar;
use App\Models\JadwalUjian;
use App\Models\User;
use App\Services\TelegramBotClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTelegramReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Belajar|JadwalUjian $jadwal,
    ) {
    }

    public function handle(TelegramBotClient $client): void
    {
        if (! $this->user->telegram_chat_id) {
            return;
        }

        try {
            $client->sendMessage($this->user->telegram_chat_id, $this->buildMessage());
        } catch (\Throwable $exception) {
            Log::warning('Gagal mengirim pengingat jadwal ke Telegram', [
                'user_id' => $this->user->id,
                'jadwal_type' => $this->jadwal instanceof JadwalUjian ? 'jadwal_ujian' : 'jadwal_belajar',
                'jadwal_id' => $this->jadwal->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function buildMessage(): string
    {
        if ($this->jadwal instanceof JadwalUjian) {
            return $this->buildUjianMessage();
        }

        $materiDetail = $this->jadwal->materi_detail;
        $namaMateri = $materiDetail?->materi?->nama_materi;
        $judul = $materiDetail?->judul;
        $pertemuan = $materiDetail?->pertemuan;
        $tanggal = date('d M Y', strtotime($this->jadwal->tanggal));
        $url = url('/');

        $lines = [
            '📚 <b>Pengingat Jadwal Belajar</b>',
            '',
            "Halo {$this->user->name}, hari ini ({$tanggal}) ada materi belajar untuk Anda:",
            '',
        ];

        if ($judul) {
            $lines[] = "📖 {$judul}";
        }

        if ($namaMateri) {
            $lines[] = $namaMateri;
        }

        if ($pertemuan) {
            $lines[] = "Pertemuan ke-{$pertemuan}";
        }

        $lines[] = '';
        $lines[] = "Buka BISI Online untuk mulai belajar: {$url}";

        return implode("\n", $lines);
    }

    private function buildUjianMessage(): string
    {
        $isAkhir = $this->jadwal->type === 'Akhir';
        $namaUjian = $isAkhir ? 'Ujian Akhir' : 'Ujian Pekanan';
        $namaMateri = $this->jadwal->materi?->nama_materi;
        $tanggal = date('d M Y', strtotime($this->jadwal->tanggal));
        $url = url('/');

        $lines = [
            ($isAkhir ? '🎓' : '📝') . " <b>Pengingat {$namaUjian}</b>",
            '',
            "Halo {$this->user->name}, hari ini ({$tanggal}) ada {$namaUjian} untuk Anda:",
            '',
        ];

        if ($namaMateri) {
            $lines[] = "📖 {$namaMateri}";
        }

        if (! $isAkhir && $this->jadwal->urutan) {
            $lines[] = "Pekan ke-{$this->jadwal->urutan}";
        }

        $lines[] = '';
        $lines[] = "Buka BISI Online untuk mengerjakan ujian: {$url}";

        return implode("\n", $lines);
    }
}
