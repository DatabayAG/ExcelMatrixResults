<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

trait emrAnswerOptionListIterator
{
	/**
	 * @var emrAnswerOption[]
	 */
	protected $answerOptions = array();
	
	/**
	 * @param emrAnswerOption $answerOption
	 */
	protected function addAnswerOption(emrAnswerOption $answerOption)
	{
		$this->answerOptions[] = $answerOption;
	}
	
	/**
	 * @return emrAnswerOption
	 */
	public function current()
	{
		return current($this->answerOptions);
	}
	
	/**
	 * @return emrAnswerOption
	 */
	public function next()
	{
		return next($this->answerOptions);
	}
	
	/**
	 * @return integer
	 */
	public function key()
	{
		return key($this->answerOptions);
	}
	
	/**
	 * @return bool
	 */
	public function valid()
	{
		return key($this->answerOptions) !== null;
	}
	
	/**
	 * @return emrAnswerOption
	 */
	public function rewind()
	{
		return reset($this->answerOptions);
	}
}