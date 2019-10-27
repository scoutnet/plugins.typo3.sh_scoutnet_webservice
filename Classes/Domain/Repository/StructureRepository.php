<?php
namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

use ScoutNet\ShScoutnetWebservice\Domain\Model\Structure;

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
 * The repository for Event
 */
class StructureRepository extends AbstractScoutnetRepository {

    const AUTH_NO_RIGHT = 1;
    const AUTH_WRITE_ALLOWED = 0;
    const AUTH_PENDING = 2;

    /**
     * @var \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure[]
     */
    protected $structure_cache = array();

    /**
     * @param $ids
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure[]
     * @deprecated
     */
    public function findKalenderByGlobalid($ids) {
        $kalenders = array();
        foreach ($this->loadDataFromScoutnet($ids,array('kalenders' =>array())) as $record) {
            if ($record['type'] === 'kalender'){
                $kalender = $this->convertToStructure($record['content']);
                $kalenders[] = $kalender;
            }
        }

        return $kalenders;
    }

    /**
     * @param integer[] $uids
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure[]
     */
    public function findByUids($uids) {
        $structures = array();
        $uidsToLoad = array();

        // check cache
        foreach ($uids as $uid) {
            if (isset($this->structure_cache[$uid])){
                $structures[] = $this->structure_cache[$uid];
            } else {
                $uidsToLoad[] = $uid;
            }
        }

        if (count($uidsToLoad) > 0) {
            foreach ($this->loadDataFromScoutnet($uidsToLoad, array('kalenders' => array())) as $record) {
                if ($record['type'] === 'kalender') {
                    $structures[] = $this->convertToStructure($record['content']);
                }
            }
        }

        return $structures;
    }

    /**
     * @param integer $uid
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure
     */
    public function findByUid($uid) {
        return $this->findByUids(array($uid))[0];
    }

    /**
     * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure $structure
     *
     * @return mixed
     * @throws \Exception
     */
    public function hasWritePermissionsToStructure(Structure $structure) {
        /** @var \ScoutNet\ShScoutnetWebservice\Domain\Model\BackendUser $be_user */
        $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user["uid"]);

        $type = 'event';
        $auth = $this->authHelper->generateAuth($be_user->getTxShscoutnetApikey(),$type.$structure->getUid().$be_user->getTxShscoutnetUsername());

        return $this->SN->checkPermission($type,$structure->getUid(),$be_user->getTxShscoutnetUsername(),$auth);
    }

    /**
     * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure $structure
     *
     * @return mixed
     * @throws \Exception
     */
    public function requestWritePermissionsForStructure(Structure $structure) {
        /** @var \ScoutNet\ShScoutnetWebservice\Domain\Model\BackendUser $be_user */
        $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user["uid"]);

        $type = 'event';
        $auth = $this->authHelper->generateAuth($be_user->getTxShscoutnetApikey(),$type.$structure->getUid().$be_user->getTxShscoutnetUsername());
        return $this->SN->requestPermission($type,$structure->getUid(),$be_user->getTxShscoutnetUsername(),$auth);
    }

    public function convertToStructure($array) {
        $structure = new Structure();
        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($array);

        $structure->setUid($array['ID']);
        $structure->setEbene($array['Ebene']);
        $structure->setName($array['Name']);
        $structure->setVerband($array['Verband']);
        $structure->setIdent($array['Ident']);
        $structure->setEbeneId($array['Ebene_Id']);

        $structure->setUsedCategories($array['Used_Kategories']);
        $structure->setForcedCategories($array['Forced_Kategories']);

        $this->structure_cache[$structure->getUid()] = $structure;
        return $structure;
    }
}
