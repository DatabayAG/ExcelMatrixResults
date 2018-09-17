<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

interface emrAnswerOptionList
{
	/**
	 * @param assQuestion $questionOBJ
	 */
	public function __construct(assQuestion $questionOBJ);
	
	/**
	 * @param integer $pass
	 * @return void
	 */
	public function initialise($activeIds);
}