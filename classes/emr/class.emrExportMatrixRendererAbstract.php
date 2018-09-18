<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
abstract class emrExportMatrixRendererAbstract implements emrExcelRangeRenderer
{
	/**
	 * @var emrAnswerOptionList
	 */
	protected $answerOptionList;

	/**
	 * emrExportMatrixRenderer constructor.
	 * @param assQuestion $questionOBJ
	 */
	abstract public function __construct(assQuestion $questionOBJ);
	
	
	/**
	 * @return emrAnswerOptionList
	 */
	public function getAnswerOptionList()
	{
		return $this->answerOptionList;
	}
	
	/**
	 * @param emrAnswerOptionList $answerOptionList
	 */
	public function setAnswerOptionList($answerOptionList)
	{
		$this->answerOptionList = $answerOptionList;
	}
}