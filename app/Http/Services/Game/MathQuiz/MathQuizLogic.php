<?php


namespace App\Http\Services\Game\MathQuiz;


class MathQuizLogic
{
    private MathQuizExample $mathQuizExample;

    public function __construct()
    {
        $this->setMathQuizExample();
    }

    private function setMathQuizExample(): static
    {
        $this->mathQuizExample = new MathQuizExample();

        return $this;
    }

    /**
     * @return MathQuizExample
     */
    public function getMathQuizExample(): MathQuizExample
    {
        return $this->mathQuizExample;
    }


}
