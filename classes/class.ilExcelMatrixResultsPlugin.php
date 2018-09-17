<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once 'Modules/Test/classes/class.ilTestExportPlugin.php';

/**
 * Class ilExcelMatrixResultsPlugin
 *
 * @author    Björn Heyser <info@bjoernheyser.de>
 * @version    $Id$
 */
class ilExcelMatrixResultsPlugin extends ilTestExportPlugin {
	/**
	 * Get Plugin Name.
	 * Must be same as in class name il<Name>Plugin
	 * and must correspond to plugins subdirectory name.
	 * Must be overwritten in plugin class of plugin
	 * (and should be made final)
	 *
	 * @return string Plugin Name
	 */
	function getPluginName()
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
		return $this->txt( 'excel_matrix_results_label' );
	}
	
	/**
	 *
	 * @param ilTestExportFilename $filename        	
	 */
	protected function buildExportFile(ilTestExportFilename $filename)
	{
		if( !$this->getTest()->isFixedTest() )
		{
			ilUtil::sendFailure($this->txt('failure_msg_only_fixed_tests'));
			return;
		}

		require_once 'Modules/TestQuestionPool/classes/class.ilAssExcelFormatHelper.php';
		$this->includeClass('class.ilExcelMatrixResultsExportBuilder.php');
		
		$exportBuilder = new ilExcelMatrixResultsExportBuilder($this->getTest());
		$exportBuilder->buildExportFile();
	}
}