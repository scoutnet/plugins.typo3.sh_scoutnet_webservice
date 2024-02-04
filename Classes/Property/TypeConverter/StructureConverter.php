<?php

namespace ScoutNet\ShScoutnetWebservice\Property\TypeConverter;

use Exception;
use ScoutNet\ShScoutnetWebservice\Domain\Model\Structure;
use ScoutNet\ShScoutnetWebservice\Domain\Repository\StructureRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Property\Exception\InvalidSourceException;
use TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;

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
class StructureConverter extends PersistentObjectConverter
{
    /**
     * Fetch an object from persistence layer.
     *
     * @param mixed  $identity
     * @param string $targetType
     *
     * @return object
     * @throws TargetNotFoundException
     * @throws InvalidSourceException
     */
    protected function fetchObjectFromPersistence($identity, string $targetType): object
    {
        $object = null;
        $identity = str_replace(Structure::class . ':', '', $identity);
        if (ctype_digit((string)$identity)) {
            // load Object via API
            /** @var StructureRepository $structureRepository */
            $structureRepository = GeneralUtility::makeInstance(StructureRepository::class);

            try {
                $object = $structureRepository->findByUid($identity);
            } catch (Exception $e) {
                $object = null;
            }
        } else {
            throw new InvalidSourceException('The identity property "' . $identity . '" is no UID.', 1297931020);
        }

        if ($object === null) {
            throw new TargetNotFoundException('Object with identity "' . print_r($identity, true) . '" not found.', 1297933823);
        }

        return $object;
    }
}
