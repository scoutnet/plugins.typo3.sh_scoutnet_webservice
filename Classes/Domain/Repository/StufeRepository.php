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
class StufeRepository extends AbstractScoutnetRepository {
    private $stufe_cache = array();

    /**
     * @param integer $uid
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Stufe
     */
    public function findByUid($uid) {
        return $this->stufe_cache[$uid];
    }

    /**
     * @param mixed $array
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Stufe
     */
    public function convertToStufe($array) {
        $stufe = new \ScoutNet\ShScoutnetWebservice\Domain\Model\Stufe();
        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($array);

        $stufe->setUid($array['id']);
        $stufe->setVerband($array['verband']);
        $stufe->setBezeichnung($array['bezeichnung']);
        $stufe->setFarbe($array['farbe']);
        $stufe->setStartalter(intval($array['startalter']));
        $stufe->setEndalter(intval($array['endalter']));
        $stufe->setCategorieId($array['Keywords_ID']);

        // save new object to cache
        $this->stufe_cache[$stufe->getCategorieId()] = $stufe;
        return $stufe;
    }
}
