<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class emrSingleChoiceAnswerOptionList
 *
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrSingleChoiceAnswerOptionList implements emrAnswerOptionList, Iterator
{
    use emrAnswerOptionListIterator;
    
    /**
     * @var assSingleChoice
     */
    protected $questionOBJ;
    
    /**
     * emrSingleChoiceAnswerOptionList constructor.
     * @param assQuestion $questionOBJ
     */
    public function __construct(assQuestion $questionOBJ)
    {
        $this->questionOBJ = $questionOBJ;
    }
    
    /**
     * @param integer[] $activeIds
     * @param emrScoredPassLookup $scoredPassLoopup
     */
    public function initialise($activeIds, emrScoredPassLookup $scoredPassLoopup)
    {
        $this->initAnswerOptions();
        
        foreach ($activeIds as $activeId) {
            $pass = $scoredPassLoopup->get($activeId);
            $rows = $this->questionOBJ->getSolutionValues($activeId, $pass);
            
            if (!count($rows)) {
                continue;
            }
            
            if ($this->answerOptionExists($rows[0]['value1'])) {
                $answerOption = $this->getAnswerOption($rows[0]['value1']);
                $answerOption->addAnsweringActiveId($activeId);
            }
        }
    }
    
    protected function initAnswerOptions()
    {
        $bestAnswerIndex = $this->getBestAnswerIndex();
        
        foreach ($this->questionOBJ->getAnswers() as $index => $answer) {
            $answerOption = new emrAnswerOption();
            
            $answerOption->setTitle(
                $index == $bestAnswerIndex ? $answer->getAnswertext() : '# ' . $answer->getAnswertext()
            );
            
            $answerOption->setPoints($answer->getPoints());
            
            $this->addAnswerOption($answerOption, $index);
        }
    }
    
    protected function getBestAnswerIndex()
    {
        $maxPoints = 0;
        $bestIndex = null;
        
        foreach ($this->questionOBJ->getAnswers() as $index => $answer) {
            if ($bestIndex === null || $answer->getPoints() > $maxPoints) {
                $maxPoints = $answer->getPoints();
                $bestIndex = $index;
            }
        }
    
        return $bestIndex;
    }
}
