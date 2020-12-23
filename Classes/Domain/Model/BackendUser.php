<?php
namespace ScoutNet\ShScoutnetWebservice\Domain\Model;
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


/**
 * Model for backend user
 */
class BackendUser extends \TYPO3\CMS\Extbase\Domain\Model\BackendUser {
    /**
     *
     * @var string
     */
    protected $txShscoutnetUsername= '';

    /**
     *
     * @var string
     */
    protected $txShscoutnetApikey = '';

    /**
     * @return string
     */
    public function getTxShscoutnetUsername(): string {
        return $this->txShscoutnetUsername;
    }

    /**
     * @param string $txShscoutnetUsername
     */
    public function setTxShscoutnetUsername(string $txShscoutnetUsername) {
        $this->txShscoutnetUsername = $txShscoutnetUsername;
    }

    /**
     * @return string
     */
    public function getTxShscoutnetApikey(): string {
        return $this->txShscoutnetApikey;
    }

    /**
     * @param string $txShscoutnetApikey
     */
    public function setTxShscoutnetApikey(string $txShscoutnetApikey) {
        $this->txShscoutnetApikey = $txShscoutnetApikey;
    }


}