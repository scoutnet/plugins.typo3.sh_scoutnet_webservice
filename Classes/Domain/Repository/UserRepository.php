<?php
namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

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
class UserRepository extends AbstractScoutnetRepository {
    private $user_cache = array();

    /**
     * @param integer $uid
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\User
     */
    public function findByUid($uid) {
        return $this->user_cache[$uid];
    }

    /**
     * @param mixed $array
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\User
     */
    public function convertToUser($array) {
        $user = new \ScoutNet\ShScoutnetWebservice\Domain\Model\User();
        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($array);

        $user->setUsername($array['userid']);
        $user->setFirstName($array['firstname']);
        $user->setLastName($array['surname']);
        $user->setSex($array['sex']);

        // save new object to cache
        $this->user_cache[$user->getUsername()] = $user;
        return $user;
    }
}
