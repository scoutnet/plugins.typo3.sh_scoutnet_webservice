<?php
/**
 * Copyright (c) 2016-2024 Stefan (Mütze) Horst
 *
 * I don't have the time to read through all the licences to find out
 * what they exactly say. But it's simple. It's free for non-commercial
 * projects, but as soon as you make money with it, I want my share :-)
 * (License: Free for non-commercial use)
 *
 * Authors: Stefan (Mütze) Horst <muetze@scoutnet.de>
 */

namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

use Exception;
use ScoutNet\Api\Model\Category;
use ScoutNet\Api\Model\Event;
use ScoutNet\Api\Model\Structure;

class CategoryRepository extends AbstractScoutnetRepository
{
    protected array $categoriesCache = [];

    /**
     * checks if Category with UID is in Cache, otherwise loads category from ScoutNet API
     *
     * @param int $uid
     *
     * @return Category|null
     * @api
     */
    public function findByUid(int $uid): ?Category
    {
        // if the category is not Cached, load from ScoutNet
        if (!isset($this->categoriesCache[$uid])) {
            try {
                foreach ($this->SN->get_categories_by_ids($uid) as $category) {
                    $this->categoriesCache[$category->getUid()] = $category;
                }
            } catch (Exception) {
                // it is not in cache, and we get an error from ScoutNet
                return null;
            }
        }

        return $this->categoriesCache[$uid] ?? null;
    }

    /**
     * Loads All Categories from ScoutNet API, regardless of local cache, but updates Cache in the Process
     *
     * @return Category[]
     * @api
     */
    public function findAll(): array
    {
        // cache all Categories
        foreach ($this->SN->get_all_categories() as $category) {
            $this->categoriesCache[$category->getUid()] = $category;
        }

        // return local cache but without the keys
        return array_values($this->categoriesCache);
    }

    /**
     * @param Structure $structure
     * @param Event $event
     *
     * @return mixed
     * @api
     */
    public function getAllCategoriesForStructureAndEvent(Structure $structure, Event $event): array
    {
        $categories['generatedCategories'] = $this->SN->get_all_categories_for_kalender_and_event($structure->getUid(), $event->getUid() ?? -1);

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
     * @param Event $event
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
            $category->setAvailable(array_key_exists($key, $event->getCategories()) || array_key_exists($key, $event->getSectionCategories()));

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
     * @return Category
     */
    public function convertToCategory(array $array): Category
    {
        $category = new Category();

        $category->setUid($array['ID']);
        $category->setText($array['Text']);
        $category->setAvailable($array['Selected'] === 'yes');

        $this->categoriesCache[$category->getUid()] = $category;

        return $category;
    }
}
