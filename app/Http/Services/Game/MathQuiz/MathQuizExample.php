<?php


namespace App\Http\Services\Game\MathQuiz;


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
        $this->generationFirstNumber()
            ->generationSecondNumber()
            ->setStringExample()
            ->setResultExample();
    }

    /**
     * @param int $firstNumber
     */
    public function generationFirstNumber(): static
    {
        $this->firstNumber = random_int(config('math_quiz.randomGenerationFrom'), config('math_quiz.randomGenerationBefore'));;

        return $this;
    }

    /**
     * @param int $secondNumber
     */
    public function generationSecondNumber(): static
    {
        $this->secondNumber = random_int(config('math_quiz.randomGenerationFrom'), config('math_quiz.randomGenerationBefore'));

        return $this;
    }

    /**
     * @param string $example
     */
    public function setStringExample(): static
    {
        $this->stringExample = ((string)$this->getFirstNumber()) . '*' . ((string)$this->getSecondNumber());

        return $this;
    }

    /**
     * @param ResultExample $resultExample
     */
    public function setResultExample(): static
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


}
