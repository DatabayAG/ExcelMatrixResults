<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrTextQuestionExportMatrixRenderer extends emrExportMatrixRendererAbstract
{
    /**
     * @var emrTextQuestionAnswerOptionList
     */
    protected emrAnswerOptionList $answerOptionList;

    protected assQuestion $questionOBJ;

    protected function getQuestionTypeLabel(): string
    {
        return $this->getPlugin()->txt('qst_type_label_textquestion');
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

        $this->renderAnswerPoints($excel, ++$row, $row - 1, 3, 4);

        $this->qstPointsRowCollector->addTotalQuestionPointsRow($row);

        return $firstRow + 1 + 3;
    }

    protected function renderAnswerPoints(ilMatrixResultsExportExcel $excel, int $curRow, int $answerRow, int $compareCol, int $pointsCol): void
    {
        $numParticipants = count($this->participantData->getActiveIds());

        $firstCol = 6;
        $lastCol = $firstCol + $numParticipants - 1;

        for ($col = $firstCol; $col <= $lastCol; $col++) {
            $formula = $this->getParticipantEssayPointsFormula(
                $excel,
                $answerRow,
                $compareCol,
                $pointsCol,
                $col
            );

            $cellCoord = $excel->getCoordByColumnAndRow($col, $curRow);
            $excel->setFormulaByCoordinates($cellCoord, $formula);
            $excel->setBorders($cellCoord, true, true, true, true);
            $excel->setColors($cellCoord, ilMatrixResultsExportExcel::COLOR_LIGHT_YELLOW);
            $excel->setBold($cellCoord);
        }
    }

    protected function getParticipantEssayPointsFormula(ilMatrixResultsExportExcel $excel, int $answerRow, int $compareCol, int $pointsCol, int $col): string
    {
        $compareCoord = $excel->getCoordByColumnAndRow($compareCol, $answerRow);
        $pointsCoord = $excel->getCoordByColumnAndRow($pointsCol, $answerRow);
        $answerCoord = $excel->getCoordByColumnAndRow($col, $answerRow);

        return "=MIN(IF($compareCoord=$answerCoord,$pointsCoord,0),1)";
    }

    protected function renderFrontBorder(ilMatrixResultsExportExcel $excel, int $firstRow): void
    {
        for ($row = $firstRow, $max = $firstRow + 1 + 3; $row <= $max; $row++) {
            $excel->setBorderRight($excel->getCoordByColumnAndRow(0, $row));
        }
    }


    protected function renderAnswerOptionLabels(ilMatrixResultsExportExcel $excel, int $firstRow): void
    {
        $row = $firstRow;
        $col = 3;

        $coords = $excel->getCoordByColumnAndRow($col, $row);

        $excel->setCellByCoordinates(
            $coords,
            $this->getPlugin()->txt('answer_option_label_textquestion')
        );
    }

    protected function renderAnswerOptionPoints(ilMatrixResultsExportExcel $excel, int $firstRow): void
    {
        $row = $firstRow;
        $col = 4;

        $coords = $excel->getCoordByColumnAndRow($col, $row);
        $excel->setCellByCoordinates($coords, $this->questionOBJ->getPoints());
        $excel->setBold($coords);
        $excel->setColors($coords, ilMatrixResultsExportExcel::COLOR_LIGHT_BLUE);
        $excel->setBorders($coords, true, true, true, true);
    }


    protected function renderAnswerOptionFrequencyFormula(ilMatrixResultsExportExcel $excel, int $firstRow): void
    {
        $row = $firstRow;
        $col = 5;

        $coords = $excel->getCoordByColumnAndRow($col, $row);
        $excel->setCellByCoordinates($coords, '---');
        $excel->setBold($coords);
        $excel->setColors($coords, ilMatrixResultsExportExcel::COLOR_LIGHT_BLUE);
        $excel->setBorders($coords, true, true, true, true);
    }

    protected function renderParticipantsAnswerings(ilMatrixResultsExportExcel $excel, int $firstRow): void
    {
        $row = $firstRow;
        $col = 6;

        foreach ($this->getParticipantData()->getActiveIds() as $activeId) {
            $coords = $excel->getCoordByColumnAndRow($col, $row);

            foreach ($this->answerOptionList as $answerOption) {
                if (!$answerOption->hasActiveIdAnswered((int) $activeId)) {
                    continue;
                }

                $excel->setNumberByCoordinates($coords, $answerOption->getTitle());

                break;
            }

            $col++;
        }
    }
}
