<?php

namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

use DateTime;
use Exception;
use ScoutNet\ShScoutnetWebservice\Domain\Model\BackendUser;
use ScoutNet\ShScoutnetWebservice\Domain\Model\Event;
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
class EventRepository extends AbstractScoutnetRepository
{
    /**
     * Cache all events, key is UID
     * @var array
     */
    private array $event_cache = [];

    /**
     * EventRepository constructor.
     *
     * @param StructureRepository $structureRepository
     * @param CategoryRepository  $categoryRepository
     * @param UserRepository      $userRepository
     * @param SectionRepository   $sectionRepository
     */
    public function __construct(
        protected readonly StructureRepository $structureRepository,
        protected readonly CategoryRepository $categoryRepository,
        protected readonly UserRepository $userRepository,
        protected readonly SectionRepository $sectionRepository
    ) {}

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
        $ids = array_map(function (Structure $structure) {return $structure->getUid();}, $structures);

        return $this->convertRecords($this->loadDataFromScoutnet($ids, ['events' => $filter]));
    }

    /**
     * @param array $filter
     *
     * @return Event[]
     * @api
     */
    public function findByFilter(array $filter): array
    {
        return $this->convertRecords($this->loadDataFromScoutnet(null, ['events' => $filter]));
    }

    /**
     * @param array|null $records
     *
     * @return array
     */
    private function convertRecords(?array $records): array
    {
        $events = [];
        foreach ($records as $record) {
            if ($record['type'] === 'user') {
                // convert and save to cache
                $this->userRepository->convertToUser($record['content']);
            } elseif ($record['type'] === 'stufe') {
                // convert and save to cache
                $this->sectionRepository->convertToSection($record['content']);
            } elseif ($record['type'] === 'kalender') {
                // convert and save to cache
                $this->structureRepository->convertToStructure($record['content']);
            } elseif ($record['type'] === 'event') {
                // convert and save to cache
                $event = $this->convertToEvent($record['content']);
                $events[] = $event;
            }
        }
        return $events;
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
        /** @var BackendUser $be_user */
        $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user['uid']);

        $type = 'event';
        $auth = $this->authHelper->generateAuth($be_user->getScoutnetApikey(), $type . $event->getStructure()->getUid() . $event->getUid() . $be_user->getScoutnetUsername());

        return $this->SN->deleteObject($type, $event->getStructure()->getUid(), $event->getUid(), $be_user->getScoutnetUsername(), $auth);
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
        /** @var BackendUser $be_user */
        $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user['uid']);

        $data = $this->convertFromEvent($event);
        $id = $event->getUid();
        $type = 'event';
        $auth = $this->authHelper->generateAuth($be_user->getScoutnetApikey(), $type . $id . serialize($data) . $be_user->getScoutnetUsername());

        return $this->SN->setData($type, $id, $data, $be_user->getScoutnetUsername(), $auth);
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
        /** @var BackendUser $be_user */
        $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user['uid']);

        $data = $this->convertFromEvent($event);
        $data['ID'] = -1;
        $id = -1;
        $type = 'event';
        $auth = $this->authHelper->generateAuth($be_user->getScoutnetApikey(), $type . $id . serialize($data) . $be_user->getScoutnetUsername());

        return $this->SN->setData($type, $id, $data, $be_user->getScoutnetUsername(), $auth);
    }

    /**
     * convert from API Array to Event Object
     *
     * @param array $array
     *
     * @return Event
     */
    public function convertToEvent(array $array): Event
    {
        $event = new Event();

        $event->setTitle($array['Title']);
        $event->setUid($array['UID']);
        $event->setOrganizer($array['Organizer']);
        $event->setTargetGroup($array['Target_Group']);
        $event->setStartDate(DateTime::createFromFormat('Y-m-d H:i:s', gmstrftime('%Y-%m-%d 00:00:00', $array['Start'])));
        $event->setStartTime($array['All_Day'] ? null : gmstrftime('%H:%M:00', $array['Start']));
        $event->setEndDate($array['End'] == 0 ? null : DateTime::createFromFormat('Y-m-d H:i:s', gmstrftime('%Y-%m-%d 00:00:00', $array['End'])));
        $event->setEndTime($array['All_Day'] ? null : gmstrftime('%H:%M:00', $array['End']));

        $event->setZip($array['ZIP']);

        $event->setLocation($array['Location']);
        $event->setUrlText($array['URL_Text']);
        $event->setUrl($array['URL']);
        $event->setDescription($array['Description']);

        $event->setChangedBy($this->userRepository->findByUsername($array['Last_Modified_By']));
        $event->setCreatedBy($this->userRepository->findByUsername($array['Created_By']));

        $event->setChangedAt($array['Last_Modified_At'] == 0 ? null : DateTime::createFromFormat('U', $array['Last_Modified_At']));
        $event->setCreatedAt($array['Created_At'] == 0 ? null : DateTime::createFromFormat('U', $array['Created_At']));

        if (isset($array['Stufen'])) {
            foreach ($array['Stufen'] as $sectionId) {
                $section = $this->sectionRepository->findByCategoryId($sectionId);
                if ($section != null) {
                    $event->addSection($section);
                }
            }
        }

        $event->setStructure($this->structureRepository->findByUid((int)($array['Kalender'])));

        if (isset($array['Keywords'])) {
            foreach ($array['Keywords'] as $id => $text) {
                $category = $this->categoryRepository->convertToCategory(['ID' => $id, 'Text' => $text]);
                if ($category != null) {
                    $event->addCategory($category);
                }
            }
        }
        //$event->setCategories($array['Keywords']);

        // save new object to cache
        $this->event_cache[$event->getUid()] = $event;
        return $event;
    }

    /**
     * Converts from Event Object to Api Data
     *
     * @param Event $event
     *
     * @return array
     */
    public function convertFromEvent(Event $event): array
    {
        $array = [
            'ID' => $event->getUid() ?? -1,
            'SSID' => $event->getStructure()->getUid(),
            'Title' => $event->getTitle(),
            'Organizer' => $event->getOrganizer(),
            'Target_Group' => $event->getTargetGroup(),
            'Start' => $event->getStartTimestamp() instanceof DateTime ? DateTime::createFromFormat('d.m.Y H:i:s T', $event->getStartTimestamp()->format('d.m.Y H:i:s') . ' UTC')->format('U') : '',
            'End' => $event->getEndTimestamp() instanceof DateTime ? DateTime::createFromFormat('d.m.Y H:i:s T', $event->getEndTimestamp()->format('d.m.Y H:i:s') . ' UTC')->format('U') : '',
            'All_Day' => $event->getAllDayEvent(),
            'ZIP' => $event->getZip(),
            'Location' => $event->getLocation(),
            'URL_Text' => $event->getUrlText(),
            'URL' => $event->getUrl(),
            'Description' => $event->getDescription(),
            'Stufen' => [],
            'Keywords' => [],
        ];

        $customKeywords = [];
        foreach ($event->getCategories() as $category) {
            if ($category->getUid() == null) {
                $customKeywords[] = $category->getText();
            } else {
                $array['Keywords'][$category->getUid()] = $category->getText();
            }
        }

        if (count($customKeywords) > 0) {
            $array['Custom_Keywords'] = $customKeywords;
        }

        return $array;
    }
}
