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
    protected string $questionGroupTitle;

    public function __construct(string $questionGroupTitle)
    {
        $this->questionGroupTitle = $questionGroupTitle;
    }

    public function render(ilMatrixResultsExportExcel $excel, int $firstRow): int
    {
        $cellChords = $excel->getCoordByColumnAndRow(0, $firstRow);

        $excel->setCellByCoordinates($cellChords, $this->questionGroupTitle);
        $excel->setBold($cellChords);
        //$excel->setBorderRight($cellChords, true);

        return $firstRow;
    }
}
