<?php
namespace ScoutNet\ShScoutnetWebservice\Helpers;

use ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetExceptionMissingConfVar;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/***************************************************************
*  Copyright notice
*
*  (c) 2009 Stefan Horst <s.horst@dpsg-koeln.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Service "SN" for the "sh_scoutnet_webservice" extension.
 *
 * @author	Stefan Horst <muetze@scoutnet.de>
 * @package	TYPO3
 * @subpackage	tx_shscoutnetwebservice
 */
class ScoutNetConnectHelper {
	/**
	 * @var array
	 */
	protected $settings;
	protected $extConfig;

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 * @return void
	 */
	public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
		$this->settings = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);
		$this->extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sh_scoutnet_webservice']);
	}

    /**
     * @param string $returnUrl
     * @param bool   $requestApiKey
     *
     * @return string
     * @throws \ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetExceptionMissingConfVar
     */
	public function getScoutNetConnectLoginButton($returnUrl = '',$requestApiKey = false){
	    // TODO: use a template here!!
		$lang = $GLOBALS['LANG']->lang;

		if ($lang == 'default') 
			$lang = 'en';

		$this->_checkConfigValues();
		$button = '<form action="'.$this->extConfig['ScoutnetLoginPage'].'" id="scoutnetLogin" method="post" target="_self">';

		$button .= $returnUrl == ''?'':'<input type="hidden" name="redirect_url" value="'.$returnUrl.'" />';
		$button .= '<input type="hidden" name="lang" value="'.$lang.'"/>';
		$button .= '<input type="hidden" name="provider" value="'.$this->extConfig['ScoutnetProviderName'].'" />';
		$button .= $requestApiKey?'<input type="hidden" name="createApiKey" value="1" />':'';
		
		$button .= '<a href="#" onclick="document.getElementById(\'scoutnetLogin\').submit(); return false;">';

		$button .= '<img src="'. PathUtility::getAbsoluteWebPath(ExtensionManagementUtility::extPath('sh_scoutnet_webservice').'Resources/Public/Images/scoutnetConnect.png').'" title="scoutnet" alt="scoutnet"/>';
		$button .= '</a>';
		
		$button .= '</form>';

		return $button;
	}

    /**
     * @throws \ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetExceptionMissingConfVar
     */
	private function _checkConfigValues(){
		$configVars = array('AES_key','AES_iv','ScoutnetLoginPage','ScoutnetProviderName');

		foreach ($configVars as $configVar) {
			if (trim($this->extConfig[$configVar]) == '')
				throw new ScoutNetExceptionMissingConfVar($configVar);
		}
	}
}
