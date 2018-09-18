<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrScoredPassLookup
{
	/**
	 * @var array
	 */
	protected $scoredPassByActiveId = array();
	
	/**
	 * @param int $activeId
	 * @return int
	 */
	protected function load($activeId)
	{
		return ilObjTest::_getResultPass($activeId);
	}
	
	/**
	 * @param int $activeId
	 * @return int
	 */
	public function get($activeId)
	{
		if( !isset($this->scoredPassByActiveId[$activeId]) )
		{
			$this->scoredPassByActiveId[$activeId] = $this->load($activeId);
		}
		
		return $this->scoredPassByActiveId[$activeId];
	}
}