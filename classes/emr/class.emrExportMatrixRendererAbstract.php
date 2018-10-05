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
	/**
	 * @var ilTestParticipantData
	 */
	protected $participantData;
	
	/**
	 * @var emrAnswerOptionList
	 */
	protected $answerOptionList;
	
	/**
	 * @var ilExcelMatrixResultsPlugin
	 */
	protected $plugin;
	
	/**
	 * @var int
	 */
	protected $subIndex = 0;
	
	/**
	 * @var emrTotalQuestionPointsRowCollector
	 */
	protected $qstPointsRowCollector;

	/**
	 * emrExportMatrixRenderer constructor.
	 * @param assQuestion $questionOBJ
	 */
	abstract public function __construct(assQuestion $questionOBJ);
	
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
	 * @return int
	 */
	public function getSubIndex()
	{
		return $this->subIndex;
	}
	
	/**
	 * @param int $subIndex
	 */
	public function setSubIndex($subIndex)
	{
		$this->subIndex = $subIndex;
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
	 * @return emrAnswerOptionList
	 */
	public function getAnswerOptionList()
	{
		return $this->answerOptionList;
	}
	
	/**
	 * @param emrAnswerOptionList $answerOptionList
	 */
	public function setAnswerOptionList($answerOptionList)
	{
		$this->answerOptionList = $answerOptionList;
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param $firstRow
	 */
	protected function renderFrontBorder(ilMatrixResultsExportExcel $excel, $firstRow)
	{
		$numAnswers = $this->getAnswerOptionList()->getNumAnswers();
		
		for($row = $firstRow, $max = $firstRow + $numAnswers + 3; $row <= $max; $row++)
		{
			$excel->setBorderRight($excel->getCoordByColumnAndRow(0, $row));
		}
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param $firstRow
	 */
	protected function renderQuestionTitle(ilMatrixResultsExportExcel $excel, $firstRow, $itemTitle)
	{
		$questionTitle = $this->parseQuestionTitle($itemTitle);
		$coords = $excel->getCoordByColumnAndRow(1, $firstRow);
		$excel->setCellByCoordinates($coords, $questionTitle);
	}
	
	/**
	 * @param $itemTitle
	 * @return string
	 */
	protected function parseQuestionTitle($itemTitle)
	{
		$matches = null;
		
		if( preg_match('/^(.*?), (.*?)$/', $itemTitle, $matches) )
		{
			return $matches[2];
		}
		
		return $itemTitle;
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param int $firstRow
	 */
	protected function renderQuestionType(ilMatrixResultsExportExcel $excel, $firstRow)
	{
		$coords = $excel->getCoordByColumnAndRow(2, $firstRow);
		$excel->setCellByCoordinates($coords, $this->getQuestionTypeLabel());
	}
	
	/**
	 * @return string
	 */
	abstract protected function getQuestionTypeLabel();
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param int $firstRow
	 */
	protected function renderAnswerOptionLabels(ilMatrixResultsExportExcel $excel, $firstRow)
	{
		$row = $firstRow;
		$col = 3;
		
		foreach($this->answerOptionList as $answerOption)
		{
			$coords = $excel->getCoordByColumnAndRow($col, $row);
			$excel->setCellByCoordinates($coords, $answerOption->getTitle());
			
			$row++;
		}
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param int $firstRow
	 */
	protected function renderAnswerOptionPoints(ilMatrixResultsExportExcel $excel, $firstRow)
	{
		$row = $firstRow;
		$col = 4;
		
		foreach($this->answerOptionList as $answerOption)
		{
			$coords = $excel->getCoordByColumnAndRow($col, $row);
			$excel->setCellByCoordinates($coords, $answerOption->getPoints());
			$excel->setBold($coords);
			$excel->setColors($coords,ilMatrixResultsExportExcel::COLOR_LIGHT_BLUE);
			$excel->setBorders($coords, true, true, true, true);
			
			$row++;
		}
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param int $firstRow
	 */
	protected function renderAnswerOptionFrequencyFormula(ilMatrixResultsExportExcel $excel, $firstRow)
	{
		$row = $firstRow;
		$col = 5;
		
		foreach($this->answerOptionList as $answerOption)
		{
			$coords = $excel->getCoordByColumnAndRow($col, $row);
			$excel->setFormulaByCoordinates($coords, $this->getAnswerFrequencyFormula($excel, $col, $row));
			$excel->setBold($coords);
			$excel->setColors($coords,ilMatrixResultsExportExcel::COLOR_LIGHT_BLUE);
			$excel->setBorders($coords, true, true, true, true);
			
			$row++;
		}
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param int $firstRow
	 */
	protected function renderParticipantsAnswerings(ilMatrixResultsExportExcel $excel, $firstRow)
	{
		$row = $firstRow;
		
		foreach($this->answerOptionList as $answerOption)
		{
			$col = 6;
			
			foreach($this->getParticipantData()->getActiveIds() as $activeId)
			{
				$coords = $excel->getCoordByColumnAndRow($col, $row);
				
				$excel->setNumberByCoordinates(
					$coords,$answerOption->hasActiveIdAnswered($activeId) ? 1 : 0
				);
				
				$col++;
			}
			
			$row++;
		}
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param int $firstRow
	 */
	protected function renderQuestionHeader(ilMatrixResultsExportExcel $excel, $firstRow)
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
	
	/**
	 * @param ilMatrixResultsExportExcel
	 * @param int $col
	 * @param int $row
	 * @return string
	 */
	protected function getAnswerFrequencyFormula(ilMatrixResultsExportExcel $excel, $col, $row)
	{
		$numParticipants = count($this->getParticipantData()->getActiveIds());
		
		$startCoord = $excel->getCoordByColumnAndRow($col + 1, $row);
		$endCoord = $excel->getCoordByColumnAndRow($col + $numParticipants, $row);
		
		return "=SUM($startCoord:$endCoord)";
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param int $pointsCol
	 * @param int $participantCol
	 * @param int $startRow
	 * @param int $endRow
	 * @return string
	 */
	protected function getParticipantQuestionPointsFormula(ilMatrixResultsExportExcel $excel, $pointsCol, $participantCol, $row, emrAnswerOptionList $answerOptionList)
	{
		$sums = array();
		
		foreach($answerOptionList as $answerOption)
		{
			$pointsStartCoord = $excel->getCoordByColumnAndRow($pointsCol, $row);
			$pointsEndCoord = $excel->getCoordByColumnAndRow($pointsCol, $row);
			
			$participantStartCoord = $excel->getCoordByColumnAndRow($participantCol, $row);
			$participantEndCoord = $excel->getCoordByColumnAndRow($participantCol, $row);

			if( $answerOption->hasPoints() )
			{
				$f = "MIN(SUMPRODUCT($pointsStartCoord:$pointsEndCoord,$participantStartCoord:$participantEndCoord),1)";
			}
			else
			{
				$f = "SUMPRODUCT($pointsStartCoord:$pointsEndCoord,$participantStartCoord:$participantEndCoord)";
			}
			
			$sums[] = $f;
			$row++;
		}
		
		return "=".implode('+', $sums);
	}
	
	protected function renderTotalPoints(ilMatrixResultsExportExcel $excel, $startRow, emrAnswerOptionList $answerOptionList)
	{
		$pointsCol = 4;
		$firstCol = 6;
		$lastCol = $firstCol + count($this->participantData->getActiveIds()) - 1;
		
		$renderRow = $startRow + $answerOptionList->getNumAnswers();
		
		for($col = $firstCol; $col <= $lastCol; $col++)
		{
			$cellCoord = $excel->getCoordByColumnAndRow($col, $renderRow);
			
			$formula = $this->getParticipantQuestionPointsFormula(
				$excel, $pointsCol, $col, $startRow, $answerOptionList
			);
			
			$excel->setFormulaByCoordinates($cellCoord, $formula);
			$excel->setBorders($cellCoord, true, true, true, true);
			$excel->setColors($cellCoord, ilMatrixResultsExportExcel::COLOR_LIGHT_YELLOW);
			$excel->setBold($cellCoord);
		}
	}
}