<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

use ILIAS\Refinery\Factory as Refinery;

interface emrAnswerOptionList
{
    public function __construct(assQuestion $questionOBJ, Refinery $refinery);

    /**
     * @param integer[] $activeIds
     */
    public function initialise(array $activeIds, emrScoredPassLookup $scoredPassLoopup): void;

    public function getNumAnswers(): int;
}
