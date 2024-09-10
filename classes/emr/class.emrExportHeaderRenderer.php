<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrExportHeaderRenderer implements emrExcelRangeRenderer
{
    protected ilExcelMatrixResultsPlugin $plugin;
    protected ilObjTest $testOBJ;
    protected ilTestParticipantData $participantData;
    protected emrScoredPassLookup $scoredPassLoopup;

    public function getPlugin(): ilExcelMatrixResultsPlugin
    {
        return $this->plugin;
    }

    public function setPlugin(ilExcelMatrixResultsPlugin $plugin): void
    {
        $this->plugin = $plugin;
    }

    public function getTestOBJ(): ilObjTest
    {
        return $this->testOBJ;
    }

    public function setTestOBJ(ilObjTest $testOBJ): void
    {
        $this->testOBJ = $testOBJ;
    }

    public function getParticipantData(): ilTestParticipantData
    {
        return $this->participantData;
    }

    public function setParticipantData(ilTestParticipantData $participantData): void
    {
        $this->participantData = $participantData;
    }

    public function getScoredPassLoopup(): emrScoredPassLookup
    {
        return $this->scoredPassLoopup;
    }

    public function setScoredPassLoopup(emrScoredPassLookup $scoredPassLoopup): void
    {
        $this->scoredPassLoopup = $scoredPassLoopup;
    }

    public function render(ilMatrixResultsExportExcel $excel, int $firstRow): int
    {
        $row = $this->renderTestTitle($excel, $firstRow);
        $row = $this->renderParticipantLabels($excel, $row);
        $row = $this->renderParticipantTimes($excel, $row);
        $row = $this->renderParticipantNames($excel, ++$row);

        return $row;
    }

    protected function renderTestTitle(ilMatrixResultsExportExcel $excel, int $row): int
    {
        $start = $excel->getCoordByColumnAndRow(0, $row);
        $end = $excel->getCoordByColumnAndRow(3, $row + 3);
        $range = $start . ':' . $end;

        $excel->mergeCells($range);

        $excel->setBold($start);
        $excel->setCellByCoordinates($start, $this->getTestOBJ()->getTitle());
        $excel->setAlignTop($start);

        return $row;
    }

    protected function renderParticipantLabels(ilMatrixResultsExportExcel $excel, int $row): int
    {
        $excel->mergeCells(
            "{$excel->getCoordByColumnAndRow(4, $row + 0)}:{$excel->getCoordByColumnAndRow(5, $row + 0)}"
        );
        $excel->mergeCells(
            "{$excel->getCoordByColumnAndRow(4, $row + 1)}:{$excel->getCoordByColumnAndRow(5, $row + 1)}"
        );
        $excel->mergeCells(
            "{$excel->getCoordByColumnAndRow(4, $row + 2)}:{$excel->getCoordByColumnAndRow(5, $row + 2)}"
        );
        $excel->mergeCells(
            "{$excel->getCoordByColumnAndRow(4, $row + 3)}:{$excel->getCoordByColumnAndRow(5, $row + 3)}"
        );

        $coord = $excel->getCoordByColumnAndRow(4, $row + 0);
        $excel->setCellByCoordinates($coord, $this->getPlugin()->txt('participants_workingtime'));
        $excel->setBold($coord);

        $coord = $excel->getCoordByColumnAndRow(4, $row + 1);
        $excel->setCellByCoordinates($coord, $this->getPlugin()->txt('participants_lastname'));
        $excel->setBold($coord);

        $coord = $excel->getCoordByColumnAndRow(4, $row + 2);
        $excel->setCellByCoordinates($coord, $this->getPlugin()->txt('participants_firstname'));
        $excel->setBold($coord);

        $coord = $excel->getCoordByColumnAndRow(4, $row + 3);
        $excel->setCellByCoordinates($coord, $this->getPlugin()->txt('participants_login'));
        $excel->setBold($coord);

        return $row;
    }

    protected function renderParticipantTimes(ilMatrixResultsExportExcel $excel, int $row): int
    {
        $col = 6;

        foreach ($this->getParticipantData()->getActiveIds() as $activeId) {
            $workingTime = ilObjTest::_getWorkingTimeOfParticipantForPass(
                $activeId,
                $this->getScoredPassLoopup()->get($activeId)
            );

            $cellChord = $excel->getCoordByColumnAndRow($col, $row);

            $excel->setCellByCoordinates($cellChord, $excel->formatMinutes($workingTime));

            $col++;
        }

        return $row;
    }

    protected function renderParticipantNames(ilMatrixResultsExportExcel $excel, $row): int
    {
        $col = 6;

        foreach ($this->getParticipantData()->getActiveIds() as $activeId) {
            $data = $this->getParticipantData()->getUserDataByActiveId($activeId);

            $cellChord = $excel->getCoordByColumnAndRow($col, $row + 0);
            $excel->setCellByCoordinates($cellChord, $data['lastname']);

            $cellChord = $excel->getCoordByColumnAndRow($col, $row + 1);
            $excel->setCellByCoordinates($cellChord, $data['firstname']);

            $cellChord = $excel->getCoordByColumnAndRow($col, $row + 2);
            $excel->setCellByCoordinates($cellChord, $data['login']);

            $col++;
        }

        return $row + 2;
    }
}
