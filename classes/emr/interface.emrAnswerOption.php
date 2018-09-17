<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

interface emrAnswerOption
{
	/**
	 * @return string
	 */
	public function getTitle();
	
	/**
	 * @return integer
	 */
	public function getPoints();
	
	/**
	 * @param integer $activeId
	 * @return bool
	 */
	public function hasAnswered($activeId);
}