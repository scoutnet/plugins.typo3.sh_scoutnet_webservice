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
	
	/**
	 * [Put your description here]
	 *
	 * @return	[type]		...
	 */
	function __construct()	{
		ini_set('default_socket_timeout',1);
		$this->SN = new tx_shscoutnetwebservice_jsonRPCClient("http://www.scoutnet.de/jsonrpc/server.php");
	}

	function get_data_by_global_id($ids,$query){
		$res = $this->SN->get_data_by_global_id($ids,$query);
		$this->cache = array_merge ($this->cache, $res);

		return $res;
	}

	function get_events_by_global_id($ids,$filter){
		$res = array();
		foreach ($this->get_data_by_global_id($ids,array('events'=>$filter)) as $record) {
			if ($record['type'] === 'event') {
				$res[] = $record['content'];
			}
		}
		return $res;
	}

	function get_stufe_by_id($id) {
		if (isset($this->cache["STUFE_".$id])){
			return new SN_Model_Stufe($this->cache['STUFE_'.$id]['content']);
		}
		return new SN_Model_Stufe(array());
	}


}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/class.tx_shscoutnetwebservice_sn.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/class.tx_shscoutnetwebservice_sn.php']);
}

?>
