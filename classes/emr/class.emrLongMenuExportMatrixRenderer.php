<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrLongMenuExportMatrixRenderer extends emrExportMatrixRendererAbstract
{
	/**
	 * @var assLongMenu
	 */
	protected $questionOBJ;
	
	/**
	 * emrLongMenuExportMatrixRenderer constructor.
	 * @param assQuestion $questionOBJ
	 */
	public function __construct(assQuestion $questionOBJ)
	{
		$this->questionOBJ = $questionOBJ;
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