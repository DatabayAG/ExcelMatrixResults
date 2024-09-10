<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
abstract class emrExportMatrixRendererAbstract implements emrExcelRangeRenderer
{
    protected assQuestion $questionOBJ;
    protected ilTestParticipantData $participantData;
    protected emrAnswerOptionList $answerOptionList;
    protected ilExcelMatrixResultsPlugin $plugin;
    protected int $subIndex = 0;
    protected emrTotalQuestionPointsRowCollector $qstPointsRowCollector;


    public function __construct(assQuestion $questionOBJ)
    {
        $this->questionOBJ = $questionOBJ;
    }

    public function getQstPointsRowCollector(): emrTotalQuestionPointsRowCollector
    {
        return $this->qstPointsRowCollector;
    }

    public function setQstPointsRowCollector(emrTotalQuestionPointsRowCollector $qstPointsRowCollector): void
    {
        $this->qstPointsRowCollector = $qstPointsRowCollector;
    }

    public function getPlugin(): ilExcelMatrixResultsPlugin
    {
        return $this->plugin;
    }

    public function setPlugin(ilExcelMatrixResultsPlugin $plugin): void
    {
        $this->plugin = $plugin;
    }

    public function getSubIndex(): int
    {
        return $this->subIndex;
    }

    public function setSubIndex(int $subIndex): void
    {
        $this->subIndex = $subIndex;
    }

    public function getParticipantData(): ilTestParticipantData
    {
        return $this->participantData;
    }

    public function setParticipantData(ilTestParticipantData $participantData): void
    {
        $this->participantData = $participantData;
    }

    public function getAnswerOptionList(): emrAnswerOptionList
    {
        return $this->answerOptionList;
    }

    public function setAnswerOptionList(emrAnswerOptionList $answerOptionList): void
    {
        $this->answerOptionList = $answerOptionList;
    }

    protected function renderFrontBorder(ilMatrixResultsExportExcel $excel, int $firstRow): void
    {
        $numAnswers = $this->getAnswerOptionList()->getNumAnswers();

        for ($row = $firstRow, $max = $firstRow + $numAnswers + 3; $row <= $max; $row++) {
            $excel->setBorderRight($excel->getCoordByColumnAndRow(0, $row));
        }
    }

    protected function renderQuestionTitle(ilMatrixResultsExportExcel $excel, int $firstRow, string $itemTitle): void
    {
        $questionTitle = $this->parseQuestionTitle($itemTitle);
        $coords = $excel->getCoordByColumnAndRow(1, $firstRow);
        $excel->setCellByCoordinates($coords, $questionTitle);
    }

    protected function parseQuestionTitle(string $itemTitle): string
    {
        $matches = null;

        if (preg_match('/^(.*?), (.*?)$/', $itemTitle, $matches)) {
            return $matches[2];
        }

        return $itemTitle;
    }

    protected function renderQuestionType(ilMatrixResultsExportExcel $excel, int $firstRow): void
    {
        $coords = $excel->getCoordByColumnAndRow(2, $firstRow);
        $excel->setCellByCoordinates($coords, $this->getQuestionTypeLabel());
    }

    abstract protected function getQuestionTypeLabel(): string;

    protected function renderAnswerOptionLabels(ilMatrixResultsExportExcel $excel, int $firstRow): void
    {
        $row = $firstRow;
        $col = 3;

        foreach ($this->answerOptionList as $answerOption) {
            $coords = $excel->getCoordByColumnAndRow($col, $row);
            $excel->setCellByCoordinates($coords, $answerOption->getTitle());

            $row++;
        }
    }

    protected function renderAnswerOptionPoints(ilMatrixResultsExportExcel $excel, int $firstRow): void
    {
        $row = $firstRow;
        $col = 4;

        foreach ($this->answerOptionList as $answerOption) {
            $coords = $excel->getCoordByColumnAndRow($col, $row);
            $excel->setCellByCoordinates($coords, $answerOption->getPoints());
            $excel->setBold($coords);
            $excel->setColors($coords, ilMatrixResultsExportExcel::COLOR_LIGHT_BLUE);
            $excel->setBorders($coords, true, true, true, true);

            $row++;
        }
    }

    protected function renderAnswerOptionFrequencyFormula(ilMatrixResultsExportExcel $excel, int $firstRow): void
    {
        $row = $firstRow;
        $col = 5;

        foreach ($this->answerOptionList as $answerOption) {
            $coords = $excel->getCoordByColumnAndRow($col, $row);
            $excel->setFormulaByCoordinates($coords, $this->getAnswerFrequencyFormula($excel, $col, $row));
            $excel->setBold($coords);
            $excel->setColors($coords, ilMatrixResultsExportExcel::COLOR_LIGHT_BLUE);
            $excel->setBorders($coords, true, true, true, true);

            $row++;
        }
    }

    protected function renderParticipantsAnswerings(ilMatrixResultsExportExcel $excel, int $firstRow): void
    {
        $row = $firstRow;

        foreach ($this->answerOptionList as $answerOption) {
            $col = 6;

            foreach ($this->getParticipantData()->getActiveIds() as $activeId) {
                $coords = $excel->getCoordByColumnAndRow($col, $row);

                $excel->setNumberByCoordinates(
                    $coords,
                    $answerOption->hasActiveIdAnswered((int) $activeId) ? 1 : 0
                );

                $col++;
            }

            $row++;
        }
    }

    protected function renderQuestionHeader(ilMatrixResultsExportExcel $excel, int $firstRow): void
    {
        $coords = $excel->getCoordByColumnAndRow(4, $firstRow);
        $excel->setCellByCoordinates($coords, $this->getPlugin()->txt('points_header'));
        $excel->setColors($coords, ilMatrixResultsExportExcel::COLOR_LIGHT_BLUE);
        $excel->setBorders($coords, true, true, true, true);
        $excel->setBold($coords);

        $coords = $excel->getCoordByColumnAndRow(5, $firstRow);
        $excel->setCellByCoordinates($coords, $this->getPlugin()->txt('frequency_header'));
        $excel->setColors($coords, ilMatrixResultsExportExcel::COLOR_LIGHT_BLUE);
        $excel->setBorders($coords, true, true, true, true);
        $excel->setBold($coords);
    }

    protected function getAnswerFrequencyFormula(ilMatrixResultsExportExcel $excel, int $col, int $row): string
    {
        $numParticipants = count($this->getParticipantData()->getActiveIds());

        $startCoord = $excel->getCoordByColumnAndRow($col + 1, $row);
        $endCoord = $excel->getCoordByColumnAndRow($col + $numParticipants, $row);

        return "=SUM($startCoord:$endCoord)";
    }

    protected function getParticipantQuestionPointsFormula(
        ilMatrixResultsExportExcel $excel,
        int $pointsCol,
        int $participantCol,
        int $row,
        emrAnswerOptionList $answerOptionList
    ): string {
        $sums = array();

        foreach ($answerOptionList as $answerOption) {
            $pointsStartCoord = $excel->getCoordByColumnAndRow($pointsCol, $row);
            $pointsEndCoord = $excel->getCoordByColumnAndRow($pointsCol, $row);

            $participantStartCoord = $excel->getCoordByColumnAndRow($participantCol, $row);
            $participantEndCoord = $excel->getCoordByColumnAndRow($participantCol, $row);

            if ($answerOption->hasPoints()) {
                $f = "MIN(SUMPRODUCT($pointsStartCoord:$pointsEndCoord,$participantStartCoord:$participantEndCoord),1)";
            } else {
                $f = "SUMPRODUCT($pointsStartCoord:$pointsEndCoord,$participantStartCoord:$participantEndCoord)";
            }

            $sums[] = $f;
            $row++;
        }

        return "=" . implode('+', $sums);
    }

    protected function renderTotalPoints(ilMatrixResultsExportExcel $excel, int $startRow, emrAnswerOptionList $answerOptionList): void
    {
        $pointsCol = 4;
        $firstCol = 6;
        $lastCol = $firstCol + count($this->participantData->getActiveIds()) - 1;

        $renderRow = $startRow + $answerOptionList->getNumAnswers();

        for ($col = $firstCol; $col <= $lastCol; $col++) {
            $cellCoord = $excel->getCoordByColumnAndRow($col, $renderRow);

            $formula = $this->getParticipantQuestionPointsFormula(
                $excel,
                $pointsCol,
                $col,
                $startRow,
                $answerOptionList
            );

            $excel->setFormulaByCoordinates($cellCoord, $formula);
            $excel->setBorders($cellCoord, true, true, true, true);
            $excel->setColors($cellCoord, ilMatrixResultsExportExcel::COLOR_LIGHT_YELLOW);
            $excel->setBold($cellCoord);
        }
    }
}
