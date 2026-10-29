<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramBotClient
{
    public function __construct(private readonly ?string $token)
    {
    }

    public function sendMessage(string $chatId, string $text): bool
    {
        if (! $this->token) {
            return false;
        }

        $response = $this->request('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ]);

        return $response->successful();
    }

    public function setWebhook(string $url, ?string $secret = null): bool
    {
        if (! $this->token) {
            return false;
        }

        $response = $this->request('setWebhook', array_filter([
            'url' => $url,
            'secret_token' => $secret,
        ]));

        return $response->successful();
    }

    private function request(string $method, array $params)
    {
        return Http::baseUrl("https://api.telegram.org/bot{$this->token}/")
            ->timeout(10)
            ->retry(2, 500, fn ($exception) => $exception instanceof \Illuminate\Http\Client\ConnectionException, throw: false)
            ->post($method, $params);
    }
}
