<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class emrLongMenuAnswerOptionList
 *
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrLongMenuAnswerOptionList extends emrAnswerOptionListAbstract implements emrAnswerOptionList, Iterator
{
    use emrAnswerOptionListIterator;

    /**
     * @var assLongMenu
     */
    protected assQuestion $questionOBJ;

    protected int $gapIndex;

    public function getGapIndex(): int
    {
        return $this->gapIndex;
    }

    public function setGapIndex(int $gapIndex)
    {
        $this->gapIndex = $gapIndex;
    }

    /**
     * @param int[] $activeIds
     */
    public function initialise(array $activeIds, emrScoredPassLookup $scoredPassLoopup): void
    {
        $this->initCorrectAnswers();
        //$this->initWrongAnswers();

        foreach ($activeIds as $activeId) {
            $pass = $scoredPassLoopup->get($activeId);
            $rows = $this->questionOBJ->getSolutionValues($activeId, $pass);

            foreach ($rows as $row) {
                if ($row['value1'] != $this->getGapIndex()) {
                    continue;
                }

                if ($this->answerOptionExists((string) $row['value2'])) {
                    $answerOption = $this->getAnswerOption((string) $row['value2']);
                    $answerOption->addAnsweringActiveId((int) $activeId);
                } else {
                    $answerOption = new emrAnswerOption();
                    $answerOption->setTitle('# ' . $row['value2']);
                    $answerOption->setPoints(0);

                    $answerOption->addAnsweringActiveId((int) $activeId);

                    $this->addAnswerOption($answerOption, (string) $row['value2']);
                }
            }
        }
    }

    public function initCorrectAnswers()
    {
        foreach ($this->questionOBJ->getCorrectAnswers() as $gapIndex => $gapData) {
            if ($gapIndex != $this->getGapIndex()) {
                continue;
            }

            foreach ($gapData[0] as $answertext) {
                $answerOption = new emrAnswerOption();
                $answerOption->setTitle((string) $answertext);
                $answerOption->setPoints((float) $gapData[1]);

                $this->addAnswerOption($answerOption, (string) $answertext);
            }
        }
    }

    protected function initWrongAnswers()
    {
        foreach ($this->questionOBJ->getAnswers() as $gapIndex => $gapAnswers) {
            if ($gapIndex != $this->getGapIndex()) {
                continue;
            }

            foreach ($gapAnswers as $answer) {
                if ($this->answerOptionExists($answer)) {
                    continue;
                }

                $answerOption = new emrAnswerOption();
                $answerOption->setTitle('# ' . $answer);
                $answerOption->setPoints(0);

                $this->addAnswerOption($answerOption, $answer);
            }
        }
    }
}
