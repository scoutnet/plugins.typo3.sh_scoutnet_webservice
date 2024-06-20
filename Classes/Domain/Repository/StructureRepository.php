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

use Exception;
use ScoutNet\Api\Model\Structure;
use ScoutNet\ShScoutnetWebservice\Domain\Model\BackendUser;

/**
 * The repository for Event
 */
class StructureRepository extends AbstractScoutnetRepository
{
    /**
     * @var int User has no access to Structure
     * @api
     */
    public const AUTH_NO_RIGHT = 1;
    /**
     * @var int User has Write access to Structure
     * @api
     */
    public const AUTH_WRITE_ALLOWED = 0;
    /**
     * @var int User Right is Pending, Admin need to allow Right
     * @api
     */
    public const AUTH_PENDING = 2;

    /**
     * @var Structure[]
     */
    protected array $structure_cache = [];

    /**
     * @param int|array $ids can be both a list of IDs or one single ID
     *
     * @return Structure[]
     * @deprecated
     * @api
     */
    public function findKalenderByGlobalid(int|array $ids): array
    {
        $calendars = [];
        foreach ($this->loadDataFromScoutnet($ids, ['kalenders' => []]) as $record) {
            if ($record['type'] === 'kalender') {
                $kalender = $this->convertToStructure($record['content']);
                $calendars[] = $kalender;
            }
        }

        return $calendars;
    }

    /**
     * Finds all Structures by their UIDs
     *
     * @param int[] $uids
     *
     * @return Structure[]
     * @api
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
     * @return Structure
     * @api
     */
    public function findByUid(int $uid): Structure
    {
        return $this->findByUids([$uid])[0];
    }

    /**
     * @param Structure        $structure
     * @param BackendUser|null $be_user    If user is not set, we use current logged-in User
     *
     * @return mixed
     * @throws Exception
     * @api
     */
    public function hasWritePermissionsToStructure(Structure $structure, BackendUser $be_user = null): mixed
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
     * @throws Exception
     * @api
     */
    public function requestWritePermissionsForStructure(Structure $structure, BackendUser $be_user = null): mixed
    {
        if ($be_user === null) {
            $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user['uid']);
        }

        $type = 'event';
        $auth = $this->authHelper->generateAuth($be_user->getTxShscoutnetApikey(), $type . $structure->getUid() . $be_user->getTxShscoutnetUsername());
        return $this->SN->requestPermission($type, $structure->getUid(), $be_user->getTxShscoutnetUsername(), $auth);
    }

    /**
     * @param array $array
     *
     * @return Structure
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
