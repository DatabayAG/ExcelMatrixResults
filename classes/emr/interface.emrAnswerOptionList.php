<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

use ILIAS\Refinery\Factory as Refinery;

interface emrAnswerOptionList
{
    public function __construct(assQuestion $questionOBJ, Refinery $refinery);
    
    /**
     * @param integer[] $activeIds
     * @param emrScoredPassLookup $scoredPassLoopup
     * @return void
     */
    public function initialise($activeIds, emrScoredPassLookup $scoredPassLoopup);
    
    /**
     * @return int
     */
    public function getNumAnswers();
}
