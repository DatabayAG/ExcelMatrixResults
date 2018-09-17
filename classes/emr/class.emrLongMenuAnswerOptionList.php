<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class emrLongMenuAnswerOptionList
 *
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrLongMenuAnswerOptionList implements emrAnswerOptionList, Iterator
{
	use emrAnswerOptionListIterator;
	
	/**
	 * @var assLongMenu
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