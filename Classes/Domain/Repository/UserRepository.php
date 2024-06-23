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

use ScoutNet\Api\Model\User;

/**
 * The repository for User
 */
class UserRepository extends AbstractScoutnetRepository
{
    private array $user_cache = [];

    /**
     * @param string $username
     *
     * @return User|null
     * @api
     */
    public function findByUsername(string $username): ?User
    {
        return $this->user_cache[$username] ?? null;
    }
}
