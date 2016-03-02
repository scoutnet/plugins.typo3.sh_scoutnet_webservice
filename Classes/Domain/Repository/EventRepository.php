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
 * The repository for Event
 */
class EventRepository extends AbstractRepository {
    /**
     * @var \ScoutNet\ShScoutnetWebservice\Domain\Repository\StructureRepository
     * @inject
     */
    protected $kalenderRepository = null;

    /**
     * @var \ScoutNet\ShScoutnetWebservice\Domain\Repository\BackendUserRepository
     * @inject
     */
    protected $backendUserRepository = null;

    /**
     * @var \ScoutNet\ShScoutnetWebservice\Domain\Repository\CategorieRepository
     * @inject
     */
    protected $categorieRepository = null;


    private $user_cache = array();
    private $stufen_cache = array();
    private $event_cache = array();

    public function findByStructureAndFilter(\ScoutNet\ShScoutnetWebservice\Domain\Model\Structure $structure, $filter) {
        return $this->get_events_for_global_id_with_filter(array($structure->getUid()), $filter);
    }

    /**
     * @param integer $ids
     * @param mixed $filter
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Event[]
     * @deprecated
     */
    public function get_events_for_global_id_with_filter($ids, $filter){
        $events = array();
        foreach ($this->load_data_from_scoutnet($ids,array('events'=>$filter)) as $record) {

            if ($record['type'] === 'user'){
                // we convert and save to cache
                $this->convertToUser($record['content']);
            } elseif ($record['type'] === 'stufe'){
                // we convert and save to cache
                $this->convertToStufe($record['content']);
            } elseif ($record['type'] === 'kalender'){
                // we convert and save to cache
                $this->kalenderRepository->convertToStructure($record['content']);
            } elseif ($record['type'] === 'event') {
                $event = $this->convertToEvent($record['content']);
                $events[] = $event;
            }
        }
        return $events;
    }

    /**
     * @param integer $uid
     *
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Event
     */
    public function findByUid($uid) {
        // search in all Calendars
        return $this->get_events_for_global_id_with_filter(null,array('event_ids'=>array($uid)))[0];
    }

    /**
     * @param $ids
     * @param $event_ids
     *
     * @return array
     * @deprecated
     */
    public function get_events_with_ids($ids,$event_ids){
        return $this->get_events_for_global_id_with_filter($ids,array('event_ids'=>$event_ids));
    }

    private function get_stufe_by_id($id) {
        return $this->stufen_cache[$id];
    }

    private function get_user_by_id($id) {
        return $this->user_cache[$id];
    }

    /**
     * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\Event $event
     *
     * @return mixed
     * @throws \Exception
     */
    public function delete(\ScoutNet\ShScoutnetWebservice\Domain\Model\Event $event) {
		/** @var \ScoutNet\ShScoutnetWebservice\Domain\Model\BackendUser $be_user */
        $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user["uid"]);

        $type = 'event';
        $auth = $this->authHelper->generateAuth($be_user->getTxShscoutnetkalenderScoutnetApikey(),$type.$event->getStructure()->getUid().$event->getUid().$be_user->getTxShscoutnetkalenderScoutnetUsername());

        return $this->SN->deleteObject($type,$event->getStructure()->getUid(),$event->getUid(),$be_user->getTxShscoutnetkalenderScoutnetUsername(),$auth);
    }

    /**
     * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\Event $event
     *
     * @return mixed
     * @throws \Exception
     */
    public function update(\ScoutNet\ShScoutnetWebservice\Domain\Model\Event $event){
		/** @var \ScoutNet\ShScoutnetWebservice\Domain\Model\BackendUser $be_user */
        $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user["uid"]);


        $data = $this->convertFromEvent($event);
        $id = $event->getUid();
        $type = 'event';
        $auth = $this->authHelper->generateAuth($be_user->getTxShscoutnetkalenderScoutnetApikey(),$type.$id.serialize($data).$be_user->getTxShscoutnetkalenderScoutnetUsername());

        return $this->SN->setData($type,$id,$data,$be_user->getTxShscoutnetkalenderScoutnetUsername(),$auth);
    }

    /**
     * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\Event $event
     *
     * @return mixed
     * @throws \Exception
     */
    public function add(\ScoutNet\ShScoutnetWebservice\Domain\Model\Event $event){
        /** @var \ScoutNet\ShScoutnetWebservice\Domain\Model\BackendUser $be_user */
        $be_user = $this->backendUserRepository->findByUid($GLOBALS['BE_USER']->user["uid"]);


        $data = $this->convertFromEvent($event);
        $data['ID'] = -1;
        $id = -1;
        $type = 'event';
        $auth = $this->authHelper->generateAuth($be_user->getTxShscoutnetkalenderScoutnetApikey(),$type.$id.serialize($data).$be_user->getTxShscoutnetkalenderScoutnetUsername());

        return $this->SN->setData($type,$id,$data,$be_user->getTxShscoutnetkalenderScoutnetUsername(),$auth);
    }

    public function convertToEvent($array){
        $event = new \ScoutNet\ShScoutnetWebservice\Domain\Model\Event();

        $event->setTitle($array['Title']);
        $event->setUid($array['UID']);
        $event->setOrganizer($array['Organizer']);
        $event->setTargetGroup($array['Target_Group']);
        $event->setStartDate(\DateTime::createFromFormat('Y-m-d H:i:s', gmstrftime("%Y-%m-%d 00:00:00",$array['Start'])));
        $event->setStartTime($array['All_Day']?null:gmstrftime('%H:%M:00',$array['Start']));
        $event->setEndDate($array['End'] == 0?null:\DateTime::createFromFormat('Y-m-d H:i:s', gmstrftime("%Y-%m-%d 00:00:00",$array['End'])));
        $event->setEndTime($array['All_Day']?null:gmstrftime('%H:%M:00',$array['End']));



        $event->setZip($array['ZIP']);

        $event->setLocation($array['Location']);
        $event->setUrlText($array['URL_Text']);
        $event->setUrl($array['URL']);
        $event->setDescription($array['Description']);

        $event->setChangedBy($this->get_user_by_id($array['Last_Modified_By']));
        $event->setCreatedBy($this->get_user_by_id($array['Created_By']));

        $event->setChangedAt($array['Last_Modified_At'] == 0?null:\DateTime::createFromFormat('U',$array['Last_Modified_At']));
        $event->setCreatedAt($array['Created_At'] == 0?null:\DateTime::createFromFormat('U',$array['Created_At']));


        if (isset($array['Stufen'])){
            foreach ($array['Stufen'] as $stufenId) {
                $stufe = $this->get_stufe_by_id($stufenId);
                if ($stufe != null) {
                    $event->addStufe($stufe);
                }
            }
        }

        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($event);

        $event->setStructure($this->kalenderRepository->findByUid(intval($array['Kalender'])));

        if (isset($array['Keywords'])) {
            foreach ($array['Keywords'] as $id => $text) {
                $categorie = $this->categorieRepository->convertToCategorie(array('ID'=>$id,'Text'=>$text));
                if ($categorie != null) {
                    $event->addCategorie($categorie);
                }
            }
        }
        //$event->setCategories($array['Keywords']);

        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->get_kalender_by_id($array['Kalender']));
        // save new object to cache
        $this->event_cache[$event->getUid()] = $event;
        return $event;
    }

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
        $this->stufen_cache[$stufe->getCategorieId()] = $stufe;
        return $stufe;
    }

    public function convertFromEvent(\ScoutNet\ShScoutnetWebservice\Domain\Model\Event $event) {
        $array = array(
            'ID' => $event->getUid() !== null?$event->getUid():-1,
            'SSID' => $event->getStructure()->getUid(),
            'Title' => $event->getTitle(),
            'Organizer' => $event->getOrganizer(),
            'Target_Group' => $event->getTargetGroup(),
            'Start' => $event->getStartTimestamp() instanceof \DateTime?\DateTime::createFromFormat('d.m.Y H:i:s T',$event->getStartTimestamp()->format('d.m.Y H:i:s').' UTC')->format('U'):'',
            'End' => $event->getEndTimestamp() instanceof \DateTime?\DateTime::createFromFormat('d.m.Y H:i:s T',$event->getEndTimestamp()->format('d.m.Y H:i:s').' UTC')->format('U'):'',
            'All_Day' => $event->getAllDayEvent(),
            'ZIP' => $event->getZip(),
            'Location' => $event->getLocation(),
            'URL_Text' => $event->getUrlText(),
            'URL' => $event->getUrl(),
            'Description' => $event->getDescription(),
            'Stufen' => array(),
            'Keywords' => array(),
        );

        $customKeywords = array();
        /** @var \ScoutNet\ShScoutnetWebservice\Domain\Model\Categorie $category */
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
