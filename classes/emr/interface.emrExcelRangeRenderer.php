<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

interface emrExcelRangeRenderer
{
    public function render(ilMatrixResultsExportExcel $excel, int $firstRow): int;
}
