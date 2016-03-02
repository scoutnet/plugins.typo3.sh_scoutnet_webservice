<?php
namespace ScoutNet\ShScoutnetWebservice\Domain\Model;

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
 * Event
 *
 */
class Event extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
	/**
	 * @var string
	 * @validate NotEmpty
	 * @validate StringLength(minimum=2, maximum=80)
	 */
	protected $title = '';

	/**
	 * @var string
	 * @validate StringLength(minimum=2, maximum=255)
	 */
	protected $organizer = '';

	/**
	 * @var string
	 * @validate StringLength(minimum=2, maximum=255)
	 */
	protected $targetGroup = '';

	/**
	 * @var \DateTime
	 * @validate NotEmpty
	 */
	protected $startDate;
	/**
	 * @var string
	 */
	protected $startTime;

	/**
	 * @var \DateTime
	 */
	protected $endDate;
	/**
	 * @var string
	 */
	protected $endTime;

	/**
	 * @var string
	 * @validate StringLength(minimum=3, maximum=6)
	 */
	protected $zip;

	/**
	 * @var string
	 * @validate StringLength(minimum=2, maximum=255)
	 */
	protected $location;

	/**
	 * @var string
	 * @validate StringLength(minimum=3, maximum=255)
	 */
	protected $urlText;

	/**
	 * @var string
	 * @validate StringLength(minimum=3, maximum=255)
	 */
	protected $url;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var array
	 */
	protected $stufen = array();

	/**
	 * @var array
	 */
	protected $categories = array();
	
	/**
	 * Kalender
	 *
	 * @var \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure
	 * validate NotEmpty
	 * @lazy
	 */
	protected $structure = NULL;

	/**
	 * changedBy
	 *
	 * @var \ScoutNet\ShScoutnetWebservice\Domain\Model\User
	 */
	protected $changedBy = NULL;

	/**
	 * changedBy
	 *
	 * @var \ScoutNet\ShScoutnetWebservice\Domain\Model\User
	 */
	protected $createdBy = NULL;

	/**
	 * createdAt
	 *
	 * @var \DateTime
	 */
	protected $createdAt;

	/**
	 * changedAt
	 *
	 * @var \DateTime
	 */
	protected $changedAt;

	/**
	 * @return string
	 */
	public function getTitle () {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle ($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getOrganizer () {
		return $this->organizer;
	}

	/**
	 * @param string $organizer
	 */
	public function setOrganizer ($organizer) {
		$this->organizer = $organizer;
	}

	/**
	 * @return string
	 */
	public function getTargetGroup () {
		return $this->targetGroup;
	}

	/**
	 * @param string $targetGroup
	 */
	public function setTargetGroup ($targetGroup) {
		$this->targetGroup = $targetGroup;
	}

	/**
	 * @return \DateTime
	 */
	public function getStartDate () {
		return $this->startDate;
	}

	/**
	 * @param \DateTime $startDate
	 */
	public function setStartDate ($startDate) {
		$this->startDate = $startDate;
	}

	/**
	 * @return string
	 */
	public function getStartTime () {
		return $this->startTime;
	}

	/**
	 * @param string $startTime
	 */
	public function setStartTime ($startTime) {
		$this->startTime = $startTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndDate () {
		return $this->endDate;
	}

	/**
	 * @param \DateTime $endDate
	 */
	public function setEndDate ($endDate) {
		$this->endDate = $endDate;
	}

	/**
	 * @return string
	 */
	public function getEndTime () {
		return $this->endTime;
	}

	/**
	 * @param string $endTime
	 */
	public function setEndTime ($endTime) {
		$this->endTime = $endTime;
	}

	/**
	 * @return string
	 */
	public function getZip () {
		return $this->zip;
	}

	/**
	 * @param string $zip
	 */
	public function setZip ($zip) {
		$this->zip = $zip;
	}

	/**
	 * @return string
	 */
	public function getLocation () {
		return $this->location;
	}

	/**
	 * @param string $location
	 */
	public function setLocation ($location) {
		$this->location = $location;
	}

	/**
	 * @return string
	 */
	public function getUrlText () {
		return $this->urlText;
	}

	/**
	 * @param string $urlText
	 */
	public function setUrlText ($urlText) {
		$this->urlText = $urlText;
	}

	/**
	 * @return string
	 */
	public function getUrl () {
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl ($url) {
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getDescription () {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription ($description) {
		$this->description = $description;
	}

	/**
	 * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure
	 */
	public function getStructure () {
		return $this->structure;
	}

	/**
	 * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\Structure $structure
	 */
	public function setStructure ($structure) {
		$this->structure = $structure;
	}

	/**
	 * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\User
	 */
	public function getChangedBy () {
		return $this->changedBy;
	}

	/**
	 * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\User $changedBy
	 */
	public function setChangedBy ($changedBy) {
		$this->changedBy = $changedBy;
	}

	/**
	 * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\User
	 */
	public function getCreatedBy () {
		return $this->createdBy;
	}

	/**
	 * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\User $createdBy
	 */
	public function setCreatedBy ($createdBy) {
		$this->createdBy = $createdBy;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt () {
		return $this->createdAt;
	}

	/**
	 * @param \DateTime $createdAt
	 */
	public function setCreatedAt ($createdAt) {
		$this->createdAt = $createdAt;
	}

	/**
	 * @return \DateTime
	 */
	public function getChangedAt () {
		return $this->changedAt;
	}

	/**
	 * @param \DateTime $changedAt
	 */
	public function setChangedAt ($changedAt) {
		$this->changedAt = $changedAt;
	}

	/**
	 * @return array
	 */
	public function getCategories () {
		return $this->categories;
	}

	/**
	 * @param array $categories
	 */
	public function setCategories ($categories) {
		$this->categories = $categories;
	}



	public function getAuthor() {
		if ($this->changedBy != null) return $this->changedBy;
		return $this->createdBy;
	}

	/**
	 * @return string
     */
	public function getStufenImages() {
		if (isset($this->stufen) && $this->stufen != null) {
			
			$stufen = "";
			/** @var \ScoutNet\ShScoutnetWebservice\Domain\Model\Stufe $stufe */
			foreach ($this->stufen as $stufe) {
				$stufen .= $stufe->getImageURL();
			}

			return (string) $stufen;
		}
		return (string) "";
	}





	public function getStartTimestamp() {
		if ($this->startTime) {
			$startTimestamp = \DateTime::createFromFormat('Y-m-d H:i:s',$this->startDate->format('Y-m-d').' '.$this->startTime);
		} else {
			$startTimestamp = \DateTime::createFromFormat('Y-m-d H:i:s',$this->startDate->format('Y-m-d').' 00:00:00');
		}

		return $startTimestamp;
	}

	public function getEndTimestamp() {
		if ($this->endDate && $this->endTime) {
			$endTimestamp = \DateTime::createFromFormat('Y-m-d H:i:s',$this->endDate->format('Y-m-d').' '.$this->endTime);
		} elseif ($this->endTime) {
			$endTimestamp = \DateTime::createFromFormat('Y-m-d H:i:s',$this->startDate->format('Y-m-d').' '.$this->endTime);
		} elseif ($this->endDate) {
			$endTimestamp = \DateTime::createFromFormat('Y-m-d H:i:s',$this->endDate->format('Y-m-d').' 00:00:00');
		} else {
			$endTimestamp = $this->getStartTimestamp();
		}
		return $endTimestamp;
	}

	public function getShowEndDateOrTime() {
		return $this->getShowEndDate() || $this->getShowEndTime();
	}

	public function getShowEndDate() {
		return !is_null($this->endDate) && $this->endDate != 0 && $this->endDate != $this->startDate;
	}

	public function getShowEndTime() {
		return !is_null($this->endTime);
	}

	public function getAllDayEvent() {
		return is_null($this->startTime);
	}

	public function getStartYear() {
		return $this->startDate->format('Y');
	}
	public function getStartMonth() {
		return $this->startDate->format('m');
	}

	/**
	 * @return mixed
	 */
	public function getStufen () {
		return $this->stufen;
	}

	/**
	 * @param mixed $stufen
	 */
	public function setStufen ($stufen) {
		$this->stufen = $stufen;
	}

	/**
	 * @param $stufe
     */
	public function addStufe($stufe) {
		$this->stufen[] = $stufe;
	}

	public function getShowDetails() {
		return trim($this->getDescription().$this->getZip().$this->getLocation().$this->getOrganizer().$this->getTargetGroup().$this->getUrl()) !== '';
	}

	/**
	 * @param $uid
     */
	public function setUid ($uid) {
		$this->uid = $uid;
	}
}
