<?php

namespace ScoutNet\ShScoutnetWebservice\Domain\Model;

use ScoutNet\ShScoutnetWebservice\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
 * Section
 */
class Section extends AbstractEntity
{
    /**
     * @var string
     */
    protected $verband;

    /**
     * @var string
     */
    protected $bezeichnung;

    /**
     * @var string
     */
    protected $farbe;

    /**
     * @var int
     */
    protected $startalter;

    /**
     * @var int
     */
    protected $endalter;

    /**
     * @var \ScoutNet\ShScoutnetWebservice\Domain\Model\Category
     */
    protected $category;

    /**
     * @param int $uid
     */
    public function setUid(int $uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return string
     */
    public function getVerband(): string
    {
        return $this->verband??'';
    }

    /**
     * @param string $verband
     */
    public function setVerband(string $verband)
    {
        $this->verband = $verband;
    }

    /**
     * @return string
     */
    public function getBezeichnung(): string
    {
        return $this->bezeichnung??'';
    }

    /**
     * @param string $bezeichnung
     */
    public function setBezeichnung(string $bezeichnung)
    {
        $this->bezeichnung = $bezeichnung;
    }

    /**
     * @return string
     */
    public function getFarbe(): string
    {
        return $this->farbe??'';
    }

    /**
     * @param string $farbe
     */
    public function setFarbe(string $farbe)
    {
        $this->farbe = $farbe;
    }

    /**
     * @return int
     */
    public function getStartalter(): int
    {
        return $this->startalter??-1;
    }

    /**
     * @param int $startalter
     */
    public function setStartalter(int $startalter)
    {
        $this->startalter = $startalter;
    }

    /**
     * @return int
     */
    public function getEndalter(): int
    {
        return $this->endalter??-1;
    }

    /**
     * @param int $endalter
     */
    public function setEndalter(int $endalter)
    {
        $this->endalter = $endalter;
    }

    /**
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string
     * @deprecated
     */
    public function getCategorieId(): string
    {
        return $this->category->getUid();
    }

    /**
     * @param int $categoryId
     * @deprecated
     */
    public function setCategorieId(int $categoryId)
    {
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = GeneralUtility::makeInstance(CategoryRepository::class);
        $this->category = $categoryRepository->findByUid($categoryId);
    }

    /**
     * @return string
     */
    public function getImageURL(): string
    {
        // TODO: make this configurable
        return (string)"<img src='https://kalender.scoutnet.de/2.0/images/" . $this->getUid() . ".gif' alt='" . htmlentities($this->getBezeichnung(), ENT_COMPAT|ENT_HTML401, 'UTF-8') . "' />";
    }
}
