<?php

return [

    'mathQuiz' => [
        'prefixCacheKey' => 'mathQuiz_',
        'timeCache' => 10800,
        'randomGenerationFrom' => 2,
        'randomGenerationBefore' => 10,
        'cacheNameActiveResult' => 'activeResult',
        'cacheNameScore' => 'score',
        'addScoreOnSuccess' => 10,
        'addScoreOnFailure' => -2,
    ],

];
