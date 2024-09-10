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
class emrSingleChoiceAnswerOptionList extends emrAnswerOptionListAbstract implements emrAnswerOptionList, Iterator
{
    use emrAnswerOptionListIterator;

    /**
     * @var assSingleChoice
     */
    protected assQuestion $questionOBJ;


    /**
     * @param integer[] $activeIds
     */
    public function initialise(array $activeIds, emrScoredPassLookup $scoredPassLoopup): void
    {
        $this->initAnswerOptions();

        foreach ($activeIds as $activeId) {
            $pass = $scoredPassLoopup->get($activeId);
            $rows = $this->questionOBJ->getSolutionValues($activeId, $pass);

            if (!count($rows)) {
                continue;
            }

            if ($this->answerOptionExists((string) $rows[0]['value1'])) {
                $answerOption = $this->getAnswerOption((string) $rows[0]['value1']);
                $answerOption->addAnsweringActiveId((int) $activeId);
            }
        }
    }

    protected function initAnswerOptions()
    {
        $bestAnswerIndex = $this->getBestAnswerIndex();

        foreach ($this->questionOBJ->getAnswers() as $index => $answer) {
            $answerOption = new emrAnswerOption();

            $answerOption->setTitle(
                $index == $bestAnswerIndex ? (string) $answer->getAnswertext() : '# ' . $answer->getAnswertext()
            );

            $answerOption->setPoints((float) $answer->getPoints());

            $this->addAnswerOption($answerOption, (string) $index);
        }
    }

    protected function getBestAnswerIndex(): ?int
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
