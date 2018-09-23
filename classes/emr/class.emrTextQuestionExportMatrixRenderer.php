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

		return $firstRow + 1 + 3;
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param int $firstRow
	 */
	protected function renderFrontBorder(ilMatrixResultsExportExcel $excel, $firstRow)
	{
		for($row = $firstRow, $max = $firstRow + 1 + 3; $row <= $max; $row++)
		{
			$excel->setBorderRight($excel->getCoordByColumnAndRow(0, $row), true);
		}
	}
}