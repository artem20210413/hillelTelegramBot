<?php


namespace App\Http\Services\Telegram;


use App\Http\Services\Telegram\RequestParams\GetUpdateParams;
use App\Http\Services\Telegram\RequestParams\TextMessage;

class TelegramReaderService extends TelegramClient
{
    public function __construct(public TelegramRespondService $respondService)
    {
    }

    public function getUpdates(int $offset = 0):int
    {
        $messages = $this->makeRequest(TelegramApiMethodDictionary::METHOD_GET_UPDATE, GetUpdateParams::create($offset+1));

        if (!$messages) {
            return 0;
        }
        foreach ($messages as $message) {
            $this->handleMessage($message);
        }
        return $message['update_id'];

    }

    private function handleMessage(array $message)
    {
        $tMessage = new TextMessage();
        $tMessage->setChatId($message['message']['chat']['id']);
        $tMessage->setReplyToMessageId($message['message']['message_id']);
        $tMessage->setText('textMessage');

        $this->respondService->sendMessages($tMessage);
    }


}
