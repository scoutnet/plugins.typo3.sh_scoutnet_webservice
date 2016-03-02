<?php
namespace ScoutNet\ShScoutnetWebservice\Helpers;

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
class AuthHelper {
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

	/**
	 * Stores the Login Data
	 * @var array
	 */
	private $snData = null;

	const UNSECURE_START_IV = '1234567890123456';

	public function getApiKeyFromData($data){
		if (isset($this->snData)) {
			return $this->snData;
		}    

		$this->_checkConfigValues();

		$z = $this->extConfig['AES_key'];
		$iv = $this->extConfig['AES_iv'];

		$aes = new \ScoutNet\ShScoutnetWebservice\Helpers\AESHelper($z,"CBC",$iv);

		$base64 = base64_decode(strtr($data, '-_~','+/='));

		if (trim($base64) == "")  
			throw new \Exception('the auth is empty');

		$data = json_decode(substr($aes->decrypt($base64),strlen($iv)),true);


		$md5 = $data['md5']; unset($data['md5']);
		$sha1 = $data['sha1']; unset($data['sha1']);

		if (md5(json_encode($data)) != $md5) {
			throw new \Exception('the auth is broken');
		}    

		if (sha1(json_encode($data)) != $sha1) {
			throw new \Exception('the auth is broken');
		}    


		if (time() - $data['time'] > 3600) {
			throw new \Exception('the auth is too old. Try again');
		}    

		$your_domain = $this->extConfig['ScoutnetProviderName'];

		if ($data['your_domain'] != $your_domain)
			throw new \Exception('the auth is for the wrong site!. Try again');

		$this->snData = $data;

		return $data;
	}

	private function _checkConfigValues(){
		$configVars = array('AES_key','AES_iv','ScoutnetLoginPage','ScoutnetProviderName');

		foreach ($configVars as $configVar) {
			if (trim($this->extConfig[$configVar]) == '')
				throw new \ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetExceptionMissingConfVar($configVar);
		}
	}


	public function generateAuth($api_key, $checkValue){
		if ($api_key == '')
			throw new \Exception('your Api Key is empty');

		$aes = new \ScoutNet\ShScoutnetWebservice\Helpers\AESHelper($api_key,"CBC",self::UNSECURE_START_IV);

		$auth = array(
			'sha1' => sha1($checkValue),
			'md5' => md5($checkValue),
			'time' => time(),
		);
		// TODO: fix this!
		$auth = serialize($auth);

		// this is done since we use the same iv all the time
		$first_block = '';
		for ($i=0;$i<16;$i++) {
			$first_block .= chr(rand(0,255));
		}

		$auth = strtr(base64_encode($aes->encrypt($first_block.$auth)), '+/=', '-_~');
		return $auth;
	}

}
