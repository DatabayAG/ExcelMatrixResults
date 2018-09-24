<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrExportHeaderRenderer implements emrExcelRangeRenderer
{
	/**
	 * @var ilExcelMatrixResultsPlugin
	 */
	protected $plugin;
	
	/**
	 * @var ilObjTest
	 */
	protected $testOBJ;
	
	/**
	 * @var ilTestParticipantData
	 */
	protected $participantData;
	
	/**
	 * @var emrScoredPassLookup
	 */
	protected $scoredPassLoopup;
	
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
	 * @return ilObjTest
	 */
	public function getTestOBJ()
	{
		return $this->testOBJ;
	}
	
	/**
	 * @param ilObjTest $testOBJ
	 */
	public function setTestOBJ($testOBJ)
	{
		$this->testOBJ = $testOBJ;
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
	 * @return emrScoredPassLookup
	 */
	public function getScoredPassLoopup()
	{
		return $this->scoredPassLoopup;
	}
	
	/**
	 * @param emrScoredPassLookup $scoredPassLoopup
	 */
	public function setScoredPassLoopup($scoredPassLoopup)
	{
		$this->scoredPassLoopup = $scoredPassLoopup;
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param int $firstRow
	 * @return int $lastRow
	 */
	public function render(ilMatrixResultsExportExcel $excel, $firstRow)
	{
		$row = $this->renderTestTitle($excel, $firstRow);
		$row = $this->renderParticipantLabels($excel, $row);
		$row = $this->renderParticipantTimes($excel, $row);
		$row = $this->renderParticipantNames($excel, ++$row);
		
		return $row;
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param int $firstRow
	 * @return int $lastRow
	 */
	protected function renderTestTitle(ilMatrixResultsExportExcel $excel, $row)
	{
		$start = $excel->getCoordByColumnAndRow(0, $row);
		$end = $excel->getCoordByColumnAndRow(3, $row + 3);
		$range = $start.':'.$end;
		
		$excel->mergeCells($range);
		
		$excel->setBold($start);
		$excel->setCellByCoordinates($start, $this->getTestOBJ()->getTitle());
		$excel->setAlignTop($start);
		
		return $row;
	}
	
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 */
	protected function renderParticipantLabels(ilMatrixResultsExportExcel $excel, $row)
	{
		$excel->mergeCells(
			"{$excel->getCoordByColumnAndRow(4, $row + 0)}:{$excel->getCoordByColumnAndRow(5, $row + 0)}"
		);
		$excel->mergeCells(
			"{$excel->getCoordByColumnAndRow(4, $row + 1)}:{$excel->getCoordByColumnAndRow(5, $row + 1)}"
		);
		$excel->mergeCells(
			"{$excel->getCoordByColumnAndRow(4, $row + 2)}:{$excel->getCoordByColumnAndRow(5, $row + 2)}"
		);
		$excel->mergeCells(
			"{$excel->getCoordByColumnAndRow(4, $row + 3)}:{$excel->getCoordByColumnAndRow(5, $row + 3)}"
		);

		$coord = $excel->getCoordByColumnAndRow(4, $row + 0);
		$excel->setCellByCoordinates($coord, $this->getPlugin()->txt('participants_workingtime'));
		$excel->setBold($coord);
		
		$coord = $excel->getCoordByColumnAndRow(4, $row + 1);
		$excel->setCellByCoordinates($coord, $this->getPlugin()->txt('participants_lastname') );
		$excel->setBold($coord);
		
		$coord = $excel->getCoordByColumnAndRow(4, $row + 2);
		$excel->setCellByCoordinates($coord, $this->getPlugin()->txt('participants_firstname') );
		$excel->setBold($coord);
		
		$coord = $excel->getCoordByColumnAndRow(4, $row + 3);
		$excel->setCellByCoordinates($coord, $this->getPlugin()->txt('participants_login') );
		$excel->setBold($coord);
		
		return $row;
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param int $firstRow
	 * @return int $lastRow
	 */
	protected function renderParticipantTimes(ilMatrixResultsExportExcel $excel, $row)
	{
		$col = 6;
		
		foreach($this->getParticipantData()->getActiveIds() as $activeId)
		{
			$workingTime = ilObjTest::_getWorkingTimeOfParticipantForPass(
				$activeId, $this->getScoredPassLoopup()->get($activeId)
			);
			
			$cellChord = $excel->getCoordByColumnAndRow($col, $row);
			
			$excel->setCellByCoordinates($cellChord, $excel->formatMinutes($workingTime));
			
			$col++;
		}
		
		return $row;
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param int $firstRow
	 * @return int $lastRow
	 */
	protected function renderParticipantNames(ilMatrixResultsExportExcel $excel, $row)
	{
		$col = 6;
		
		foreach($this->getParticipantData()->getActiveIds() as $activeId)
		{
			$data = $this->getParticipantData()->getUserDataByActiveId($activeId);
			
			$cellChord = $excel->getCoordByColumnAndRow($col, $row + 0);
			$excel->setCellByCoordinates($cellChord, $data['lastname']);
			
			$cellChord = $excel->getCoordByColumnAndRow($col, $row + 1);
			$excel->setCellByCoordinates($cellChord, $data['firstname']);
			
			$cellChord = $excel->getCoordByColumnAndRow($col, $row + 2);
			$excel->setCellByCoordinates($cellChord, $data['login']);
			
			$col++;
		}
		
		return $row + 2;
	}
}