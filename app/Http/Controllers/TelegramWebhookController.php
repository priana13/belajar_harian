<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TelegramBotClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TelegramWebhookController extends Controller
{
    public function __invoke(Request $request, TelegramBotClient $client): JsonResponse
    {
        $secret = (string) config('services.telegram.webhook_secret');

        abort_if(
            $secret === '' || ! hash_equals($secret, (string) $request->header('X-Telegram-Bot-Api-Secret-Token')),
            403
        );

        $chatId = $request->input('message.chat.id');
        $text = trim((string) $request->input('message.text'));

        if ($chatId === null || ! str_starts_with($text, '/start')) {
            return response()->json(['ok' => true]);
        }

        $code = trim(substr($text, strlen('/start')));

        $user = $code === '' ? null : User::where('telegram_link_code', $code)->first();

        if (! $user) {
            $this->reply($client, $chatId, 'Kode tidak valid. Buka halaman depan BISI Online dan klik tombol "Hubungkan Telegram" untuk mendapatkan link yang baru.');

            return response()->json(['ok' => true]);
        }

        $user->forceFill([
            'telegram_chat_id' => (string) $chatId,
            'telegram_link_code' => null,
            'telegram_linked_at' => now(),
        ])->save();

        $this->reply($client, $chatId, "Halo {$user->name}, akun Telegram Anda berhasil terhubung ✅\n\nAnda akan menerima pengingat setiap ada jadwal belajar hari itu.");

        return response()->json(['ok' => true]);
    }

    private function reply(TelegramBotClient $client, int|string $chatId, string $text): void
    {
        try {
            $client->sendMessage((string) $chatId, $text);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
