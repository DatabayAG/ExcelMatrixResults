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
	const COLOR_GREY = 'C0C0C0';
	const COLOR_LIGHT_BLUE = 'ccffff';
	const COLOR_LIGHT_YELLOW = 'ffff99';
	
	/**
	 * Save workbook to file
	 *
	 * @param string $a_file full path
	 */
	public function writeToFile($a_file)
	{
		$a_file = $this->prepareStorage($a_file);
		
		$writer = IOFactory::createWriter($this->workbook, $this->format);
		$writer->setPreCalculateFormulas(true);
		$writer->save($a_file);
	}
	
	/**
	 * NOT set all column autosize
	 */
	public function setGlobalAutoSize()
	{
		// do nothing - let us decide for column widths our self
	}
	
	/**
	 * @param string $coord
	 */
	public function setFirstNonFreezedCell($coord)
	{
		$this->workbook->getActiveSheet()->freezePane($coord);
	}
	
	/**
	 * @param int $col
	 * @param float $width
	 */
	public function setColumnWidth($col, $width)
	{
		$col = $this->getColumnCoord($col);
		$this->workbook->getActiveSheet()->getColumnDimension($col)->setAutoSize(false);
		$this->workbook->getActiveSheet()->getColumnDimension($col)->setWidth($width);
	}
	
	public function setAlignTop($coords)
	{
		$this->workbook->getActiveSheet()->getStyle($coords)->getAlignment()->setVertical(
			Style\Alignment::VERTICAL_TOP
		);
	}
	
	public function setAlignRight($coords)
	{
		$this->workbook->getActiveSheet()->getStyle($coords)->getAlignment()->setHorizontal(
            Style\Alignment::HORIZONTAL_RIGHT
		);
	}
	
	/**
	 * @param float $width
	 */
	public function setDefaultColumnWidth($width)
	{
		$this->workbook->getActiveSheet()->getDefaultColumnDimension()->setWidth($width);
	}
	
	/**
	 * @param int $seconds
	 * @return string
	 */
	public function formatMinutes($seconds)
	{
		$mins = (int)($seconds / 60);
		$secs = (int)($seconds % 60);
		return sprintf("%02d:%02d", $mins, $secs);
	}
	
	/**
	 * @param string $rangeCoords
	 */
	public function mergeCells($rangeCoords)
	{
		$this->workbook->getActiveSheet()->mergeCells($rangeCoords);
	}
	
	/**
	 * @param string $rangeCoords
	 * @param bool $bold
	 */
	public function setBorderTop($rangeCoords, $bold = false)
	{
		$style = $this->workbook->getActiveSheet()->getStyle($rangeCoords);
		
		$style->getBorders()->getTop()->setBorderStyle(
			$bold ? Style\Border::BORDER_THICK : Style\Border::BORDER_THIN
		);
	}
	
	/**
	 * @param string $rangeCoords
	 * @param bool $bold
	 */
	public function setBorderRight($rangeCoords, $bold = false)
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
	public function setBorderBottom($rangeCoords, $bold = false)
	{
		$style = $this->workbook->getActiveSheet()->getStyle($rangeCoords);
		
		$style->getBorders()->getBottom()->setBorderStyle(
			$bold ? Style\Border::BORDER_THICK : Style\Border::BORDER_THIN
		);
	}
	
	/**
	 * @param string $rangeCoords
	 * @param bool $bold
	 */
	public function setBorderLeft($rangeCoords, $bold = false)
	{
		$style = $this->workbook->getActiveSheet()->getStyle($rangeCoords);
		
		$style->getBorders()->getLeft()->setBorderStyle(
			$bold ? Style\Border::BORDER_THICK : Style\Border::BORDER_THIN
		);
	}
	
	/**
	 * @param string $coords
	 * @param string $formula
	 */
	public function setFormulaByCoordinates($coords, $formula)
	{
		$this->workbook->getActiveSheet()->setCellValue($coords, $formula);
		$this->workbook->getActiveSheet()->getCell($coords)->getOldCalculatedValue();
		
		$this->workbook->getActiveSheet()->getStyle($coords)->getAlignment()->setHorizontal(
            Style\Alignment::HORIZONTAL_RIGHT
		);
	}
	
	/**
	 * @param string $coords
	 * @param string $number
	 */
	public function setNumberByCoordinates($coords, $number)
	{
		$this->workbook->getActiveSheet()->setCellValue($coords, $number);
		
		$this->workbook->getActiveSheet()->getStyle($coords)->getAlignment()->setHorizontal(
            Style\Alignment::HORIZONTAL_RIGHT
		);
	}
}
