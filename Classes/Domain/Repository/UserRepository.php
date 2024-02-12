<?php

namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

use ScoutNet\ShScoutnetWebservice\Domain\Model\User;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception\UnsupportedMethodException;

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
class UserRepository extends AbstractScoutnetRepository
{
    private array $user_cache = [];

    /**
     * @param int $uid
     *
     * @throws UnsupportedMethodException
     */
    public function findByUid(int $uid)
    {
        throw new UnsupportedMethodException('this Method is no longer supported, use findByUsername');
    }

    /**
     * @param string $username
     *
     * @return User|null
     */
    public function findByUsername(string $username): ?User
    {
        return $this->user_cache[$username] ?? null;
    }

    /**
     * @param array $array
     *
     * @return User
     */
    public function convertToUser(array $array): User
    {
        $user = new User();

        $user->setUsername($array['userid']);
        $user->setFirstName($array['firstname']);
        $user->setLastName($array['surname']);
        $user->setSex($array['sex']);

        // save new object to cache
        $this->user_cache[$user->getUsername()] = $user;
        return $user;
    }
}
