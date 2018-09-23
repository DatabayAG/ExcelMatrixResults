<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class emrQuestionGroupHeaderRenderer
 *
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Modules/Test(QuestionPool)
 */
class emrQuestionGroupHeaderRenderer implements emrExcelRangeRenderer
{
	/**
	 * @var string
	 */
	protected $questionGroupTitle;
	
	/**
	 * emrQuestionGroupHeaderRenderer constructor.
	 * @param $questionGroupTitle
	 */
	public function __construct($questionGroupTitle)
	{
		$this->questionGroupTitle = $questionGroupTitle;
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param int $firstRow
	 * @return int $lastRow
	 */
	public function render(ilMatrixResultsExportExcel $excel, $firstRow)
	{
		$cellChords = $excel->getCoordByColumnAndRow(0, $firstRow);
		
		$excel->setCellByCoordinates($cellChords, $this->questionGroupTitle);
		$excel->setBold($cellChords);
		$excel->setBorderRight($cellChords, true);
		
		return $firstRow;
	}
	
	
}