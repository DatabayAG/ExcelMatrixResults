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
    /**
     * @var int[]
     */
    protected $totalQuestionPointsRows = [];

    public function addTotalQuestionPointsRow(int $totalQuestionPointsRow): void
    {
        $this->totalQuestionPointsRows[] = $totalQuestionPointsRow;
    }

    /**
     * @return int[]
     */
    public function getTotalQuestionPointsRows(): array
    {
        return $this->totalQuestionPointsRows;
    }

    public function getNumQuestions(): int
    {
        return count($this->totalQuestionPointsRows);
    }
}
