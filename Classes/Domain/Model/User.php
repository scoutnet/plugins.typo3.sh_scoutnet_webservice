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
 * User
 */
class User extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
	/**
	 * @var string
	 * @validate NotEmpty
	 * @validate StringLength(minimum=2, maximum=80)
	 */
	protected $username = null;

	/**
	 * @var string
	 * @validate NotEmpty
	 * @validate StringLength(minimum=2, maximum=80)
	 */
	protected $firstName = null;

	/**
	 * @var string
	 * @validate NotEmpty
	 * @validate StringLength(minimum=2, maximum=80)
	 */
	protected $lastName = null;

	/**
	 * @var string
	 * @validate NotEmpty
	 * @validate StringLength(minimum=1, maximum=80)
	 */
	protected $sex = null;

	/**
	 * @return string
	 */
	public function getUsername () {
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername ($username) {
		$this->username = $username;
	}

	public function getFirstName(){
		return trim($this->firstName)?trim($this->firstName):$this->getUsername();
	}

	/**
	 * @param string $firstName
	 */
	public function setFirstName ($firstName) {
		$this->firstName = $firstName;
	}

	/**
	 * @return string
	 */
	public function getLastName () {
		return $this->lastName;
	}

	/**
	 * @param string $lastName
	 */
	public function setLastName ($lastName) {
		$this->lastName = $lastName;
	}

	/**
	 * @return string
	 */
	public function getSex () {
		return $this->sex;
	}

	/**
	 * @param string $sex
	 */
	public function setSex ($sex) {
		$this->sex = $sex;
	}

	public function getFullName(){
		// we use the class functions not the getters, because the firstname can be the username
		$full_name = $this->firstName.' '.$this->lastName;
		return trim($full_name) ? trim($full_name) : $this->getUsername();
	}
	public function getLongName(){
		$full_name = $this->getFullName();
		if( $full_name ){
			return $full_name.' ('.$this->getUsername().')';
		} else {
			return $this->getUsername();
		}
	}
}
