<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class emrExportSummaryRenderer
 *
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Modules/Test(QuestionPool)
 */
class emrExportSummaryRenderer implements emrExcelRangeRenderer
{
    protected emrTotalQuestionPointsRowCollector $qstPointsRowCollector;
    protected ilTestParticipantData $participantData;
    protected $plugin;

    public function getQstPointsRowCollector(): emrTotalQuestionPointsRowCollector
    {
        return $this->qstPointsRowCollector;
    }

    public function setQstPointsRowCollector(emrTotalQuestionPointsRowCollector $qstPointsRowCollector): void
    {
        $this->qstPointsRowCollector = $qstPointsRowCollector;
    }

    public function getParticipantData(): ilTestParticipantData
    {
        return $this->participantData;
    }

    public function setParticipantData(ilTestParticipantData $participantData): void
    {
        $this->participantData = $participantData;
    }

    public function getPlugin(): ilExcelMatrixResultsPlugin
    {
        return $this->plugin;
    }

    public function setPlugin(ilExcelMatrixResultsPlugin $plugin): void
    {
        $this->plugin = $plugin;
    }

    public function render(ilMatrixResultsExportExcel $excel, int $firstRow): int
    {
        $this->renderParticipantsSummary($excel, $firstRow + 0);

        $this->renderQuestionCount($excel, $firstRow + 2);

        $firstCol = 6;
        $lastCol = $firstCol + count($this->participantData->getActiveIds()) - 1;

        $this->renderMaxPoints($excel, $firstRow + 3, $this->getMaxPointsFormula(
            $excel,
            $firstRow,
            $firstCol,
            $lastCol
        ));

        $this->renderMinPoints($excel, $firstRow + 4, $this->getMinPointsFormula(
            $excel,
            $firstRow,
            $firstCol,
            $lastCol
        ));

        $this->renderAvgPoints($excel, $firstRow + 5, $this->getAvgPointsFormula(
            $excel,
            $firstRow,
            $firstCol,
            $lastCol
        ));

        return $firstRow + 6;
    }

    protected function getMinPointsFormula(ilMatrixResultsExportExcel $excel, int $row, int $firstCol, int $lastCol): string
    {
        $startCoord = $excel->getCoordByColumnAndRow($firstCol, $row);
        $endCoord = $excel->getCoordByColumnAndRow($lastCol, $row);
        return "=MIN($startCoord:$endCoord)";
    }

    protected function getMaxPointsFormula(ilMatrixResultsExportExcel $excel, int $row, int $firstCol, int $lastCol): string
    {
        $startCoord = $excel->getCoordByColumnAndRow($firstCol, $row);
        $endCoord = $excel->getCoordByColumnAndRow($lastCol, $row);
        return "=MAX($startCoord:$endCoord)";
    }

    protected function getAvgPointsFormula(ilMatrixResultsExportExcel $excel, int $row, int $firstCol, int $lastCol): string
    {
        $startCoord = $excel->getCoordByColumnAndRow($firstCol, $row);
        $endCoord = $excel->getCoordByColumnAndRow($lastCol, $row);
        return "=AVERAGE($startCoord:$endCoord)";
    }

    protected function renderAvgPoints(ilMatrixResultsExportExcel $excel, int $row, string $formula): void
    {
        $this->renderStatisticRow(
            $excel,
            $row,
            true,
            $this->getPlugin()->txt('summary_average_points'),
            $formula
        );
    }

    protected function renderMinPoints(ilMatrixResultsExportExcel $excel, int $row, string $formula): void
    {
        $this->renderStatisticRow(
            $excel,
            $row,
            true,
            $this->getPlugin()->txt('summary_minimum_points'),
            $formula
        );
    }

    protected function renderMaxPoints(ilMatrixResultsExportExcel $excel, int $row, string $formula): void
    {
        $this->renderStatisticRow(
            $excel,
            $row,
            true,
            $this->getPlugin()->txt('summary_maximum_points'),
            $formula
        );
    }

    protected function renderQuestionCount(ilMatrixResultsExportExcel $excel, int $row): void
    {
        $this->renderStatisticRow(
            $excel,
            $row,
            false,
            $this->getPlugin()->txt('summary_question_count'),
            $this->getQstPointsRowCollector()->getNumQuestions()
        );
    }

    /**
     * @param mixed $value
     */
    protected function renderStatisticRow(ilMatrixResultsExportExcel $excel, int $row, bool $isFormula, string $label, $value): void
    {
        $startCoord = $excel->getCoordByColumnAndRow(3, $row);
        $endCoord = $excel->getCoordByColumnAndRow(4, $row);
        $excel->mergeCells("$startCoord:$endCoord");
        $excel->setCellByCoordinates($startCoord, $label);
        $excel->setColors($startCoord, ilMatrixResultsExportExcel::COLOR_GREY);
        $excel->setBold($startCoord);

        $excel->setBorders($startCoord, true, false, true, true);
        $excel->setBorders($endCoord, true, true, true, false);

        $coord = $excel->getCoordByColumnAndRow(5, $row);
        if ($isFormula) {
            $excel->setFormulaByCoordinates($coord, (string) $value);
        } else {
            $excel->setCellByCoordinates($coord, $value);
        }
        $excel->setColors($coord, ilMatrixResultsExportExcel::COLOR_LIGHT_YELLOW);
        $excel->setBorders($coord, true, true, true, true);
        $excel->setBold($coord);
    }

    protected function renderParticipantsSummary(ilMatrixResultsExportExcel $excel, int $row): void
    {
        $startCoord = $excel->getCoordByColumnAndRow(3, $row);
        $endCoord = $excel->getCoordByColumnAndRow(5, $row);
        $excel->mergeCells("$startCoord:$endCoord");
        $excel->setCellByCoordinates($startCoord, $this->getPlugin()->txt('summary_participants_points'));
        $excel->setColors($startCoord, ilMatrixResultsExportExcel::COLOR_GREY);
        $excel->setBold($startCoord);

        $excel->setBorders($startCoord, true, false, true, true);
        $excel->setBorders($endCoord, true, true, true, false);

        $firstCol = 6;
        $lastCol = $firstCol + count($this->participantData->getActiveIds()) - 1;

        for ($col = $firstCol; $col <= $lastCol; $col++) {
            $formula = $this->getOverallPointsFormula($excel, $col);

            $coord = $excel->getCoordByColumnAndRow($col, $row);
            $excel->setFormulaByCoordinates($coord, $formula);
            $excel->setColors($coord, ilMatrixResultsExportExcel::COLOR_LIGHT_YELLOW);
            $excel->setBorders($coord, true, true, true, true);
            $excel->setBold($coord);
        }
    }

    protected function getOverallPointsFormula(ilMatrixResultsExportExcel $excel, int $col): string
    {
        $coords = array();

        foreach ($this->getQstPointsRowCollector()->getTotalQuestionPointsRows() as $row) {
            $coords[] = $excel->getCoordByColumnAndRow($col, $row);
        }

        if (!count($coords)) {
            return '';
        }

        return '=' . implode('+', $coords);
    }
}
