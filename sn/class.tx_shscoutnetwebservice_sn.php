<?php
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_t3lib.'class.t3lib_svbase.php');
require_once('class.tx_shscoutnetwebservice_jsonRPCClient.php');

require_once('models/Stufe.php');
require_once('models/Kalender.php');
require_once('models/User.php');
require_once('models/Events.php');


/**
 * Service "SN" for the "sh_scoutnet_webservice" extension.
 *
 * @author	Stefan Horst <s.horst@dpsg-koeln.de>
 * @package	TYPO3
 * @subpackage	tx_shscoutnetwebservice
 */
class tx_shscoutnetwebservice_sn extends t3lib_svbase {
	var $prefixId = 'tx_shscoutnetwebservice_sn';		// Same as class name
	var $scriptRelPath = 'sn/class.tx_shscoutnetwebservice_sn.php';	// Path to this script relative to the extension dir.
	var $extKey = 'sh_scoutnet_webservice';	// The extension key.

	var $SN = null;

	var $cache = array();

	var $user_cache = array();
	var $stufen_cache = array();
	var $kalender_cache = array();
	
	/**
	 * [Put your description here]
	 *
	 * @return	[type]		...
	 */
	public function __construct()	{
		ini_set('default_socket_timeout',1);
		$this->SN = new tx_shscoutnetwebservice_jsonRPCClient("http://www.scoutnet.de/jsonrpc/server.php");
	}

	protected function load_data_from_scoutnet($ids,$query){
		$res = $this->SN->get_data_by_global_id($ids,$query);
		$this->cache = array_merge ($this->cache, $res);

		return $res;
	}

	public function get_events_for_global_id_with_filter($ids,$filter){
		$events = array();
		foreach ($this->load_data_from_scoutnet($ids,array('events'=>$filter)) as $record) {

			if ($record['type'] === 'user'){
				$user = new SN_Model_User($record['content']);
				$this->user_cache[$user['userid']] = $user;
			} elseif ($record['type'] === 'stufe'){
				$stufe = new SN_Model_Stufe($record['content']);
				$this->stufen_cache[$stufe['Keywords_ID']] = $stufe;
			} elseif ($record['type'] === 'kalender'){
				$kalender = new SN_Model_Kalender($record['content']);
				$this->kalender_cache[$kalender['ID']] = $kalender;
			} elseif ($record['type'] === 'event') {
				$event = new SN_Model_Event($record['content']);

				$author = $this->get_user_by_id($event['Last_Modified_By']);
				if ($author == null) {
					$author = $this->get_user_by_id($event['Created_By']);
				}

				if ($author != null) {
					$event['Author'] = $author;
				}	

				$stufen = Array();


				if (isset($event['Stufen'])){
					foreach ($event['Stufen'] as $stufenId) {
						$stufe = $this->get_stufe_by_id($stufenId);
						if ($stufe != null) {
							$stufen[] = $stufe;
						}
					}
				}

				$event['Stufen'] = $stufen;
					
				$event['Kalender'] = $this->get_kalender_by_id($event['Kalender']);


				$events[] = $event;
			}
		}
		return $events;
	}

	private function get_stufe_by_id($id) {
		return $this->stufen_cache[$id];
	}

	private function get_kalender_by_id($id) {
		return $this->kalender_cache[$id];
	}

	private function get_user_by_id($id) {
		return $this->user_cache[$id];
	}

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/class.tx_shscoutnetwebservice_sn.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/class.tx_shscoutnetwebservice_sn.php']);
}

?>
