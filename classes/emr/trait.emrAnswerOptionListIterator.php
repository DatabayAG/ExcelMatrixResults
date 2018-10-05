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
	 * @param string $key
	 */
	protected function addAnswerOption(emrAnswerOption $answerOption, $key)
	{
		$this->answerOptions[$key] = $answerOption;
	}
	
	/**
	 * @param string $key
	 */
	protected function getAnswerOption($key)
	{
		return $this->answerOptions[$key];
	}
	
	/**
	 * @param $key
	 * @return bool
	 */
	protected function answerOptionExists($key)
	{
		return strlen($key) && isset($this->answerOptions[$key]);
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
	
	/**
	 * @return int
	 */
	public function getNumAnswers()
	{
		return count($this->answerOptions);
	}
}