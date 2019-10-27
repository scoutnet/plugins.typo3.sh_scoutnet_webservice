<?php
namespace ScoutNet\ShScoutnetWebservice\Helpers;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Stefan "Mütze" Horst <muetze@scoutnet.de>, ScoutNet
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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

use ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetException;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;

/**
 * JsonRPCClientHelper
 *
 * @method get_data_by_global_id($globalid, $filter)
 * @method deleteObject($type, $globalid, $id, $username, $auth)
 * @method setData($type,$id,$object,$username,$auth)
 * @method checkPermission($type,$globalid,$username,$auth)
 * @method requestPermission($type,$globalid,$username,$auth)
 * @method test()
 */
class JsonRPCClientHelper {
	
	/**
	 * Debug state
	 *
	 * @var boolean
	 */
	private $debug;
	
	/**
	 * The server URL
	 *
	 * @var string
	 */
	private $url;
	/**
	 * The request id
	 *
	 * @var integer
	 */
	private $id;
	/**
	 * If true, notifications are performed instead of requests
	 *
	 * @var boolean
	 */
	private $notification = false;
	
	/**
	 * Takes the connection parameters
	 *
	 * @param string $url
	 * @param boolean $debug
	 */
	public function __construct($url, $debugOutput = false) {
		// server URL
		$this->url = $url;
		$this->debug = $debugOutput;
		// message id
		$this->id = 1;
	}
	
	/**
	 * Sets the notification state of the object. In this state, notifications are performed, instead of requests.
	 *
	 * @param boolean $notification
	 */
	public function setRPCNotification($notification) {
		empty($notification) ?
							$this->notification = false
							:
							$this->notification = true;
	}

	/**
	 * Performs a jsonRCP request and gets the results as an array
	 *
	 * @param string $method
	 * @param array  $params
	 *
	 * @return array | bool
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException
     * @throws \ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetException
	 */
	public function __call($method, $params) {
		$debug = '';

		// check
		if (!is_scalar($method)) {
		    // only possible if someone calls __call directly
			throw new InvalidArgumentValueException('Method name has no scalar value', 1572203129);
		}

		// check
		if (is_array($params)) {
			// no keys
			$params = array_values($params);
		} else {
            // only possible if someone calls __call directly
			throw new InvalidArgumentValueException('Params must be given as array', 1572203170);
		}
		
		// sets notification or request task
		if ($this->notification) {
			$currentId = NULL;
		} else {
			$currentId = $this->id;
			$this->id += 1;
		}
		
		// prepares the request
		$request = array(
			'method' => $method,
			'params' => $params,
			'id' => $currentId
		);
		$request = json_encode($request);
		$this->debug && $debug .='***** Request *****'."\n".$request."\n".'***** End Of request *****'."\n\n";


		if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlUse'] && extension_loaded( 'curl' ) ) {
			// performs the HTTP POST by use of libcurl
			$options = array(
				CURLOPT_URL		=> $this->url,
				CURLOPT_POST		=> true,
				CURLOPT_POSTFIELDS	=> $request,
				CURLOPT_HTTPHEADER	=> array( 'Content-Type: application/json' ),
				CURLINFO_HEADER_OUT	=> true,
				CURLOPT_RETURNTRANSFER	=> true,
				CURLOPT_SSL_VERIFYHOST 	=> false,
				CURLOPT_SSL_VERIFYPEER 	=> false,
				CURLOPT_FOLLOWLOCATION	=> true,
			);
			$ch = curl_init();
			curl_setopt_array( $ch, $options );

			if (isset($_COOKIE['XDEBUG_SESSION'])) {
				curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_SESSION: '.urlencode($_COOKIE['XDEBUG_SESSION']));
			}

			if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyServer'])	{
				curl_setopt($ch, CURLOPT_PROXY, $GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyServer']);

				if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyTunnel'])	{
					curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyTunnel']);
				}
				if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyUserPass'])	{
					curl_setopt($ch, CURLOPT_PROXYUSERPWD, $GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyUserPass']);
				}
			}



			$response = trim( curl_exec( $ch ) );

			$this->debug && $debug.='***** Server response *****'."\n".$response.'***** End of server response *****'."\n";
			$response = json_decode( $response, true );
			curl_close( $ch );
		} else {
			// performs the HTTP POST
			$opts = array (
				'http' => array (
					'method'  => 'POST',
					'header'  => 'Content-type: application/json',
					'content' => $request
				));
			$context  = stream_context_create($opts);

			if ($fp = @fopen($this->url, 'r', false, $context)) {
				$response = '';
				while($row = fgets($fp)) {
					$response.= trim($row)."\n";
				}
				$this->debug && $debug.='***** Server response *****'."\n".$response.'***** End of server response *****'."\n";
				$response = json_decode($response,true);
			} else {
				throw new ScoutNetException('Unable to connect to '.$this->url, 1572202683);
			}
		}

		// debug output
		if ($this->debug) {
			echo nl2br($debug);
		}
		
		// final checks and return
		if (!$this->notification) {
			// check
			if ($response['id'] != $currentId) {
				throw new ScoutNetException('Incorrect response id (request id: '.$currentId.', response id: '.$response['id'].')', 1572203283);
			}
			if (!is_null($response['error'])) {
				throw new ScoutNetException('Request error: '.$response['error'], 1572203301);
			}
			
			return $response['result'];
			
		} else {
			return true;
		}
	}
}

