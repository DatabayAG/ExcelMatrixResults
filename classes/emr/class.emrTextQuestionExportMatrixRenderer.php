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
     * @var assTextQuestion
     */
    protected $questionOBJ;
    
    /**
     * emrTextQuestionExportMatrixRenderer constructor.
     * @param assQuestion $questionOBJ
     */
    public function __construct(assQuestion $questionOBJ)
    {
        $this->questionOBJ = $questionOBJ;
    }
    
    /**
     * @return string
     */
    protected function getQuestionTypeLabel()
    {
        return $this->getPlugin()->txt('qst_type_label_textquestion');
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $firstRow
     * @return int $lastRow
     */
    public function render(ilMatrixResultsExportExcel $excel, $firstRow)
    {
        $row = $firstRow;
        
        $this->renderFrontBorder($excel, $row);
        
        $this->renderQuestionTitle($excel, $row, $this->questionOBJ->getTitle());
        
        $this->renderQuestionType($excel, ++$row);
        $this->renderQuestionHeader($excel, $row);
        
        $this->renderAnswerOptionLabels($excel, ++$row);
        $this->renderAnswerOptionPoints($excel, $row);
        $this->renderParticipantsAnswerings($excel, $row);
        
        $this->renderAnswerPoints($excel, ++$row, $row - 1, 3, 4);
        
        $this->qstPointsRowCollector->addTotalQuestionPointsRow($row);

        return $firstRow + 1 + 3;
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $curRow
     * @param int $answerRow
     * @param int $compareCol
     */
    protected function renderAnswerPoints(ilMatrixResultsExportExcel $excel, $curRow, $answerRow, $compareCol, $pointsCol)
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
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $answerRow
     * @param int $compareCol
     * @param int $pointsCol
     * @param int $col
     * @return string
     */
    protected function getParticipantEssayPointsFormula(ilMatrixResultsExportExcel $excel, $answerRow, $compareCol, $pointsCol, $col)
    {
        $compareCoord = $excel->getCoordByColumnAndRow($compareCol, $answerRow);
        $pointsCoord = $excel->getCoordByColumnAndRow($pointsCol, $answerRow);
        $answerCoord = $excel->getCoordByColumnAndRow($col, $answerRow);
        
        return "=MIN(IF($compareCoord=$answerCoord,$pointsCoord,0),1)";
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $firstRow
     */
    protected function renderFrontBorder(ilMatrixResultsExportExcel $excel, $firstRow)
    {
        for ($row = $firstRow, $max = $firstRow + 1 + 3; $row <= $max; $row++) {
            $excel->setBorderRight($excel->getCoordByColumnAndRow(0, $row));
        }
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $firstRow
     */
    protected function renderQuestionHeader(ilMatrixResultsExportExcel $excel, $firstRow)
    {
        $startCoord = $excel->getCoordByColumnAndRow(4, $firstRow);
        $endCoord = $excel->getCoordByColumnAndRow(5, $firstRow);
        $excel->mergeCells("$startCoord:$endCoord");
        $excel->setCellByCoordinates($startCoord, $this->getPlugin()->txt('points_header'));
        $excel->setColors($startCoord, ilMatrixResultsExportExcel::COLOR_LIGHT_BLUE);
        $excel->setBold($startCoord);
        
        $excel->setBorders($startCoord, true, false, true, true);
        $excel->setBorders($endCoord, false, true, false, false);
        
        $excel->setAlignRight($startCoord);
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $firstRow
     */
    protected function renderAnswerOptionLabels(ilMatrixResultsExportExcel $excel, $firstRow)
    {
        $row = $firstRow;
        $col = 3;
        
        $coords = $excel->getCoordByColumnAndRow($col, $row);
        
        $excel->setCellByCoordinates(
            $coords,
            $this->getPlugin()->txt('answer_option_label_textquestion')
        );
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $firstRow
     */
    protected function renderAnswerOptionPoints(ilMatrixResultsExportExcel $excel, $firstRow)
    {
        $row = $firstRow;
        $col = 4;
        
        $startCoords = $excel->getCoordByColumnAndRow($col, $row);
        $endCoords = $excel->getCoordByColumnAndRow($col + 1, $row);
        
        $excel->mergeCells("$startCoords:$endCoords");
        
        $excel->setCellByCoordinates($startCoords, $this->questionOBJ->getPoints());
        $excel->setBold($startCoords);
        $excel->setColors($startCoords, ilMatrixResultsExportExcel::COLOR_LIGHT_BLUE);
        
        $excel->setBorders($startCoords, true, false, true, true);
        $excel->setBorders($endCoords, false, true, false, false);
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $firstRow
     */
    protected function renderParticipantsAnswerings(ilMatrixResultsExportExcel $excel, $firstRow)
    {
        $row = $firstRow;
        $col = 6;
        
        foreach ($this->getParticipantData()->getActiveIds() as $activeId) {
            $coords = $excel->getCoordByColumnAndRow($col, $row);

            foreach ($this->answerOptionList as $answerOption) {
                if (!$answerOption->hasActiveIdAnswered($activeId)) {
                    continue;
                }
                
                $excel->setNumberByCoordinates($coords, $answerOption->getTitle());

                break;
            }
            
            $col++;
        }
    }
}
