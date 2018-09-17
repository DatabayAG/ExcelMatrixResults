<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class emrSingleChoiceAnswerOptionList
 *
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrSingleChoiceAnswerOptionList implements emrAnswerOptionList, Iterator
{
	use emrAnswerOptionListIterator;
	
	/**
	 * @var assSingleChoice
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