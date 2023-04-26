<?php


namespace App\Http\Services\Telegram;


use App\Http\Services\Telegram\RequestParams\GetUpdateParams;
use App\Http\Services\Telegram\RequestParams\IToArray;
use Illuminate\Support\Facades\Http;

class TelegramClient
{

    protected function makeRequest(string $method, IToArray $params)
    {
        $response = Http::get($this->getBasicUrl() . $method . '?' . http_build_query($params->toArray()));

        return $this->getResult($response->json());
    }

    private function getBasicUrl()
    {
        return 'https://' . $this->getDomain() . '/bot' . $this->getToken() . '/';
    }

    private function getDomain()
    {
        return config('telegram.TELEGRAM_DOMAIN');
    }

    private function getToken()
    {
        return config('telegram.TELEGRAM_TOKEN');
    }

    protected function getResult(array $json)
    {
        $this->isOk($json);
        return $json['result'];
    }

    private function isOk(array $json): void
    {
        if (!isset($json['ok']) || $json['ok'] !== true || !isset($json['result'])) {
            throw new \Exception('Invalid response API telegram');
        }
    }
}
