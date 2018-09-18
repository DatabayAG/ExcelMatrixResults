<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrParticipantsHeaderRenderer implements emrExcelRangeRenderer
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
	 * @param ilAssExcelFormatHelper $excel
	 * @param int $firstRow
	 * @return int $lastRow
	 */
	public function render(ilAssExcelFormatHelper $excel, $firstRow)
	{
		$excel->setCellByCoordinates($excel->getColumnCoord(0).$firstRow, __CLASS__);
		
		return $firstRow;
	}
}