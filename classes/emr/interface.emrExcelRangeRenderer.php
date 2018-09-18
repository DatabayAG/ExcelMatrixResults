<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

interface emrExcelRangeRenderer
{
	/**
	 * @param ilAssExcelFormatHelper $excel
	 * @param integer $firstRow
	 * @return integer $lastRow
	 */
	public function render(ilAssExcelFormatHelper $excel, $firstRow);
}