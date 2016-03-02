<?php
namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

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
class AbstractRepository { //extends \TYPO3\CMS\Core\Service\AbstractService {
	/**
	 * @var \ScoutNet\ShScoutnetWebservice\Helpers\AuthHelper
	 * @inject
	 */
	protected $authHelper = null;


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
	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
		$this->settings = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);
		$this->extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sh_scoutnet_webservice']);
	}


	var $prefixId = 'tx_shscoutnetwebservice_sn';		// Same as class name
	var $scriptRelPath = 'sn/class.tx_shscoutnetwebservice_sn.php';	// Path to this script relative to the extension dir.
	var $extKey = 'sh_scoutnet_webservice';	// The extension key.
	var $iv = '1234567890123456';

	var $SN = null;


	public function __construct()	{
		//ini_set('default_socket_timeout',1);
		$this->SN = new \ScoutNet\ShScoutnetWebservice\Helpers\JsonRPCClientHelper("http://api.scoutnet.dev/jsonrpc/");//, true); // TODO: configure this and configure debug
	}

	protected function load_data_from_scoutnet($ids,$query){
		$res = $this->SN->get_data_by_global_id($ids,$query);

		return $res;
	}

}

