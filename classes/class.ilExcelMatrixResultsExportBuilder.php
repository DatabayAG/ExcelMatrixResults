<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once 'Modules/Test/classes/class.ilTestExport.php';
require_once 'Modules/Test/classes/class.ilTestParticipantData.php';
require_once 'Services/Tracking/classes/class.ilLPStatusWrapper.php';
require_once 'Services/Tracking/classes/class.ilLearningProgressBaseGUI.php';

/**
 * Class class.ilResultsAndProgressExportBuilder
 *
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 * 
 * @package    Plugins/ExcelMatrixResults
 */
class ilExcelMatrixResultsExportBuilder extends ilTestExport
{
	protected $supportedQuestionTypes = array(
		'assSingleChoice', 'assTextQuestion', 'assLongMenu'
	);
	
	/**
	 * @var ilLanguage
	 */
	protected $lang;
	
	/**
	 * @var ilTestParticipantData
	 */
	protected $participantData;
	
	/**
	 * @var ilExcelMatrixResultsPlugin
	 */
	protected $plugin;
	
	/**
	 * @param ilObjTest $testObject
	 */
	public function __construct(ilObjTest $testObject)
	{
		parent::__construct($testObject, 'results');
		
		$this->lang = isset($GLOBALS['DIC']) ? $GLOBALS['DIC']['lng'] : $GLOBALS['lng'];
		$db = isset($GLOBALS['DIC']) ? $GLOBALS['DIC']['ilDB'] : $GLOBALS['ilDB'];
		
		$this->participantData = new ilTestParticipantData($db, $this->lang);
		$this->participantData->load($this->test_obj->getTestId());
	}
	
	/**
	 * @return ilExcelMatrixResultsPlugin
	 */
	public function getPlugin()
	{
		return $this->plugin;
	}
	
	/**
	 * @param ilExcelMatrixResultsPlugin $plugin
	 */
	public function setPlugin($plugin)
	{
		$this->plugin = $plugin;
	}
	
	// never used methods dealing with test object export stuff this class is never used for
	protected function initXmlExport() {}
	protected function getQuestionIds() {}
	protected function populateQuestionSetConfigXml(ilXmlWriter $xmlWriter) {}
	protected function getQuestionsQtiXml() {}
	
	public function ensureExistingExportDirectory()
	{
		global $DIC; /* @var ILIAS\DI\Container $DIC */
		
		$exportDirectory = str_replace(
			ilUtil::getDataDir().'/', '', $this->export_dir
		);
		
		if( !$DIC->filesystem()->storage()->hasDir($exportDirectory) )
		{
			$DIC->filesystem()->storage()->createDir($exportDirectory);
		}
	}
	
	protected function getFixedFilename()
	{
		return str_replace($this->getExtension(), "xlsx", $this->filename);
	}
	
	/**
	 * MAIN EXPORT FUNCTION
	 * 
	 * @return string $exportFilename
	 */
	public function buildExportFile()
	{
		$excel = new ilMatrixResultsExportExcel();
		$this->addTestPassMatrixWorkSheet($excel);
		
		$filename = ilUtil::ilTempnam();
		$excel->writeToFile($filename);
		
		ilFileUtils::rename($filename.'.xlsx',
			$this->export_dir . "/" . $this->getFixedFilename()
		);
		
		return $this->export_dir."/".$this->getFixedFilename();
	}
	
	/**
	 * @param ilMatrixResultsExportExcel $excel
	 */
	protected function addTestPassMatrixWorkSheet(ilMatrixResultsExportExcel $excel)
	{
		$excel->addSheet($this->lang->txt('tst_results'));
		
		$scoredPassLookup = new emrScoredPassLookup();
		
		$renderer = $this->getParticipantsHeaderRenderer($scoredPassLookup);
		$lastRow = $renderer->render($excel, $firstRow = 1);
		
		$lastGroupTitle = '';
		
		foreach($this->getQuestions() as $questionId => $questionOBJ)
		{
			$groupTitle = $this->parseQuestionGroupTitle($questionOBJ->getTitle());
			
			if($groupTitle != $lastGroupTitle)
			{
				$lastGroupTitle = $groupTitle;
				
				$renderer = $this->getQuestionGroupRenderer($groupTitle);
				$lastRow = $renderer->render($excel, $lastRow + 1);
			}
			
			foreach($this->getExportAnswerOptionLists($questionOBJ) as $subIndex => $exportAnswerOptionList)
			{
				$exportAnswerOptionList->initialise(
					$this->participantData->getActiveIds(), $scoredPassLookup
				);
				
				$exportMatrixRenderer = $this->getExportMatrixRenderer($questionOBJ);
				$exportMatrixRenderer->setSubIndex($subIndex);
				$exportMatrixRenderer->setParticipantData($this->participantData);
				$exportMatrixRenderer->setAnswerOptionList($exportAnswerOptionList);
				
				$lastRow = $exportMatrixRenderer->render($excel, $lastRow + 1);
			}
		}
		
		$this->setColumnsDimension($excel);
	}
	
	/**
	 * @return assQuestion[]
	 */
	protected function getQuestions()
	{
		$questions = array();
		
		foreach($this->test_obj->getTestQuestions() as $q)
		{
			$question = assQuestion::_instantiateQuestion($q['question_id']);
			
			if( !$this->isSupportedQuestionType($question->getQuestionType()) )
			{
				continue;
			}
			
			$questions[$q['question_id']] = $question;
		}
		
		return $questions;
	}
	
	/**
	 * @param emrScoredPassLookup $scoredPassLookup
	 * @return emrExportHeaderRenderer
	 */
	protected function getParticipantsHeaderRenderer(emrScoredPassLookup $scoredPassLookup)
	{
		$renderer = new emrExportHeaderRenderer();
		$renderer->setTestOBJ($this->test_obj);
		$renderer->setParticipantData($this->participantData);
		$renderer->setScoredPassLoopup($scoredPassLookup);
		
		return $renderer;
	}
	
	/**
	 * @param assQuestion $questionOBJ
	 * @return emrAnswerOptionList[]
	 */
	protected function getExportAnswerOptionLists(assQuestion $questionOBJ)
	{
		$exportAnswerOptionLists = array();
		
		switch($questionOBJ->getQuestionType())
		{
			case 'assLongMenu':
				
				/* @var assLongMenu $questionOBJ */
				foreach( $questionOBJ->getAnswers() as $lmIndex => $lm )
				{
					$answerOptionList = new emrLongMenuAnswerOptionList($questionOBJ);
					$answerOptionList->setGapIndex($lmIndex);
					
					$exportAnswerOptionLists[$lmIndex] = $answerOptionList;
				}
				break;
				
			case 'assSingleChoice':
				
				$exportAnswerOptionLists[] = new emrSingleChoiceAnswerOptionList($questionOBJ);
				break;
				
			case 'assTextQuestion':
				
				$exportAnswerOptionLists[] = new emrTextQuestionAnswerOptionList($questionOBJ);
				break;
		}
		
		return $exportAnswerOptionLists;
	}
	
	/**
	 * @param assQuestion $questionOBJ
	 * @return emrExportMatrixRendererAbstract
	 */
	protected function getExportMatrixRenderer(assQuestion $questionOBJ)
	{
		switch($questionOBJ->getQuestionType())
		{
			case 'assSingleChoice':
				$exportMatrixRenderer = new emrSingleChoiceExportMatrixRenderer($questionOBJ);
				break;
			case 'assLongMenu':
				$exportMatrixRenderer = new emrLongMenuExportMatrixRenderer($questionOBJ);
				break;
			case 'assTextQuestion':
				$exportMatrixRenderer = new emrTextQuestionExportMatrixRenderer($questionOBJ);
				break;
			default: $exportMatrixRenderer = null;
		}
		
		$exportMatrixRenderer->setPlugin($this->getPlugin());
		
		return $exportMatrixRenderer;
	}
	
	/**
	 * @param string $questionType
	 * @return bool
	 */
	protected function isSupportedQuestionType($questionType)
	{
		return in_array($questionType, $this->supportedQuestionTypes);
	}
	
	/**
	 * @param string $questionGroupTitle
	 * @return emrQuestionGroupHeaderRenderer
	 */
	protected function getQuestionGroupRenderer($questionGroupTitle)
	{
		return new emrQuestionGroupHeaderRenderer($questionGroupTitle);
	}
	
	/**
	 * @param string $questionTitle
	 * @return string
	 */
	protected function parseQuestionGroupTitle($questionTitle)
	{
		$matches = null;
		
		if( preg_match('/^(.*?), (.*?)$/', $questionTitle, $matches) )
		{
			return $matches[1];
		}
		
		return '';
	}
	
	protected function setColumnsDimension(ilMatrixResultsExportExcel $excel)
	{
		for($col = 0; $col <= 5; $col++)
		{
			$excel->setColumnWidth($col, 100);
		}
		
		$lastCol = 5 + count($this->participantData->getActiveIds());
		
		for($col = 6; $col <= $lastCol; $col++)
		{
			$excel->setColumnWidth($col, 100);
		}
	}
}