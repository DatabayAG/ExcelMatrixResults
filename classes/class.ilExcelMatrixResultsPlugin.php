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
    public function getPluginName(): string
    {
        return 'ExcelMatrixResults';
    }
    
    /**
     *
     * @return string
     */
    protected function getFormatIdentifier(): string
    {
        return 'emr';
    }
    
    /**
     *
     * @return string
     */
    public function getFormatLabel(): string
    {
        return $this->txt('excel_matrix_results_label');
    }

    /**
     *
     * @param ilTestExportFilename $filename
     */
    protected function buildExportFile(ilTestExportFilename $filename)
    {
        global $DIC;
        $tpl = $DIC->ui()->mainTemplate();

        if (!$this->getTest()->isFixedTest()) {
            $tpl->setOnScreenMessage(ilGlobalTemplateInterface::MESSAGE_TYPE_FAILURE,
                $this->txt('failure_msg_only_fixed_tests'), true);
            return '';
        }
        
        $exportBuilder = new ilExcelMatrixResultsExportBuilder($this->getTest());
        $exportBuilder->setPlugin($this);
        $exportBuilder->ensureExistingExportDirectory();
        
        return $exportBuilder->buildExportFile();
    }
}
