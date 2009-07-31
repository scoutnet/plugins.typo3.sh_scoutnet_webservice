<?php
class SN_Model_Event extends ArrayObject{
	function __construct( $array ){
		parent::__construct($array);
	}


	public function get_Author_name(){
		if (isset($this['Author']) && $this['Author'] != null) {
			return (string) htmlentities(utf8_decode($this['Author']->get_long_Name()));
		}

		return (string) "";
	}

	public function get_Stufen_Images() {
		if (isset($this['Stufen']) && $this['Stufen'] != null) {
			
			$stufen = "";
			foreach ($this['Stufen'] as $stufe) { 
				$stufen .= $stufe->get_Image_URL();
			}

			return (string) $stufen;
		}
		return (string) "";
	}
}

