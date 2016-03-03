<?php

namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

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
class CategorieRepository extends AbstractScoutnetRepository {
    protected $categorieCache = array();

    /**
     * @param integer $uid
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Categorie[]|null
     */
    public function findByUid($uid) {
        $generatedCategories = array();
        if (isset($this->categorieCache[$uid]))
            return $this->categorieCache[$uid];

        try {
            foreach ($this->loadDataFromScoutnet(null , array('categories' => array('uid' => $uid))) as $record) {
                if ($record['type'] === 'categorie') {
                    $generatedCategories[] = $this->convertToCategorie($record['content']);
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return $generatedCategories[0];
    }

    /**
     * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure $structure
     * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\Event     $event
     *
     * @return mixed
     */
    public function getAllCategoriesForStructureAndEvent(\ScoutNet\ShScoutnetWebservice\Domain\Model\Structure $structure, \ScoutNet\ShScoutnetWebservice\Domain\Model\Event $event){
        $generatedCategories = array();
        try {
            foreach ($this->loadDataFromScoutnet($structure->getUid(), array('categories' => array('generatedCategoriesForEventId' => $event->getUid() != null?$event->getUid():-1))) as $record) {
                if ($record['type'] === 'categorie') {
                    $generatedCategories[] = $this->convertToCategorie($record['content']);
                }
            }
        } catch (\Exception $e) {
            // if the scoutnet Server is down, we use the Categories from the structure
            $categories['generatedCategories'] = $this->convertArrayToCategories($structure->getUsedCategories(), $event);
        }
        $categories['generatedCategories'] = $generatedCategories;

        $forcedCategoriesName = array_keys($structure->getForcedCategories())[1];
        $categories['allSectCategories'] = $this->convertArrayToCategories($structure->getForcedCategories()['sections/leaders'], $event);
        $categories['forcedCategories'] = $this->convertArrayToCategories($structure->getForcedCategories()[$forcedCategoriesName], $event);

        return $categories;
    }

    private function convertArrayToCategories($array, \ScoutNet\ShScoutnetWebservice\Domain\Model\Event $event) {
        $categories = array();
        foreach ($array as $key => $text) {
            $categorie = new \ScoutNet\ShScoutnetWebservice\Domain\Model\Categorie();

            $categorie->setUid($key);
            $categorie->setText($text);
            $categorie->setAvailable(in_array($key, array_keys($event->getCategories())) || in_array($key, array_keys($event->getStufenCategories())));

            $this->categorieCache[$categorie->getUid()] = $categorie;
            $categories[] = $categorie;
        }
        return $categories;
    }

    public function convertToCategorie($array) {
        $categorie = new \ScoutNet\ShScoutnetWebservice\Domain\Model\Categorie();

        $categorie->setUid($array['ID']);
        $categorie->setText($array['Text']);
        $categorie->setAvailable($array['Selected']=='yes');

        $this->categorieCache[$categorie->getUid()] = $categorie;

        return $categorie;
    }

}