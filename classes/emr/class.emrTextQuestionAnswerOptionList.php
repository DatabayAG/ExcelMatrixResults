<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class emrTextQuestionAnswerOptionList
 *
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrTextQuestionAnswerOptionList implements emrAnswerOptionList, Iterator
{
	use emrAnswerOptionListIterator;
	
	/**
	 * @var assTextQuestion
	 */
	protected $questionOBJ;
	
	/**
	 * emrSingleChoiceAnswerOptionList constructor.
	 * @param assQuestion $questionOBJ
	 */
	public function __construct(assQuestion $questionOBJ)
	{
		$this->questionOBJ = $questionOBJ;
	}
}