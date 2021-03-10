<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class emrSingleChoiceExportMatrixRenderer extends emrExportMatrixRendererAbstract
{
    /**
     * @var emrSingleChoiceAnswerOptionList
     */
    protected $answerOptionList;

    /**
     * @var assSingleChoice
     */
    protected $questionOBJ;
    
    /**
     * emrSingleChoiceExportMatrixRenderer constructor.
     * @param assQuestion $questionOBJ
     */
    public function __construct(assQuestion $questionOBJ)
    {
        $this->questionOBJ = $questionOBJ;
    }
    
    /**
     * @return string
     */
    protected function getQuestionTypeLabel()
    {
        return $this->getPlugin()->txt('qst_type_label_singlechoice');
    }
    
    /**
     * @param ilMatrixResultsExportExcel $excel
     * @param int $firstRow
     * @return int $lastRow
     */
    public function render(ilMatrixResultsExportExcel $excel, $firstRow)
    {
        $row = $firstRow;
        
        $this->renderFrontBorder($excel, $row);
        
        $this->renderQuestionTitle($excel, $row, $this->questionOBJ->getTitle());

        $this->renderQuestionType($excel, ++$row);
        $this->renderQuestionHeader($excel, $row);
        
        $this->renderAnswerOptionLabels($excel, ++$row);
        $this->renderAnswerOptionPoints($excel, $row);
        $this->renderAnswerOptionFrequencyFormula($excel, $row);
        $this->renderParticipantsAnswerings($excel, $row);
        
        $this->renderTotalPoints($excel, $row, $this->answerOptionList);
        
        $this->qstPointsRowCollector->addTotalQuestionPointsRow(
            $row + $this->answerOptionList->getNumAnswers()
        );
        
        return $firstRow + $this->getAnswerOptionList()->getNumAnswers() + 3;
    }
}
