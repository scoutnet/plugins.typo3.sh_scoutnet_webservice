<?php

namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

use ScoutNet\ShScoutnetWebservice\Domain\Model\BackendUser;
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
class StructureRepository extends AbstractScoutnetRepository
{
    const AUTH_NO_RIGHT = 1;
    const AUTH_WRITE_ALLOWED = 0;
    const AUTH_PENDING = 2;

    /**
     * @var \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure[]
     */
    protected $structure_cache = [];

    /**
     * @param array|int $ids can be both a list of IDs or one single ID
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure[]
     * @deprecated
     */
    public function findKalenderByGlobalid($ids): array
    {
        $kalenders = [];
        foreach ($this->loadDataFromScoutnet($ids, ['kalenders' => []]) as $record) {
            if ($record['type'] === 'kalender') {
                $kalender = $this->convertToStructure($record['content']);
                $kalenders[] = $kalender;
            }
        }

        return $kalenders;
    }

    /**
     * Finds all Structures by their UIDs
     *
     * @param int[] $uids
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure[]
     */
    public function findByUids(array $uids): array
    {
        $structures = [];
        $uidsToLoad = [];

        // check cache
        foreach ($uids as $uid) {
            if (isset($this->structure_cache[$uid])) {
                $structures[] = $this->structure_cache[$uid];
            } else {
                $uidsToLoad[] = $uid;
            }
        }

        if (count($uidsToLoad) > 0) {
            foreach ($this->loadDataFromScoutnet($uidsToLoad, ['kalenders' => []]) as $record) {
                if ($record['type'] === 'kalender') {
                    $structures[] = $this->convertToStructure($record['content']);
                }
            }
        }

        return $structures;
    }

    /**
     * find a Single Structure by UID
     *
     * @param int $uid
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure
     */
    public function findByUid(int $uid): Structure
    {
        return $this->findByUids([$uid])[0];
    }

    /**
     * @param Structure        $structure
     * @param BackendUser|null $be_user    If user is not set, we use current logged in User
     *
     * @return mixed
     * @throws \Exception
     */
    public function hasWritePermissionsToStructure(Structure $structure, BackendUser $be_user=null)
    {
        if ($be_user === null) {
            $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user['uid']);
        }

        $type = 'event';
        $auth = $this->authHelper->generateAuth($be_user->getTxShscoutnetApikey(), $type . $structure->getUid() . $be_user->getTxShscoutnetUsername());

        return $this->SN->checkPermission($type, $structure->getUid(), $be_user->getTxShscoutnetUsername(), $auth);
    }

    /**
     * @param Structure        $structure
     * @param BackendUser|null $be_user
     *
     * @return mixed
     * @throws \Exception
     */
    public function requestWritePermissionsForStructure(Structure $structure, BackendUser $be_user=null)
    {
        if ($be_user === null) {
            $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user['uid']);
        }

        $type = 'event';
        $auth = $this->authHelper->generateAuth($be_user->getTxShscoutnetApikey(), $type . $structure->getUid() . $be_user->getTxShscoutnetUsername());
        return $this->SN->requestPermission($type, $structure->getUid(), $be_user->getTxShscoutnetUsername(), $auth);
    }

    /**
     * @param $array
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure
     */
    public function convertToStructure(array $array): Structure
    {
        $structure = new Structure();

        $structure->setUid($array['ID']);
        $structure->setLevel($array['Ebene']);
        $structure->setName($array['Name']);
        $structure->setVerband($array['Verband']);
        $structure->setIdent($array['Ident']);
        $structure->setLevelId($array['Ebene_Id']);

        $structure->setUsedCategories($array['Used_Kategories']);
        $structure->setForcedCategories($array['Forced_Kategories']);

        $this->structure_cache[$structure->getUid()] = $structure;
        return $structure;
    }
}
