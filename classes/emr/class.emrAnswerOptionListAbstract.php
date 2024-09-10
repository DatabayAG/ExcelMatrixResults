<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

use ILIAS\Refinery\Factory as Refinery;

abstract class emrAnswerOptionListAbstract
{
    protected assQuestion $questionOBJ;
    protected Refinery $refinery;

    public function __construct(assQuestion $questionOBJ, Refinery $refinery)
    {
        $this->questionOBJ = $questionOBJ;
        $this->refinery = $refinery;
    }

    /**
     * @param integer[] $activeIds
     */
    abstract public function initialise(array $activeIds, emrScoredPassLookup $scoredPassLoopup): void;

    abstract public function getNumAnswers(): int;
}
