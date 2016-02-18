<?php
class SN_Model_Kalender extends ArrayObject{
	function __construct( $array ){
		parent::__construct($array);
	}


	public function get_long_Name() {
		return (string) htmlentities($this['Ebene'], ENT_COMPAT|ENT_HTML401, 'UTF-8').(($this['Ebene_Id'] >= 7)?"<br>".htmlentities($this['Name'], ENT_COMPAT|ENT_HTML401, 'UTF-8'):"");
	}

	public function get_Name() {
		return (string) htmlentities($this['Ebene'], ENT_COMPAT|ENT_HTML401, 'UTF-8').(($this['Ebene_Id'] >= 7)?"&nbsp;".htmlentities($this['Name'], ENT_COMPAT|ENT_HTML401, 'UTF-8'):"");
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/models/SN_Model_Kalender.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/models/SN_Model_Kalender.php']);
}
