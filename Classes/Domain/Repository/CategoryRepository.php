<?php

namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

use Exception;
use ScoutNet\ShScoutnetWebservice\Domain\Model\Category;
use ScoutNet\ShScoutnetWebservice\Domain\Model\Event;
use ScoutNet\ShScoutnetWebservice\Domain\Model\Structure;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Stefan "Mütze" Horst <muetze@scoutnet.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
class CategoryRepository extends AbstractScoutnetRepository
{
    protected $categoriesCache = [];

    /**
     * checks if Category with UID is in Cache, otherwise loads category from Scoutnet API
     *
     * @param int $uid
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Category|null
     */
    public function findByUid(int $uid): ?Category
    {
        // if the category is not Cached, load from Scoutnet
        if (!isset($this->categoriesCache[$uid])) {
            try {
                foreach ($this->loadDataFromScoutnet(null, ['categories' => ['uid' => $uid]]) as $record) {
                    if ($record['type'] === 'categorie') {
                        // convert and save to cache
                        $this->convertToCategory($record['content']);
                    }
                }
            } catch (Exception $e) {
                // it is not in cache and we get an error from scoutnet
                return null;
            }
        }

        return $this->categoriesCache[$uid]??null;
    }

    /**
     * Loads All Categories from Scoutnet API, regardless of local cache, but updates Cache in the Process
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Category[]
     */
    public function findAll(): array
    {
        try {
            foreach ($this->loadDataFromScoutnet(null, ['categories' => ['all' => true]]) as $record) {
                if ($record['type'] === 'categorie') {
                    // convert and save to cache
                    $this->convertToCategory($record['content']);
                }
            }
        } catch (Exception $e) {
            return [];
        }

        // return local cache but without the keys
        return array_values($this->categoriesCache);
    }

    /**
     * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure $structure
     * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\Event     $event
     *
     * @return mixed
     */
    public function getAllCategoriesForStructureAndEvent(Structure $structure, Event $event): array
    {
        $generatedCategories = [];
        try {
            foreach ($this->loadDataFromScoutnet($structure->getUid(), ['categories' => ['generatedCategoriesForEventId' => $event->getUid() != null?$event->getUid():-1]]) as $record) {
                if ($record['type'] === 'categorie') {
                    // convert and save to cache
                    $generatedCategories[] = $this->convertToCategory($record['content']);
                }
            }
        } catch (Exception $e) {
            // if the scoutnet Server is down, we use the Categories from the structure
            $categories['generatedCategories'] = $this->convertArrayToCategories($structure->getUsedCategories(), $event);
        }
        $categories['generatedCategories'] = $generatedCategories;

        $forcedCategoriesName = array_keys($structure->getForcedCategories())[1];
        $categories['allSectCategories'] = $this->convertArrayToCategories($structure->getForcedCategories()['sections/leaders'], $event);
        $categories['forcedCategories'] = $this->convertArrayToCategories($structure->getForcedCategories()[$forcedCategoriesName], $event);

        return $categories;
    }

    /**
     * convert array containing uid=>text values to List of Category Objects
     * Checks if the Category is used by the event and if so, marks the Category as available
     *
     * @param array                                             $array
     * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\Event $event
     *
     * @return Category[] List of all Categories
     */
    private function convertArrayToCategories(array $array, Event $event): array
    {
        $categories = [];
        foreach ($array as $key => $text) {
            $category = new Category();

            $category->setUid($key);
            $category->setText($text);
            $category->setAvailable(in_array($key, array_keys($event->getCategories())) || in_array($key, array_keys($event->getSectionCategories())));

            $this->categoriesCache[$category->getUid()] = $category;
            $categories[] = $category;
        }
        return $categories;
    }

    /**
     * Convert from Api Data to Category Object and save to cache
     *
     * @param array $array
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Category
     */
    public function convertToCategory(array $array): Category
    {
        $category = new Category();

        $category->setUid($array['ID']);
        $category->setText($array['Text']);
        $category->setAvailable($array['Selected']=='yes');

        $this->categoriesCache[$category->getUid()] = $category;

        return $category;
    }
}
