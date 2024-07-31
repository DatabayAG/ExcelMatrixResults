<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class emrTextQuestionAnswerOptionList
 *
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrTextQuestionAnswerOptionList extends emrAnswerOptionListAbstract implements emrAnswerOptionList, Iterator
{
    use emrAnswerOptionListIterator;
    
    /**
     * @var assTextQuestion
     */
    protected assQuestion $questionOBJ;

    /**
     * @param integer[] $activeIds
     * @param emrScoredPassLookup $scoredPassLoopup
     */
    public function initialise($activeIds, emrScoredPassLookup $scoredPassLoopup)
    {
        foreach ($activeIds as $activeId) {
            $pass = $scoredPassLoopup->get($activeId);
            $rows = $this->questionOBJ->getSolutionValues($activeId, $pass);
            
            $answerOption = new emrAnswerOption();
            
            if (count($rows)) {
                $answerOption->setTitle($this->refinery->string()->stripTags()->transform((string) $rows[0]['value1']));
            }
            
            $answerOption->addAnsweringActiveId($activeId);
            
            $this->addAnswerOption($answerOption, $activeId);
        }
    }
}
