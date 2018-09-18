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
	 * @var int
	 */
	protected $gapIndex;
	
	/**
	 * emrSingleChoiceAnswerOptionList constructor.
	 * @param assQuestion $questionOBJ
	 */
	public function __construct(assQuestion $questionOBJ)
	{
		$this->questionOBJ = $questionOBJ;
		
		$this->gapIndex = 0;
	}
	
	/**
	 * @return int
	 */
	public function getGapIndex()
	{
		return $this->gapIndex;
	}
	
	/**
	 * @param int $gapIndex
	 */
	public function setGapIndex($gapIndex)
	{
		$this->gapIndex = $gapIndex;
	}
	
	/**
	 * @param integer[] $activeIds
	 * @param emrScoredPassLookup $scoredPassLoopup
	 */
	public function initialise($activeIds, emrScoredPassLookup $scoredPassLoopup)
	{
		$this->initCorrectAnswers();
		$this->initWrongAnswers();
		
		foreach($activeIds as $activeId)
		{
			$pass = $scoredPassLoopup->get($activeId);
			$rows = $this->questionOBJ->getSolutionValues($activeId, $pass);
			
			foreach($rows as $row)
			{
				if( $row['value1'] != $this->getGapIndex() )
				{
					continue;
				}
				
				if( $this->answerOptionExists($row['value2']) )
				{
					$answerOption = $this->getAnswerOption($row['value2']);
					$answerOption->addAnsweringActiveId($activeId);
				}
				else
				{
					$answerOption = new emrAnswerOption();
					$answerOption->setTitle('# '.$row['value2']);
					$answerOption->setPoints(0);
					
					$answerOption->addAnsweringActiveId($activeId);
					
					$this->addAnswerOption($answerOption, $row['value2']);
				}
			}
		}
	}
	
	public function initCorrectAnswers()
	{
		foreach($this->questionOBJ->getCorrectAnswers() as $gapIndex => $gapData)
		{
			if( $gapIndex != $this->getGapIndex() )
			{
				continue;
			}
			
			foreach($gapData[0] as $answertext)
			{
				$answerOption = new emrAnswerOption();
				$answerOption->setTitle($answertext);
				$answerOption->setPoints($gapData[1]);
				
				$this->addAnswerOption($answerOption, $answertext);
			}
		}
	}
	
	protected function initWrongAnswers()
	{
		foreach($this->questionOBJ->getAnswers() as $gapIndex => $gapAnswers)
		{
			if( $gapIndex != $this->getGapIndex() )
			{
				continue;
			}

			foreach($gapAnswers as $answer)
			{
				if( $this->answerOptionExists($answer) )
				{
					continue;
				}
				
				$answerOption = new emrAnswerOption();
				$answerOption->setTitle('# '.$answer);
				$answerOption->setPoints(0);
				
				$this->addAnswerOption($answerOption, $answer);
			}
		}
	}
}