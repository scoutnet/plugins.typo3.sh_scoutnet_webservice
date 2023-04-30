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
 * User
 */
class User extends AbstractEntity
{
    /**
     * @var string
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate NotEmpty
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate StringLength(minimum=2, maximum=80)
     */
    protected $username = '';

    /**
     * @var string
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate NotEmpty
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate StringLength(minimum=2, maximum=80)
     */
    protected $firstName = '';

    /**
     * @var string
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate NotEmpty
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate StringLength(minimum=2, maximum=80)
     */
    protected $lastName = '';

    /**
     * @var string
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate NotEmpty
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate StringLength(minimum=1, maximum=80)
     */
    protected $sex = '';

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getFirstName(): string
    {
        return trim($this->firstName)?trim($this->firstName):$this->getUsername();
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getSex(): string
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     */
    public function setSex(string $sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        // we use the class functions not the getters, because the firstname can be the username
        $full_name = $this->firstName . ' ' . $this->lastName;
        return trim($full_name) ? trim($full_name) : $this->getUsername();
    }

    /**
     * @return string
     */
    public function getLongName(): string
    {
        $full_name = $this->getFullName();
        if ($full_name != $this->getUsername()) {
            return $full_name . ' (' . $this->getUsername() . ')';
        }
        return $this->getUsername();
    }
}
