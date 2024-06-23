<?php
/**
 * Copyright (c) 2015-2024 Stefan (Mütze) Horst
 *
 * I don't have the time to read through all the licences to find out
 * what they exactly say. But it's simple. It's free for non-commercial
 * projects, but as soon as you make money with it, I want my share :-)
 * (License: Free for non-commercial use)
 *
 * Authors: Stefan (Mütze) Horst <muetze@scoutnet.de>
 */

namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

use ScoutNet\Api\Model\Section;

/**
 * The repository for User
 */
class SectionRepository extends AbstractScoutnetRepository
{
    private array $section_cache = [];
    private array $section_cache_uid = [];

    /**
     * It searches for UIDs
     *
     * @param int $uid
     *
     * @return Section|null returns Null if Section is not cached
     * @api
     */
    public function findByUid(int $uid): ?Section
    {
        $categoryId = $this->section_cache_uid[$uid] ?? null;

        return $categoryId ? $this->section_cache[$categoryId] ?? null : null;
    }

    /**
     * @param int $categoryId
     *
     * @return Section|null returns Null if Section is not cached
     * @api
     */
    public function findByCategoryId(int $categoryId): ?Section
    {
        return $this->section_cache[$categoryId] ?? null; // return null if key does not exist
    }
}
