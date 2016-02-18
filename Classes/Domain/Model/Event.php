<?php
namespace ScoutNet\ShScoutnetWebservice\Domain\Model;

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

/**
 * Event
 *
 */
//class Event extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
class Event extends ArrayObject{
	function __construct( $array ){
		parent::__construct($array);
	}


	public function get_Author_name(){
		if (isset($this['Author']) && $this['Author'] != null) {
			return (string) htmlentities($this['Author']->get_full_Name(), ENT_COMPAT|ENT_HTML401, 'UTF-8');
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
