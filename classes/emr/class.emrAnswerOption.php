<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrAnswerOption
{
	/**
	 * @var array
	 */
	protected $answeringActiveIds = array();
	
	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var float
	 */
	protected $points = 0;
	
	
	/**
	 * @param integer $activeId
	 * @return bool
	 */
	public function hasActiveIdAnswered($activeId)
	{
		return isset($this->answeringActiveIds[$activeId]);
	}
	
	/**
	 * @param integer $activeId
	 */
	public function addAnsweringActiveId($activeId)
	{
		$this->answeringActiveIds[$activeId] = $activeId;
	}
	
	/**
	 * @return int
	 */
	public function getAnsweringFrequency()
	{
		return count($this->answeringActiveIds[$activeId]);
	}
	
	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	/**
	 * @return float
	 */
	public function getPoints()
	{
		return $this->points;
	}
	
	/**
	 * @param float $points
	 */
	public function setPoints($points)
	{
		$this->points = $points;
	}
	
	/**
	 * @return bool
	 */
	public function hasPoints()
	{
		return $this->getPoints() > 0;
	}
}