<?php
/**
 * Copyright (c) 2016-2024 Stefan (Mütze) Horst
 *
 * I don't have the time to read through all the licences to find out
 * what they exactly say. But it's simple. It's free for non-commercial
 * projects, but as soon as you make money with it, I want my share :-)
 * (License: Free for non-commercial use)
 *
 * Authors: Stefan (Mütze) Horst <muetze@scoutnet.de>
 */

namespace ScoutNet\ShScoutnetWebservice\Property\TypeConverter;

use Exception;
use ScoutNet\Api\Models\Structure;
use ScoutNet\ShScoutnetWebservice\Domain\Repository\StructureRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Property\Exception\InvalidSourceException;
use TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;

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
            } catch (Exception) {
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
