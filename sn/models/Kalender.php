<?php
class SN_Model_Kalender extends ArrayObject{
	function __construct( $array ){
		parent::__construct($array);
	}


	public function get_long_Name() {
		return (string) htmlentities(utf8_decode($this['Ebene'])).(($this['Ebene_Id'] >= 7)?"<br>".htmlentities(utf8_decode($this['Name'])):"");
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/models/Kalender.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/models/Kalender.php']);
}
