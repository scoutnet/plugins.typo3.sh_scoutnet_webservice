<?php
namespace ScoutNet\ShScoutnetWebservice\Helpers;

use DateTime;

use ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetException;
use ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetExceptionMissingConfVar;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
class AuthHelper {
	/**
	 * @var array
	 */
//	protected $settings;

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
//		$this->settings = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);
		$this->extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sh_scoutnet_webservice']);
	}

	/**
	 * Stores the Login Data
	 * @var array
	 */
	private $snData = null;

	const UNSECURE_START_IV = '1234567890123456';

    /**
     * @param $data
     *
     * @return array|mixed
     * @throws \ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetExceptionMissingConfVar
     * @throws \Exception
     */
	public function getApiKeyFromData($data){
		if (isset($this->snData)) {
			return $this->snData;
		}    

		$this->_checkConfigValues();

		$z = $this->extConfig['AES_key'];
		$iv = $this->extConfig['AES_iv'];

		$aes = new AESHelper($z,"CBC",$iv);

		$base64 = base64_decode(strtr($data, '-_~','+/='));

		if (trim($base64) == "")  
			throw new ScoutNetException('the auth is empty', 1572191918);

		$data = json_decode(substr($aes->decrypt($base64),strlen($iv)),true);


		$md5 = $data['md5']; unset($data['md5']);
		$sha1 = $data['sha1']; unset($data['sha1']);

		if (md5(json_encode($data)) != $md5) {
			throw new ScoutNetException('the auth is broken', 1572192280);
		}    

		if (sha1(json_encode($data)) != $sha1) {
			throw new ScoutNetException('the auth is broken', 1572192281);
		}

        // use this so we can mock it
        $now = GeneralUtility::makeInstance(DateTime::class);

		if ($now->getTimestamp() - $data['time'] > 3600) {
			throw new ScoutNetException('the auth is too old. Try again', 1572192282);
		}    

		$your_domain = $this->extConfig['ScoutnetProviderName'];

		if ($data['your_domain'] != $your_domain)
			throw new ScoutNetException('the auth is for the wrong site!. Try again', 1572192283);

		$this->snData = $data;

		return $data;
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

    /**
     * This Function generates Auth for given value. The auth uses this formular:
     *
     * base64(aes_256_cbc(<random block> + json([sha1=>sha1($checkValue),md5=>md5($checkvalue),time=>time()])))
     *
     * the key for the aes is the api_key, the iv is self::UNSECURE_START_IV, therefor the first block is random and will be discarded on the other end
     *
     * @param $api_key
     * @param $checkValue
     *
     * @return array|false|string
     * @throws \Exception
     */
	public function generateAuth($api_key, $checkValue){
		if ($api_key == '')
			throw new ScoutNetException('your Api Key is empty', 1572194190);

		$aes = new AESHelper($api_key,"CBC",self::UNSECURE_START_IV);

		$now = GeneralUtility::makeInstance(DateTime::class);

		$auth = array(
			'sha1' => sha1($checkValue),
			'md5' => md5($checkValue),
			'time' => $now->getTimestamp(),
		);
		$auth = json_encode($auth);

		// this is done since we use the same iv all the time
        $random = GeneralUtility::makeInstance(Random::class);
        $first_block = $random->generateRandomBytes(16);

		$auth = strtr(base64_encode($aes->encrypt($first_block.$auth)), '+/=', '-_~');
		return $auth;
	}
}
