<?php

namespace App\Jobs;

use App\Models\Belajar;
use App\Models\JadwalUjian;
use App\Models\Setting;
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

    public const TEMPLATE_BELAJAR = 'telegram_template_belajar';

    public const TEMPLATE_UJIAN_PEKANAN = 'telegram_template_ujian_pekanan';

    public const TEMPLATE_UJIAN_AKHIR = 'telegram_template_ujian_akhir';

    /**
     * Placeholder yang bisa dipakai di template, beserta keterangannya.
     */
    public const PLACEHOLDERS_BELAJAR = [
        'nama' => 'Nama peserta',
        'tanggal' => 'Tanggal jadwal (mis. 31 Oct 2026)',
        'judul' => 'Judul materi hari ini',
        'materi' => 'Nama materi',
        'pertemuan' => 'Nomor pertemuan',
        'link' => 'Link BISI Online',
    ];

    public const PLACEHOLDERS_UJIAN = [
        'nama' => 'Nama peserta',
        'tanggal' => 'Tanggal ujian (mis. 31 Oct 2026)',
        'jenis_ujian' => 'Ujian Pekanan / Ujian Akhir',
        'materi' => 'Nama materi',
        'pekan' => 'Nomor pekan (khusus ujian pekanan)',
        'link' => 'Link BISI Online',
    ];

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
            $template = Setting::getTelegramReminderTemplate($this->templateKey());

            $sent = $template
                ? $client->sendMessage($this->user->telegram_chat_id, $this->renderTemplate($template))
                : false;

            // Pesan default dipakai jika template belum diatur, atau template ditolak Telegram (mis. tag HTML tidak valid).
            if (! $sent) {
                $client->sendMessage($this->user->telegram_chat_id, $this->buildMessage());
            }
        } catch (\Throwable $exception) {
            Log::warning('Gagal mengirim pengingat jadwal ke Telegram', [
                'user_id' => $this->user->id,
                'jadwal_type' => $this->jadwal instanceof JadwalUjian ? 'jadwal_ujian' : 'jadwal_belajar',
                'jadwal_id' => $this->jadwal->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function templateKey(): string
    {
        if ($this->jadwal instanceof JadwalUjian) {
            return $this->jadwal->type === 'Akhir' ? self::TEMPLATE_UJIAN_AKHIR : self::TEMPLATE_UJIAN_PEKANAN;
        }

        return self::TEMPLATE_BELAJAR;
    }

    private function placeholderValues(): array
    {
        $tanggal = date('d M Y', strtotime($this->jadwal->tanggal));
        $link = url('/');

        if ($this->jadwal instanceof JadwalUjian) {
            $isAkhir = $this->jadwal->type === 'Akhir';

            return [
                'nama' => $this->user->name,
                'tanggal' => $tanggal,
                'jenis_ujian' => $isAkhir ? 'Ujian Akhir' : 'Ujian Pekanan',
                'materi' => $this->jadwal->materi?->nama_materi,
                'pekan' => $isAkhir ? null : $this->jadwal->urutan,
                'link' => $link,
            ];
        }

        $materiDetail = $this->jadwal->materi_detail;

        return [
            'nama' => $this->user->name,
            'tanggal' => $tanggal,
            'judul' => $materiDetail?->judul,
            'materi' => $materiDetail?->materi?->nama_materi,
            'pertemuan' => $materiDetail?->pertemuan,
            'link' => $link,
        ];
    }

    /**
     * Ganti {placeholder} dengan data jadwal. Baris yang semua placeholder-nya kosong
     * (mis. "📖 {judul}" saat judul tidak ada) dibuang supaya pesan tetap rapi.
     */
    private function renderTemplate(string $template): string
    {
        $values = $this->placeholderValues();

        $replacements = [];
        foreach ($values as $key => $value) {
            $replacements['{' . $key . '}'] = htmlspecialchars((string) $value, ENT_NOQUOTES);
        }

        $lines = [];
        foreach (preg_split('/\R/', $template) as $line) {
            preg_match_all('/\{(\w+)\}/', $line, $matches);
            $used = array_intersect($matches[1], array_keys($values));

            if ($used && collect($used)->every(fn ($key) => blank($values[$key]))) {
                continue;
            }

            $lines[] = strtr($line, $replacements);
        }

        return trim(implode("\n", $lines));
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
