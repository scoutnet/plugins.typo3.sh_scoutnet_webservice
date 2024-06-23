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
use ScoutNet\Api\Model\Event;
use ScoutNet\Api\Model\Structure;

/**
 * The repository for Event
 */
class EventRepository extends AbstractScoutnetRepository
{
    /**
     * Cache all events, key is UID
     * @var array
     */
    private array $event_cache = [];

    /**
     * @param Structure $structure
     * @param array                                                 $filter
     *
     * @return Event[]
     * @api
     */
    public function findByStructureAndFilter(Structure $structure, array $filter): array
    {
        return $this->findByStructuresAndFilter([$structure], $filter);
    }

    /**
     * @param Structure[] $structures
     * @param array $filter
     *
     * @return Event[]
     * @api
     */
    public function findByStructuresAndFilter(array $structures, array $filter): array
    {
        // get UIDs from Structure Objects
        $ids = array_map(static function (Structure $structure) {return $structure->getUid();}, $structures);

        return $this->SN->get_events_for_global_id_with_filter($ids, $filter);
    }

    /**
     * @param array $filter
     *
     * @return Event[]
     * @api
     */
    public function findByFilter(array $filter): array
    {
        // TODO: fix this
        return $this->SN->get_events_for_global_id_with_filter(null, $filter);
    }

    /**
     * @param int $uid
     *
     * @return Event
     * @api
     */
    public function findByUid(int $uid): Event
    {
        // search in all Calendars
        return $this->event_cache[$uid] ?? $this->findByFilter(['event_ids' => [$uid]])[0];
    }

    /**
     * @param Event $event
     *
     * @return mixed
     * @throws Exception
     * @api
     */
    public function delete(Event $event): mixed
    {
        $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user['uid']);
        $this->SN->loginUser($be_user->getScoutnetUsername(), $be_user->getScoutnetApikey());

        return $this->SN->delete_event($event->getStructure()->getUid(), $event->getUid());
    }

    /**
     * @param Event $event
     *
     * @return mixed
     * @throws Exception
     * @api
     */
    public function update(Event $event): mixed
    {
        $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user['uid']);
        $this->SN->loginUser($be_user->getScoutnetUsername(), $be_user->getScoutnetApikey());

        return $this->SN->write_event($event);
    }

    /**
     * @param Event $event
     *
     * @return mixed
     * @throws Exception
     * @api
     */
    public function add(Event $event): mixed
    {
        $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user['uid']);
        $this->SN->loginUser($be_user->getScoutnetUsername(), $be_user->getScoutnetApikey());

        // add will set UID to -1
        $event->setUid(-1);

        return $this->SN->write_event($event);
    }
}
