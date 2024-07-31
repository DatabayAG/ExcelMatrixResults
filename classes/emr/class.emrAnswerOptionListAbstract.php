<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

use ILIAS\Refinery\Factory as Refinery;

abstract class emrAnswerOptionListAbstract
{

    protected assQuestion $questionOBJ;
    protected Refinery $refinery;

    public function __construct(assQuestion $questionOBJ, Refinery $refinery) {
        $this->questionOBJ = $questionOBJ;
        $this->refinery = $refinery;
    }
    
    /**
     * @param integer[] $activeIds
     * @param emrScoredPassLookup $scoredPassLoopup
     * @return void
     */
    abstract public function initialise($activeIds, emrScoredPassLookup $scoredPassLoopup);
    
    /**
     * @return int
     */
    abstract public function getNumAnswers();
}
