<?php

/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

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
    protected function getFormatIdentifier(): string
    {
        return 'emr';
    }


    public function getFormatLabel(): string
    {
        return $this->txt('excel_matrix_results_label');
    }


    protected function buildExportFile(ilTestExportFilename $filename): void
    {
        global $DIC;
        $tpl = $DIC->ui()->mainTemplate();

        if (!$this->getTest()->isFixedTest()) {
            $tpl->setOnScreenMessage(
                ilGlobalTemplateInterface::MESSAGE_TYPE_FAILURE,
                $this->txt('failure_msg_only_fixed_tests'),
                true
            );
        }

        $exportBuilder = new ilExcelMatrixResultsExportBuilder($this->getTest());
        $exportBuilder->setPlugin($this);
        $exportBuilder->buildExcelMatrixFile($filename);
    }
}
