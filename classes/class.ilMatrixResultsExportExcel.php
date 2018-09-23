<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

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
	const COLOR_LIGHT_BLUE = 'ccffff';
	
	/**
	 * Save workbook to file
	 *
	 * @param string $a_file full path
	 */
	public function writeToFile($a_file)
	{
		$a_file = $this->prepareStorage($a_file);
		
		$writer = PHPExcel_IOFactory::createWriter($this->workbook, $this->format);
		$writer->setPreCalculateFormulas(true);
		$writer->save($a_file);
	}
	
	/**
	 * @param int $col
	 * @param int $width
	 */
	public function setColumnWidth($col, $width)
	{
		$col = $this->getColumnCoord($col);
		$this->workbook->getActiveSheet()->getColumnDimension($col)->setAutoSize(false)->setWidth($width);
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
			$bold ? PHPExcel_Style_Border::BORDER_THICK : PHPExcel_Style_Border::BORDER_THIN
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
			$bold ? PHPExcel_Style_Border::BORDER_THICK : PHPExcel_Style_Border::BORDER_THIN
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
			$bold ? PHPExcel_Style_Border::BORDER_THICK : PHPExcel_Style_Border::BORDER_THIN
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
			$bold ? PHPExcel_Style_Border::BORDER_THICK : PHPExcel_Style_Border::BORDER_THIN
		);
	}
	
	/**
	 * @param string $coords
	 * @param string $formula
	 */
	public function setFormulaByCoordinates($coords, $formula)
	{
		#$this->workbook->getActiveSheet()->setCellValueExplicit(
		#	$coords, $formula, PHPExcel_Cell_DataType::TYPE_FORMULA
		#);
		
		$this->workbook->getActiveSheet()->setCellValue($coords, $formula);
		$this->workbook->getActiveSheet()->getCell($coords)->getOldCalculatedValue();
		
		$this->workbook->getActiveSheet()->getStyle($coords)->getAlignment()->setHorizontal(
			PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		);
	}
	
	/**
	 * @param string $coords
	 * @param string $number
	 */
	public function setNumberByCoordinates($coords, $number)
	{
		#$this->workbook->getActiveSheet()->setCellValueExplicit(
		#	$coords, $number, PHPExcel_Cell_DataType::TYPE_NUMERIC
		#);
		
		$this->workbook->getActiveSheet()->setCellValue($coords, $number);
		
		$this->workbook->getActiveSheet()->getStyle($coords)->getAlignment()->setHorizontal(
			PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		);
	}
}
