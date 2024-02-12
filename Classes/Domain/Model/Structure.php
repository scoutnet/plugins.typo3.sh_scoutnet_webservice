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
 */
class Structure extends AbstractEntity
{
    /**
     * @var string
     */
    protected string $level;
    /**
     * @var string
     */
    protected string $name;
    /**
     * @var string
     */
    protected string $verband;
    /**
     * @var string
     */
    protected string $ident;
    /**
     * @var int
     */
    protected int $levelId;
    /**
     * @var array
     */
    protected array $usedCategories;
    /**
     * @var array
     */
    protected array $forcedCategories;

    /**
     * @param int $uid
     */
    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level ?? '';
    }

    /**
     * @param string $level
     */
    public function setLevel(string $level): void
    {
        $this->level = $level;
    }

    /**
     * @return string
     * @deprecated
     */
    public function getEbene(): string
    {
        return $this->getLevel();
    }

    /**
     * @param string $ebene
     * @deprecated
     */
    public function setEbene(string $ebene): void
    {
        $this->setLevel($ebene);
    }

    /**
     * @return string
     */
    public function getVerband(): string
    {
        return $this->verband;
    }

    /**
     * @param string $verband
     */
    public function setVerband(string $verband): void
    {
        $this->verband = $verband;
    }

    /**
     * @return string
     */
    public function getIdent(): string
    {
        return $this->ident;
    }

    /**
     * @param string $ident
     */
    public function setIdent(string $ident): void
    {
        $this->ident = $ident;
    }

    /**
     * @return int
     */
    public function getLevelId(): int
    {
        return $this->levelId ?? -1;
    }

    /**
     * @param int $levelId
     */
    public function setLevelId(int $levelId): void
    {
        $this->levelId = $levelId;
    }

    /**
     * @return int
     * @deprecated
     */
    public function getEbeneId(): int
    {
        return $this->getLevelId();
    }

    /**
     * @param int $ebeneId
     * @deprecated
     */
    public function setEbeneId(int $ebeneId): void
    {
        $this->setLevelId($ebeneId);
    }

    /**
     * @return array
     */
    public function getUsedCategories(): array
    {
        return $this->usedCategories ?? [];
    }

    /**
     * @param array $usedCategories
     */
    public function setUsedCategories(array $usedCategories): void
    {
        $this->usedCategories = $usedCategories;
    }

    /**
     * @return array
     */
    public function getForcedCategories(): array
    {
        return $this->forcedCategories ?? [];
    }

    /**
     * @param array $forcedCategories
     */
    public function setForcedCategories(array $forcedCategories): void
    {
        $this->forcedCategories = $forcedCategories;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?? '';
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLongName(): string
    {
        return (string)$this->getLevel() . ' ' . (($this->getLevelId() >= 7) ? $this->getName() : '');
    }

    /**
     * @return string
     * @deprecated
     */
    public function get_Name(): string
    {
        return (string)$this->getLevel() . (($this->getLevelId() >= 7) ? '&nbsp;' . $this->getName() : '');
    }
}
