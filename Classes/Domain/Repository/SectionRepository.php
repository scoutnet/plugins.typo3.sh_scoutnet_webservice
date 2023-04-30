<?php

namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

use ScoutNet\ShScoutnetWebservice\Domain\Model\Section;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * The repository for User
 */
class SectionRepository extends AbstractScoutnetRepository
{
    private $section_cache = [];
    private $section_cache_uid = [];

    /**
     * It searches for UIDs
     *
     * @param int $uid
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Section|null returns Null if Section is not cached
     */
    public function findByUid(int $uid): ?Section
    {
        $categoryId = $this->section_cache_uid[$uid]??null;

        return $categoryId?$this->section_cache[$categoryId]??null:null;
    }

    /**
     * @param int $categoryId
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Section|null returns Null if Section is not cached
     */
    public function findByCategoryId(int $categoryId): ?Section
    {
        return $this->section_cache[$categoryId]??null; // return null if key does not exists
    }

    /**
     * Convert Array coming from API to Section Object and saves it to cache for later direct retrieval
     *
     * @param array $array
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Section
     */
    public function convertToSection(array $array): Section
    {
        /** @var \ScoutNet\ShScoutnetWebservice\Domain\Repository\CategoryRepository $categoryRepository */
        $categoryRepository = GeneralUtility::makeInstance(CategoryRepository::class);
        $category = $categoryRepository->findByUid($array['Keywords_ID']);

        $section = new Section();

        $section->setUid($array['id']);
        $section->setVerband($array['verband']);
        $section->setBezeichnung($array['bezeichnung']);
        $section->setFarbe($array['farbe']);
        $section->setStartalter((int)($array['startalter']));
        $section->setEndalter((int)($array['endalter']));
        $section->setCategory($category);

        // save new object to cache
        $this->section_cache[$section->getCategory()->getUid()] = $section;
        $this->section_cache_uid[$section->getUid()] = $section->getCategory()->getUid();
        return $section;
    }
}
