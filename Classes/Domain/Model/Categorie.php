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
class Categorie {
    /**
     * @var integer
     */
    protected $uid;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var bool
     */
    protected $available;

    /**
     * @return int
     */
    public function getUid () {
        return $this->uid;
    }

    /**
     * @param int $uid
     */
    public function setUid ($uid) {
        $this->uid = $uid;
    }

    /**
     * @return string
     */
    public function getText () {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText ($text) {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getAvailable () {
        return $this->available;
    }

    /**
     * @param mixed $available
     */
    public function setAvailable ($available) {
        $this->available = $available;
    }
}