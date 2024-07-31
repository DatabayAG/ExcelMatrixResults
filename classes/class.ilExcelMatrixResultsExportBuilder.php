<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

use ILIAS\Filesystem\Util\LegacyPathHelper;
use ILIAS\Refinery\Factory as Refinery;

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
    
    protected ilLanguage $lang;
    protected ilTestParticipantData $participantData;
    protected ilExcelMatrixResultsPlugin $plugin;
    protected Refinery $refinery;
    
    public function __construct(ilObjTest $testObject)
    {
        global $DIC;

        parent::__construct($testObject, 'results');
        
        $this->lang = $DIC->language();
        $this->refinery = $DIC->refinery();
        $db = $DIC->database();
        
        $this->participantData = new ilTestParticipantData($db, $this->lang);
        $this->participantData->load($this->test_obj->getTestId());
    }
    
    public function getPlugin(): ilExcelMatrixResultsPlugin
    {
        return $this->plugin;
    }
    
    public function setPlugin(ilExcelMatrixResultsPlugin $plugin)
    {
        $this->plugin = $plugin;
    }
    
    // never used methods dealing with test object export stuff this class is never used for
    protected function initXmlExport()
    {
    }
    protected function getQuestionIds()
    {
    }
    protected function populateQuestionSetConfigXml(ilXmlWriter $xmlWriter)
    {
    }
    protected function getQuestionsQtiXml()
    {
    }
    
    public function ensureExistingExportDirectory()
    {
        $absolute_path = $this->test_obj->getExportDirectory();
        $relative_path = LegacyPathHelper::createRelativePath($absolute_path);
        $filesystem = LegacyPathHelper::deriveFilesystemFrom($absolute_path);
        if (!$filesystem->hasDir($relative_path)) {
            $filesystem->createDir($relative_path);
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
    public function buildExportFile(): string
    {
        $excel = new ilMatrixResultsExportExcel();
        $this->addTestPassMatrixWorkSheet($excel);
        
        $filename = $this->test_obj->getExportDirectory() . "/" . $this->getFixedFilename();
        $excel->writeToFile($filename);
        return $filename;
    }
    
    protected function addTestPassMatrixWorkSheet(ilMatrixResultsExportExcel $excel)
    {
        $excel->addSheet($this->lang->txt('tst_results'));

        $this->setColumnsDimension($excel);
        $this->freezeLabelColsAndRows($excel);
        
        $scoredPassLookup = new emrScoredPassLookup();
        $totalQstPointsRowCollector = new emrTotalQuestionPointsRowCollector();
        
        $renderer = $this->getParticipantsHeaderRenderer($scoredPassLookup);
        $lastRow = $renderer->render($excel, $firstRow = 1);
        
        $lastGroupTitle = '';
        
        foreach ($this->getQuestions() as $questionId => $questionOBJ) {
            $groupTitle = $this->parseQuestionGroupTitle($questionOBJ->getTitle());
            
            if ($groupTitle != $lastGroupTitle) {
                $lastGroupTitle = $groupTitle;
                
                $renderer = $this->getQuestionGroupRenderer($groupTitle);
                $lastRow = $renderer->render($excel, $lastRow + 1);
            }
            
            foreach ($this->getExportAnswerOptionLists($questionOBJ) as $subIndex => $exportAnswerOptionList) {
                $exportAnswerOptionList->initialise(
                    $this->participantData->getActiveIds(),
                    $scoredPassLookup
                );
                
                $exportMatrixRenderer = $this->getExportMatrixRenderer($questionOBJ);
                $exportMatrixRenderer->setQstPointsRowCollector($totalQstPointsRowCollector);
                $exportMatrixRenderer->setSubIndex($subIndex);
                $exportMatrixRenderer->setParticipantData($this->participantData);
                $exportMatrixRenderer->setAnswerOptionList($exportAnswerOptionList);
                
                $lastRow = $exportMatrixRenderer->render($excel, $lastRow + 1);
            }
        }
        
        $summaryRenderer = $this->getExportSummaryRenderer();
        $summaryRenderer->setQstPointsRowCollector($totalQstPointsRowCollector);
        $lastRow = $summaryRenderer->render($excel, $lastRow + 2);
    }
    
    protected function getExportSummaryRenderer(): emrExportSummaryRenderer
    {
        $summaryRenderer = new emrExportSummaryRenderer();
        $summaryRenderer->setPlugin($this->getPlugin());
        $summaryRenderer->setParticipantData($this->participantData);
        return $summaryRenderer;
    }
    
    /**
     * @return assQuestion[]
     */
    protected function getQuestions(): array
    {
        $questions = array();
        
        foreach ($this->test_obj->getTestQuestions() as $q) {
            $question = assQuestion::_instantiateQuestion($q['question_id']);
            
            if (!$this->isSupportedQuestionType($question->getQuestionType())) {
                continue;
            }
            
            $questions[$q['question_id']] = $question;
        }
        
        return $questions;
    }
    
    protected function getParticipantsHeaderRenderer(emrScoredPassLookup $scoredPassLookup): emrExportHeaderRenderer
    {
        $renderer = new emrExportHeaderRenderer();
        $renderer->setPlugin($this->getPlugin());
        $renderer->setTestOBJ($this->test_obj);
        $renderer->setParticipantData($this->participantData);
        $renderer->setScoredPassLoopup($scoredPassLookup);
        
        return $renderer;
    }
    
    /**
     * @return emrAnswerOptionList[]
     */
    protected function getExportAnswerOptionLists(assQuestion $questionOBJ): array
    {
        $exportAnswerOptionLists = array();
        
        switch ($questionOBJ->getQuestionType()) {
            case 'assLongMenu':
                
                /* @var assLongMenu $questionOBJ */
                foreach ($questionOBJ->getAnswers() as $lmIndex => $lm) {
                    $answerOptionList = new emrLongMenuAnswerOptionList($questionOBJ, $this->refinery);
                    $answerOptionList->setGapIndex($lmIndex);
                    
                    $exportAnswerOptionLists[$lmIndex] = $answerOptionList;
                }
                break;
                
            case 'assSingleChoice':
                
                $exportAnswerOptionLists[] = new emrSingleChoiceAnswerOptionList($questionOBJ, $this->refinery);
                break;
                
            case 'assTextQuestion':
                
                $exportAnswerOptionLists[] = new emrTextQuestionAnswerOptionList($questionOBJ, $this->refinery);
                break;
        }
        
        return $exportAnswerOptionLists;
    }
    
    protected function getExportMatrixRenderer(assQuestion $questionOBJ): emrExportMatrixRendererAbstract
    {
        switch ($questionOBJ->getQuestionType()) {
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
    
    protected function isSupportedQuestionType(string $questionType): bool
    {
        return in_array($questionType, $this->supportedQuestionTypes);
    }
    
    protected function getQuestionGroupRenderer(string $questionGroupTitle): emrQuestionGroupHeaderRenderer
    {
        return new emrQuestionGroupHeaderRenderer($questionGroupTitle);
    }
    
    protected function parseQuestionGroupTitle(string $questionTitle): string
    {
        $matches = null;
        
        if (preg_match('/^(.*?), (.*?)$/', $questionTitle, $matches)) {
            return $matches[1];
        }
        
        return '';
    }
    
    protected function setColumnsDimension(ilMatrixResultsExportExcel $excel)
    {
        $excel->setColumnWidth(0, 14.0);
        $excel->setColumnWidth(1, 10.0);
        $excel->setColumnWidth(2, 12.0);
        $excel->setColumnWidth(3, 14.0);
        $excel->setColumnWidth(4, 9.0);
        $excel->setColumnWidth(5, 11.0);
        
        $lastCol = 5 + count($this->participantData->getActiveIds());
        
        for ($col = 6; $col <= $lastCol; $col++) {
            $excel->setColumnWidth($col, 9.0);
        }
    }
    
    protected function freezeLabelColsAndRows(ilMatrixResultsExportExcel $excel)
    {
        $excel->setFirstNonFreezedCell('G5');
    }
}
