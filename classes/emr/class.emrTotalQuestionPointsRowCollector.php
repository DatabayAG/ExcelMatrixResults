<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrTotalQuestionPointsRowCollector
{
	protected $totalQuestionPointsRows = array();
	
	public function addTotalQuestionPointsRow($totalQuestionPointsRow)
	{
		$this->totalQuestionPointsRows[] = $totalQuestionPointsRow;
	}
	
	public function getTotalQuestionPointsRows()
	{
		return $this->totalQuestionPointsRows;
	}
	
	public function getNumQuestions()
	{
		return count($this->totalQuestionPointsRows);
	}
}