<?php

namespace ScoutNet\ShScoutnetWebservice\Property\TypeConverter;

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
class EventConverter extends \TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter {
    /**
     * @var \ScoutNet\ShScoutnetWebservice\Domain\Repository\EventRepository
     */
    protected $eventRepository = null;

    /**
     * @var array<string>
     */
    protected $sourceTypes = array('string');

    /**
     * @var string
     */
    protected $targetType = 'ScoutNet\ShScoutnetWebservice\Domain\Model\Event';

    /**
     * @var integer
     */
    protected $priority = 1;

    /**
     * We can only convert empty strings to array or array to array.
     *
     * @param mixed $source
     * @param string $targetType
     * @return boolean
     */
    public function canConvertFrom($source, $targetType) {
        return is_numeric($source);
    }

    /**
     * Convert from $source to $targetType, a noop if the source is an array.
     * If it is an empty string it will be converted to an empty array.
     *
     * @param string|array $source
     * @param string $targetType
     * @param array $convertedChildProperties
     * @param \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface $configuration
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Event
     * @api
     */
    public function convertFrom($source, $targetType, array $convertedChildProperties = array(), \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface $configuration = NULL) {
        if (is_numeric($source)) {
            $this->eventRepository = $this->objectManager->get('\ScoutNet\ShScoutnetWebservice\Domain\Repository\EventRepository');

            try {
                return $this->eventRepository->findByUid(intval($source));
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }


}