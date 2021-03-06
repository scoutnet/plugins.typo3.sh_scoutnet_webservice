<?php
namespace ScoutNet\ShScoutnetWebservice\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

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
 * Kalender
 *
 */
class Structure extends AbstractEntity {
	/**
	 * @var String
     */
	protected $level;
	/**
	 * @var String
     */
	protected $name;
	/**
	 * @var String
     */
	protected $verband;
	/**
	 * @var String
     */
	protected $ident;
	/**
	 * @var Integer
     */
	protected $levelId;
	/**
	 * @var array
     */
	protected $usedCategories;
	/**
	 * @var array
     */
	protected $forcedCategories;

	/**
	 * @param Integer $uid
     */
	public function setUid(int $uid) {
		$this->uid = $uid;
	}

    /**
     * @return String
     */
    public function getLevel(): string {
        return $this->level??'';
    }

    /**
     * @param String $level
     */
    public function setLevel(string $level): void {
        $this->level = $level;
    }

	/**
	 * @return String
     * @deprecated
	 */
	public function getEbene(): string {
		return $this->getLevel();
	}

	/**
	 * @param String $ebene
     * @deprecated
     */
	public function setEbene(string $ebene) {
	    $this->setLevel($ebene);
	}

	/**
	 * @return String
	 */
	public function getVerband(): string {
		return $this->verband;
	}

	/**
	 * @param String $verband
	 */
	public function setVerband(string $verband) {
		$this->verband = $verband;
	}

	/**
	 * @return String
	 */
	public function getIdent(): string {
		return $this->ident;
	}

	/**
	 * @param String $ident
	 */
	public function setIdent(string $ident) {
		$this->ident = $ident;
	}

    /**
     * @return int
     */
    public function getLevelId(): int {
        return $this->levelId??-1;
    }

    /**
     * @param int $levelId
     */
    public function setLevelId(int $levelId): void {
        $this->levelId = $levelId;
    }

	/**
	 * @return int
     * @deprecated
	 */
	public function getEbeneId(): int {
	    return $this->getLevelId();
	}

	/**
	 * @param int $ebeneId
     * @deprecated
     */
	public function setEbeneId(int $ebeneId) {
	    $this->setLevelId($ebeneId);
	}

	/**
	 * @return array
	 */
	public function getUsedCategories(): array {
		return $this->usedCategories??[];
	}

    /**
     * @param array $usedCategories
     */
	public function setUsedCategories(array $usedCategories) {
		$this->usedCategories = $usedCategories;
	}

	/**
	 * @return array
	 */
	public function getForcedCategories(): array {
		return $this->forcedCategories??[];
	}

    /**
     * @param array $forcedCategories
     */
	public function setForcedCategories(array $forcedCategories) {
		$this->forcedCategories = $forcedCategories;
	}

	/**
	 * @return String
     */
	public function getName(): string {
		return $this->name??'';
	}

    /**
     * @param String $name
     */
	public function setName(string $name) {
		$this->name = $name;
	}


	/**
	 * @return string
     */
	public function getLongName(): string {
		return (string) $this->getLevel().' '.(($this->getLevelId() >= 7)?$this->getName():"");
	}

	/**
	 * @return string
	 * @deprecated
     */
	public function get_Name(): string {
		return (string) $this->getLevel().(($this->getLevelId() >= 7)?'&nbsp;'.$this->getName():"");
	}
}
