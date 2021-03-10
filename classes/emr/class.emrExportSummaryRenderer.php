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
    /**
     * @var emrTotalQuestionPointsRowCollector
     */
    protected $qstPointsRowCollector;
    
    /**
     * @var ilTestParticipantData
     */
    protected $participantData;
    
    /**
     * @var ilExcelMatrixResultsPlugin
     */
    protected $plugin;
    
    /**
     * @return emrTotalQuestionPointsRowCollector
     */
    public function getQstPointsRowCollector()
    {
        return $this->qstPointsRowCollector;
    }
    
    /**
     * @param emrTotalQuestionPointsRowCollector $qstPointsRowCollector
     */
    public function setQstPointsRowCollector($qstPointsRowCollector)
    {
        $this->qstPointsRowCollector = $qstPointsRowCollector;
    }
    
    /**
     * @return ilTestParticipantData
     */
    public function getParticipantData()
    {
        return $this->participantData;
    }
    
    /**
     * @param ilTestParticipantData $participantData
     */
    public function setParticipantData($participantData)
    {
        $this->participantData = $participantData;
    }
    
    /**
     * @return ilExcelMatrixResultsPlugin
     */
    public function getPlugin()
    {
        return $this->plugin;
    }
    
    /**
     * @param ilExcelMatrixResultsPlugin $plugin
     */
    public function setPlugin($plugin)
    {
        $this->plugin = $plugin;
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $firstRow
     * @return int $lastRow
     */
    public function render(ilMatrixResultsExportExcel $excel, $firstRow)
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
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $row
     * @param int $firstCol
     * @param int $lastCol
     */
    protected function getMinPointsFormula(ilMatrixResultsExportExcel $excel, $row, $firstCol, $lastCol)
    {
        $startCoord = $excel->getCoordByColumnAndRow($firstCol, $row);
        $endCoord = $excel->getCoordByColumnAndRow($lastCol, $row);
        return "=MIN($startCoord:$endCoord)";
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $row
     * @param int $firstCol
     * @param int $lastCol
     */
    protected function getMaxPointsFormula(ilMatrixResultsExportExcel $excel, $row, $firstCol, $lastCol)
    {
        $startCoord = $excel->getCoordByColumnAndRow($firstCol, $row);
        $endCoord = $excel->getCoordByColumnAndRow($lastCol, $row);
        return "=MAX($startCoord:$endCoord)";
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $row
     * @param int $firstCol
     * @param int $lastCol
     */
    protected function getAvgPointsFormula(ilMatrixResultsExportExcel $excel, $row, $firstCol, $lastCol)
    {
        $startCoord = $excel->getCoordByColumnAndRow($firstCol, $row);
        $endCoord = $excel->getCoordByColumnAndRow($lastCol, $row);
        return "=AVERAGE($startCoord:$endCoord)";
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $row
     * @param string $formula
     */
    protected function renderAvgPoints(ilMatrixResultsExportExcel $excel, $row, $formula)
    {
        $this->renderStatisticRow(
            $excel,
            $row,
            true,
            $this->getPlugin()->txt('summary_average_points'),
            $formula
        );
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $row
     * @param string $formula
     */
    protected function renderMinPoints(ilMatrixResultsExportExcel $excel, $row, $formula)
    {
        $this->renderStatisticRow(
            $excel,
            $row,
            true,
            $this->getPlugin()->txt('summary_minimum_points'),
            $formula
        );
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $row
     * @param string $formula
     */
    protected function renderMaxPoints(ilMatrixResultsExportExcel $excel, $row, $formula)
    {
        $this->renderStatisticRow(
            $excel,
            $row,
            true,
            $this->getPlugin()->txt('summary_maximum_points'),
            $formula
        );
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $row
     */
    protected function renderQuestionCount(ilMatrixResultsExportExcel $excel, $row)
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
     * @param ilMatrixResultsExportExcel $excel
     * @param int $row
     * @param bool $isFormula
     * @param string $label
     * @param string $value
     */
    protected function renderStatisticRow(ilMatrixResultsExportExcel $excel, $row, $isFormula, $label, $value)
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
            $excel->setFormulaByCoordinates($coord, $value);
        } else {
            $excel->setCellByCoordinates($coord, $value);
        }
        $excel->setColors($coord, ilMatrixResultsExportExcel::COLOR_LIGHT_YELLOW);
        $excel->setBorders($coord, true, true, true, true);
        $excel->setBold($coord);
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $row
     */
    protected function renderParticipantsSummary(ilMatrixResultsExportExcel $excel, $row)
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
    
    protected function getOverallPointsFormula(ilMatrixResultsExportExcel $excel, $col)
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
