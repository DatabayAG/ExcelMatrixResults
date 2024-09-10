<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style;

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class ilMatrixResultsExportExcel extends ilAssExcelFormatHelper
{
    /**
     * colors
     */
    public const COLOR_GREY = 'C0C0C0';
    public const COLOR_LIGHT_BLUE = 'ccffff';
    public const COLOR_LIGHT_YELLOW = 'ffff99';

    /**
     * Save workbook to file
     *
     * @param string $a_file full path
     */
    public function writeToFile(string $a_file): void
    {
        $a_file = $this->prepareStorage($a_file);

        $writer = IOFactory::createWriter($this->workbook, self::FORMAT_XML);
        $writer->setPreCalculateFormulas(true);
        $writer->save($a_file);
    }

    /**
     * NOT set all column autosize
     */
    public function setGlobalAutoSize(): void
    {
        // do nothing - let us decide for column widths our self
    }


    public function setFirstNonFreezedCell(string $coord): void
    {
        $this->workbook->getActiveSheet()->freezePane($coord);
    }

    public function setColumnWidth(int $col, float $width): void
    {
        $col = $this->getColumnCoord($col);
        $this->workbook->getActiveSheet()->getColumnDimension($col)->setAutoSize(false);
        $this->workbook->getActiveSheet()->getColumnDimension($col)->setWidth($width);
    }

    public function setAlignTop(string $coords): void
    {
        $this->workbook->getActiveSheet()->getStyle($coords)->getAlignment()->setVertical(
            Style\Alignment::VERTICAL_TOP
        );
    }

    public function setAlignRight(string $coords): void
    {
        $this->workbook->getActiveSheet()->getStyle($coords)->getAlignment()->setHorizontal(
            Style\Alignment::HORIZONTAL_RIGHT
        );
    }

    public function setDefaultColumnWidth(float $width): void
    {
        $this->workbook->getActiveSheet()->getDefaultColumnDimension()->setWidth($width);
    }

    public function formatMinutes(int $seconds): string
    {
        $mins = (int) ($seconds / 60);
        $secs = (int) ($seconds % 60);
        return sprintf("%02d:%02d", $mins, $secs);
    }

    public function setBorderTop(string $rangeCoords, bool $bold = false): void
    {
        $style = $this->workbook->getActiveSheet()->getStyle($rangeCoords);

        $style->getBorders()->getTop()->setBorderStyle(
            $bold ? Style\Border::BORDER_THICK : Style\Border::BORDER_THIN
        );
    }

    public function setBorderRight(string $rangeCoords, bool $bold = false): void
    {
        $style = $this->workbook->getActiveSheet()->getStyle($rangeCoords);

        $style->getBorders()->getRight()->setBorderStyle(
            $bold ? Style\Border::BORDER_THICK : Style\Border::BORDER_THIN
        );
    }

    /**
     * @param string $rangeCoords
     * @param bool $bold
     */
    public function setBorderBottom(string $rangeCoords, bool $bold = false): void
    {
        $style = $this->workbook->getActiveSheet()->getStyle($rangeCoords);

        $style->getBorders()->getBottom()->setBorderStyle(
            $bold ? Style\Border::BORDER_THICK : Style\Border::BORDER_THIN
        );
    }

    public function setBorderLeft(string $rangeCoords, bool $bold = false): void
    {
        $style = $this->workbook->getActiveSheet()->getStyle($rangeCoords);

        $style->getBorders()->getLeft()->setBorderStyle(
            $bold ? Style\Border::BORDER_THICK : Style\Border::BORDER_THIN
        );
    }

    public function setFormulaByCoordinates(string $coords, string $formula): void
    {
        $this->workbook->getActiveSheet()->setCellValue($coords, $formula);
        $this->workbook->getActiveSheet()->getCell($coords)->getOldCalculatedValue();

        $this->workbook->getActiveSheet()->getStyle($coords)->getAlignment()->setHorizontal(
            Style\Alignment::HORIZONTAL_RIGHT
        );
    }

    /**
     * @param int|string $number
     */
    public function setNumberByCoordinates(string $coords, $number): void
    {
        $this->workbook->getActiveSheet()->setCellValue($coords, $number);

        $this->workbook->getActiveSheet()->getStyle($coords)->getAlignment()->setHorizontal(
            Style\Alignment::HORIZONTAL_RIGHT
        );
    }
}
