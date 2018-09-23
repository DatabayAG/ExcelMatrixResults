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
		$end = $excel->getCoordByColumnAndRow(5, $row);
		$range = $start.':'.$end;
		
		$excel->mergeCells($range);
		
		$excel->setBold($start);
		$excel->setCellByCoordinates($start, $this->getTestOBJ()->getTitle());
		
		$excel->setBorderBottom($range, true);
		$excel->setBorderRight($end, true);
		
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
			
			$excel->setBorderBottom($cellChord, true);
			
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
		$excel->setBorderRight($excel->getCoordByColumnAndRow(0, $row + 0), true);
		$excel->setBorderRight($excel->getCoordByColumnAndRow(0, $row + 1), true);
		$excel->setBorderRight($excel->getCoordByColumnAndRow(0, $row + 2), true);
		
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