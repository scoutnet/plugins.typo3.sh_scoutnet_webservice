<?php
class SN_Model_Event extends ArrayObject{
	function __construct( $array ){
		parent::__construct($array);
	}


	public function get_Author_name(){
		if (isset($this['Author']) && $this['Author'] != null) {
			return (string) utf8_decode($this['Author']->get_long_Name());
		}

		return (string) "";
	}
}

