<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

interface emrExcelRangeRenderer
{
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 * @param integer $firstRow
	 * @return integer $lastRow
	 */
	public function render(ilMatrixResultsExportExcel $excel, $firstRow);
}