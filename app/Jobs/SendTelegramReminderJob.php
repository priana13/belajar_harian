<?php

namespace App\Jobs;

use App\Models\Belajar;
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
        public Belajar $jadwal,
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
            Log::warning('Gagal mengirim pengingat jadwal belajar ke Telegram', [
                'user_id' => $this->user->id,
                'jadwal_belajar_id' => $this->jadwal->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function buildMessage(): string
    {
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
}
