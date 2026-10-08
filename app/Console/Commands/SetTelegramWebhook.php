<?php

namespace App\Console\Commands;

use App\Services\TelegramBotClient;
use Illuminate\Console\Command;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {url?}';

    protected $description = 'Daftarkan webhook bot Telegram ke Telegram Bot API';

    public function handle(TelegramBotClient $client): int
    {
        $url = $this->argument('url') ?? route('webhooks.telegram');

        $ok = $client->setWebhook($url, config('services.telegram.webhook_secret'));

        if (! $ok) {
            $this->error("Gagal mendaftarkan webhook ke {$url}. Pastikan TELEGRAM_BOT_TOKEN benar dan url bisa diakses publik (HTTPS).");

            return self::FAILURE;
        }

        $this->info("Webhook terdaftar: {$url}");

        return self::SUCCESS;
    }
}
