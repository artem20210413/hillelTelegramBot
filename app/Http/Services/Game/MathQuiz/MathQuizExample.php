<?php


namespace App\Http\Services\Game\MathQuiz;


use App\Http\Services\Telegram\MessageDto;
use Illuminate\Support\Facades\Cache;
use function Ramsey\Collection\element;

class MathQuizExample
{

    private int $firstNumber;
    private int $secondNumber;
    private string $stringExample;

    private ResultExample $resultExample;

    public function __construct()
    {
        $this->generationExample();
    }

    public function generationExample()
    {
        $this->generationNumbers()
            ->setStringExample()
            ->setResultExample();
    }

    /**
     * @param int $firstNumber
     */
    private function generationNumbers(): static
    {
        $from = config('games.mathQuiz.randomGenerationFrom');
        $before = config('games.mathQuiz.randomGenerationBefore');
        $this->firstNumber = random_int($from, $before);
        $this->secondNumber = random_int($from, $before);

        return $this;
    }

    /**
     * @param string $example
     */
    private function setStringExample(): static
    {
        $this->stringExample = ((string)$this->getFirstNumber()) . '*' . ((string)$this->getSecondNumber());

        return $this;
    }

    /**
     * @param ResultExample $resultExample
     */
    private function setResultExample(): static
    {
        $this->resultExample = new ResultExample($this);

        return $this;
    }

    /**
     * @return int
     */
    public function getFirstNumber(): int
    {
        return $this->firstNumber;
    }

    /**
     * @return int
     */
    public function getSecondNumber(): int
    {
        return $this->secondNumber;
    }

    /**
     * @return string
     */
    public function getStringExample(): string
    {
        return $this->stringExample;
    }

    /**
     * @return ResultExample
     */
    public function getResultExample(): ResultExample
    {
        return $this->resultExample;
    }


    protected function saveExampleToCache(int $user_id, int $activeResult, int $score): void
    {
        $time = config('games.mathQuiz.timeCache');
        $prefix = config('games.mathQuiz.prefixCacheKey');
        $kay = $prefix . $user_id;
        $value = [
            config('games.mathQuiz.cacheNameActiveResult') => $activeResult,
            config('games.mathQuiz.cacheNameScore') => $score
        ];

        Cache::put($kay, $value, $time);
    }

    protected function checkResultInCache(int $user_id, int $result): ?bool
    {
        $prefix = config('games.mathQuiz.prefixCacheKey');
        $kay = $prefix . $user_id;
        $value = Cache::get($kay);
        $cacheNameActiveResult = config('games.mathQuiz.cacheNameActiveResult');

        return !$value ? null
            : $value[$cacheNameActiveResult] === $result;
    }

    protected function getScore(MessageDto $messageDto): string
    {
        $prefix = config('games.mathQuiz.prefixCacheKey');
        $userId = $messageDto->getUserDto()->getId();
        $name = $messageDto->getUserDto()->getFirstName();
        $timeMin = config('games.mathQuiz.timeCache') / 60;
        $kay = $prefix . $userId;
        $value = Cache::get($kay);
        $cacheNameScore = config('games.mathQuiz.cacheNameScore');

        if (!$value) {
            return "$name у Вас немає зіграних ігор за останні $timeMin хвилин.";
        }

        $score = $value[$cacheNameScore];

        return "$name Ваш рахунок $score од.";
    }


    private function addNewExample(MessageDto $messageDto, ?int $score = null): string
    {
        $userId = $messageDto->getUserDto()->getId();
        $activeResult = $this->getResultExample()->getResultCorrect();
        $this->saveExampleToCache($userId, $activeResult, (int)$score);

        return $this->getStringExample();
    }

    protected function calculationExample(MessageDto $messageDto)
    {

        $prefixCacheKey = config('games.mathQuiz.prefixCacheKey');
        $userId = $messageDto->getUserDto()->getId();
        $requestNumber = $messageDto->getText();
        $kay = $prefixCacheKey . $userId;
        $value = Cache::get($kay);
        $cacheNameActiveResult = config('games.mathQuiz.cacheNameActiveResult');
        $cacheNameScore = config('games.mathQuiz.cacheNameScore');
        $score = $value[$cacheNameScore] ?? null;
        $activeResult = $value[$cacheNameActiveResult] ?? null;

        if ((int)$requestNumber === (int)$activeResult) {
            $prefixResponseMessage = 'Вірно! Ось нове завдання.';
            $addScoreOnSuccess = config('games.mathQuiz.addScoreOnSuccess');
            $newScore = (int)$score + (int)$addScoreOnSuccess;
            $example = $this->addNewExample($messageDto, $newScore);

        } else if ($activeResult && (((int)$requestNumber !== (int)$activeResult))) {
            $prefixResponseMessage = 'Не вірно! Ось нове завдання: ';
            $addScoreOnFailure = config('games.mathQuiz.addScoreOnFailure');
            $newScore = (int)$score + (int)$addScoreOnFailure;
            $newScore = $newScore < 0 ? 0 : $newScore;
            $example = $this->addNewExample($messageDto, $newScore);

        } else {
            $prefixResponseMessage = 'Привіт! Ось твоє завдання: ';
            $example = $this->addNewExample($messageDto);
        }

        return $prefixResponseMessage . $example;

    }

    protected function start(MessageDto $messageDto)
    {
        $prefixCacheKey = config('games.mathQuiz.prefixCacheKey');
        $userId = $messageDto->getUserDto()->getId();
        $kay = $prefixCacheKey . $userId;
        $isForget = Cache::forget($kay);
        $prefixResponseMessage = 'Привіт';

        if ($isForget) {
            $prefixResponseMessage = $prefixResponseMessage . ', старі результати віддалилися';
        }

        $prefixResponseMessage = $prefixResponseMessage . '! Ось твоє завдання: ';
        $example = $this->addNewExample($messageDto);

        return $prefixResponseMessage . $example;
    }

}
