<?php

namespace ScoutNet\ShScoutnetWebservice\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

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
 */
class Event extends AbstractEntity
{
    /**
     * @var string
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate NotEmpty
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate StringLength(minimum=2, maximum=80)
     */
    protected string $title;

    /**
     * @var string
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate StringLength(minimum=2, maximum=255)
     */
    protected string $organizer;

    /**
     * @var string
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate StringLength(minimum=2, maximum=255)
     */
    protected string $targetGroup;

    /**
     * @var DateTime
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate NotEmpty
     */
    protected DateTime $startDate;
    /**
     * @var string
     */
    protected string $startTime;

    /**
     * @var DateTime
     */
    protected DateTime $endDate;
    /**
     * @var string
     */
    protected string $endTime;

    /**
     * @var string
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate StringLength(minimum=3, maximum=255)
     */
    protected string $zip;

    /**
     * @var string
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate StringLength(minimum=2, maximum=255)
     */
    protected string $location;

    /**
     * @var string
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate StringLength(minimum=3, maximum=255)
     */
    protected string $urlText;

    /**
     * @var string
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate StringLength(minimum=3, maximum=255)
     */
    protected string $url;

    /**
     * @var string
     */
    protected string $description;

    /**
     * @var Section[]
     */
    protected array $sections = [];

    /**
     * @var Category[]
     */
    protected array $categories = [];

    /**
     * Kalender
     *
     * @var Structure
     * @TYPO3\\CMS\\Extbase\\Annotation\\Validate NotEmpty
     */
    protected Structure $structure;

    /**
     * changedBy
     *
     * @var User
     */
    protected User $changedBy;

    /**
     * changedBy
     *
     * @var User
     */
    protected User $createdBy;

    /**
     * createdAt
     *
     * @var DateTime
     */
    protected DateTime $createdAt;

    /**
     * changedAt
     *
     * @var DateTime
     */
    protected DateTime $changedAt;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title ?? '';
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getOrganizer(): string
    {
        return $this->organizer ?? '';
    }

    /**
     * @param string $organizer
     */
    public function setOrganizer(string $organizer): void
    {
        $this->organizer = $organizer;
    }

    /**
     * @return string
     */
    public function getTargetGroup(): string
    {
        return $this->targetGroup ?? '';
    }

    /**
     * @param string $targetGroup
     */
    public function setTargetGroup(string $targetGroup): void
    {
        $this->targetGroup = $targetGroup;
    }

    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     */
    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return string|null
     */
    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    /**
     * @param string|null $startTime
     */
    public function setStartTime(?string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * @return DateTime|null
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime|null $endDate
     */
    public function setEndDate(?DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return string|null
     */
    public function getEndTime(): ?string
    {
        return $this->endTime;
    }

    /**
     * @param string|null $endTime
     */
    public function setEndTime(?string $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * @return string
     */
    public function getZip(): string
    {
        return $this->zip ?? '';
    }

    /**
     * @param string $zip
     */
    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location ?? '';
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getUrlText(): string
    {
        return $this->urlText ?? '';
    }

    /**
     * @param string $urlText
     */
    public function setUrlText(string $urlText): void
    {
        $this->urlText = $urlText;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url ?? '';
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Structure|null
     */
    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    /**
     * @param Structure $structure
     */
    public function setStructure(Structure $structure): void
    {
        $this->structure = $structure;
    }

    /**
     * @return User|null
     */
    public function getChangedBy(): ?User
    {
        return $this->changedBy;
    }

    /**
     * @param User $changedBy
     */
    public function setChangedBy(User $changedBy): void
    {
        $this->changedBy = $changedBy;
    }

    /**
     * @return User|null
     */
    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy(User $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getChangedAt(): DateTime
    {
        return $this->changedAt;
    }

    /**
     * @param DateTime $changedAt
     */
    public function setChangedAt(DateTime $changedAt): void
    {
        $this->changedAt = $changedAt;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category): void
    {
        $this->categories[$category->getUid()] = $category;
    }

    /**
     * @return User|null
     * @deprecated
     */
    public function getAuthor(): ?User
    {
        if ($this->changedBy != null) {
            return $this->changedBy;
        }
        return $this->createdBy;
    }

    /**
     * @return Section[]
     * @deprecated
     */
    public function getStufen(): array
    {
        return $this->getSections();
    }

    /**
     * @param Section[] $sections
     * @deprecated
     */
    public function setStufen(array $sections): void
    {
        $this->setSections($sections);
    }

    /**
     * @param Section $stufe
     * @deprecated
     */
    public function addStufe(Section $stufe): void
    {
        $this->addSection($stufe);
    }

    /**
     * @return Section[]
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * @param Section[] $sections
     */
    public function setSections(array $sections): void
    {
        $this->sections = $sections;
    }

    /**
     * @param Section $section
     */
    public function addSection(Section $section): void
    {
        $this->sections[] = $section;
    }

    /**
     * @return string
     * @deprecated
     */
    public function getStufenImages(): string
    {
        return $this->getSectionImages();
    }

    /**
     * @return string
     */
    public function getSectionImages(): string
    {
        $sections = '';
        foreach ($this->getSections() as $section) {
            $sections .= $section->getImageURL();
        }
        return (string)$sections;
    }

    /**
     * @return array
     * @deprecated
     */
    public function getStufenCategories(): array
    {
        return $this->getSectionCategories();
    }

    /**
     * @return array
     */
    public function getSectionCategories(): array
    {
        $categories = [];
        foreach ($this->getSections() as $section) {
            $cat = $section->getCategory();
            $categories[$cat->getUid()] = $cat;
        }

        return $categories;
    }

    /**
     * @return DateTime
     */
    public function getStartTimestamp(): DateTime
    {
        if ($this->startTime) {
            $startTimestamp = DateTime::createFromFormat('Y-m-d H:i:s', $this->startDate->format('Y-m-d') . ' ' . $this->startTime . (substr_count($this->startTime, ':') == 1 ? ':00' : ''));
        } else {
            $startTimestamp = DateTime::createFromFormat('Y-m-d H:i:s', $this->startDate->format('Y-m-d') . ' 00:00:00');
        }

        return $startTimestamp;
    }

    /**
     * @return DateTime
     */
    public function getEndTimestamp(): DateTime
    {
        if ($this->endDate && $this->endTime) {
            $endTimestamp = DateTime::createFromFormat('Y-m-d H:i:s', $this->endDate->format('Y-m-d') . ' ' . $this->endTime . (substr_count($this->endTime, ':') == 1 ? ':00' : ''));
        } elseif ($this->endTime) {
            $endTimestamp = DateTime::createFromFormat('Y-m-d H:i:s', $this->startDate->format('Y-m-d') . ' ' . $this->endTime . (substr_count($this->endTime, ':') == 1 ? ':00' : ''));
        } elseif ($this->endDate) {
            $endTimestamp = DateTime::createFromFormat('Y-m-d H:i:s', $this->endDate->format('Y-m-d') . ' 00:00:00');
        } else {
            $endTimestamp = $this->getStartTimestamp();
        }
        return $endTimestamp;
    }

    /**
     * @return bool
     */
    public function getShowEndDateOrTime(): bool
    {
        return $this->getShowEndDate() || $this->getShowEndTime();
    }

    /**
     * @return bool
     */
    public function getShowEndDate(): bool
    {
        return !is_null($this->endDate) && $this->endDate != 0 && $this->endDate != $this->startDate;
    }

    /**
     * @return bool
     */
    public function getShowEndTime(): bool
    {
        return !is_null($this->endTime);
    }

    /**
     * @return bool
     */
    public function getAllDayEvent(): bool
    {
        return is_null($this->startTime);
    }

    /**
     * @return string
     */
    public function getStartYear(): string
    {
        return $this->startDate->format('Y');
    }

    /**
     * @return string
     */
    public function getStartMonth(): string
    {
        return $this->startDate->format('m');
    }

    /**
     * @return bool
     */
    public function getShowDetails(): bool
    {
        return trim($this->getDescription() . $this->getZip() . $this->getLocation() . $this->getOrganizer() . $this->getTargetGroup() . $this->getUrl()) !== '';
    }

    /**
     * @param int $uid
     */
    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @param Event $event
     */
    public function copyProperties(Event $event): void
    {
        $copyProperties = [ 'title', 'organizer', 'targetGroup', 'startDate', 'startTime', 'endDate', 'endTime', 'zip', 'location', 'urlText', 'url', 'description', 'structure', 'categories'];

        foreach ($copyProperties as $property) {
            $this->{$property} = $event->{$property};
        }
    }
}
