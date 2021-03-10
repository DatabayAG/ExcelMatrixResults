<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once 'Modules/Test/classes/class.ilTestExportPlugin.php';

/**
 * Class ilExcelMatrixResultsPlugin
 *
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 *
 * @package    Plugins/ExcelMatrixResults
 */
class ilExcelMatrixResultsPlugin extends ilTestExportPlugin
{
    /**
     * Get Plugin Name.
     * Must be same as in class name il<Name>Plugin
     * and must correspond to plugins subdirectory name.
     * Must be overwritten in plugin class of plugin
     * (and should be made final)
     *
     * @return string Plugin Name
     */
    public function getPluginName()
    {
        return 'ExcelMatrixResults';
    }
    
    /**
     *
     * @return string
     */
    protected function getFormatIdentifier()
    {
        return 'emr';
    }
    
    /**
     *
     * @return string
     */
    public function getFormatLabel()
    {
        return $this->txt('excel_matrix_results_label');
    }
    
    protected function includeClasses()
    {
        require_once 'Modules/TestQuestionPool/classes/class.ilAssExcelFormatHelper.php';
        $this->includeClass('class.ilMatrixResultsExportExcel.php');
        
        $this->includeClass('class.ilExcelMatrixResultsExportBuilder.php');
        
        $this->includeClass('emr/class.emrScoredPassLookup.php');
        $this->includeClass('emr/class.emrTotalQuestionPointsRowCollector.php');
        
        $this->includeClass('emr/interface.emrAnswerOptionList.php');
        $this->includeClass('emr/trait.emrAnswerOptionListIterator.php');
        $this->includeClass('emr/class.emrSingleChoiceAnswerOptionList.php');
        $this->includeClass('emr/class.emrLongMenuAnswerOptionList.php');
        $this->includeClass('emr/class.emrTextQuestionAnswerOptionList.php');

        $this->includeClass('emr/class.emrAnswerOption.php');
        
        $this->includeClass('emr/interface.emrExcelRangeRenderer.php');
        $this->includeClass('emr/class.emrExportHeaderRenderer.php');
        $this->includeClass('emr/class.emrExportSummaryRenderer.php');
        $this->includeClass('emr/class.emrQuestionGroupHeaderRenderer.php');
        $this->includeClass('emr/class.emrExportMatrixRendererAbstract.php');
        $this->includeClass('emr/class.emrSingleChoiceExportMatrixRenderer.php');
        $this->includeClass('emr/class.emrLongMenuExportMatrixRenderer.php');
        $this->includeClass('emr/class.emrTextQuestionExportMatrixRenderer.php');
    }
    
    /**
     *
     * @param ilTestExportFilename $filename
     */
    protected function buildExportFile(ilTestExportFilename $filename)
    {
        if (!$this->getTest()->isFixedTest()) {
            ilUtil::sendFailure($this->txt('failure_msg_only_fixed_tests'));
            return;
        }

        $this->includeClasses();
        
        $exportBuilder = new ilExcelMatrixResultsExportBuilder($this->getTest());
        $exportBuilder->setPlugin($this);
        $exportBuilder->ensureExistingExportDirectory();
        
        return $exportBuilder->buildExportFile();
    }
}
