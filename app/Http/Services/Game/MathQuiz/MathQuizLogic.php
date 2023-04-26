<?php


namespace App\Http\Services\Game\MathQuiz;


use App\Http\Services\Telegram\MessageDto;

class MathQuizLogic extends MathQuizExample
{

    public function __construct(public MessageDto $messageDto)
    {
        parent::__construct();
    }


    //узнать что пришло getScore или число(ответ)


    public function responseMessage(): string
    {
        $text = $this->messageDto->getText();
        if ($text === '/start') {

            return $this->start($this->messageDto);
        } elseif (is_numeric($text)) {

            return $this->calculationExample($this->messageDto);
        } else if ($text === '/getScore') {

            return $this->getScore($this->messageDto);
        } else {

            return 'Упс... Невідома команда.';
        }
    }




}
