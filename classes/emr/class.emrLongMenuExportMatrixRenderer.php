<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrLongMenuExportMatrixRenderer extends emrExportMatrixRendererAbstract
{
    /**
     * @var emrLongMenuAnswerOptionList
     */
    protected emrAnswerOptionList $answerOptionList;

    protected assQuestion $questionOBJ;

    protected function getQuestionTypeLabel(): string
    {
        return sprintf($this->getPlugin()->txt('qst_type_label_longmenu'), $this->getSubIndex() + 1);
    }

    public function render(ilMatrixResultsExportExcel $excel, int $firstRow): int
    {
        $row = $firstRow;

        $this->renderFrontBorder($excel, $row);

        $this->renderQuestionTitle($excel, $row, $this->questionOBJ->getTitle());

        $this->renderQuestionType($excel, ++$row);
        $this->renderQuestionHeader($excel, $row);

        $this->renderAnswerOptionLabels($excel, ++$row);
        $this->renderAnswerOptionPoints($excel, $row);
        $this->renderAnswerOptionFrequencyFormula($excel, $row);
        $this->renderParticipantsAnswerings($excel, $row);

        $this->renderTotalPoints($excel, $row, $this->answerOptionList);

        $this->qstPointsRowCollector->addTotalQuestionPointsRow(
            $row + $this->answerOptionList->getNumAnswers()
        );

        return $firstRow + $this->getAnswerOptionList()->getNumAnswers() + 3;
    }
}
