<?php
/**
 * Copyright (c) 2009-2024 Stefan (Mütze) Horst
 *
 * I don't have the time to read through all the licences to find out
 * what they exactly say. But it's simple. It's free for non-commercial
 * projects, but as soon as you make money with it, I want my share :-)
 * (License: Free for non-commercial use)
 *
 * Authors: Stefan (Mütze) Horst <muetze@scoutnet.de>
 */

namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

use ScoutNet\Api\Helpers\JsonRPCClientHelper;
use ScoutNet\Api\ScoutnetApi;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service "SN" for the "sh_scoutnet_webservice" extension.
 *
 * @author	Stefan Horst <muetze@scoutnet.de>
 */
class AbstractScoutnetRepository
{
    protected BackendUserRepository $backendUserRepository;

    protected ScoutnetApi $SN;
    protected JsonRPCClientHelper $json;

    /**
     * @param BackendUserRepository $backendUserRepository
     */
    public function injectBackendUserRepository(BackendUserRepository $backendUserRepository): void
    {
        $this->backendUserRepository = $backendUserRepository;
    }

    /**
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function initializeObject(): void
    {
        /** @var ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $extConfig = $extensionConfiguration->get('sh_scoutnet_webservice');

        $api_url = $extConfig['ScoutnetJsonAPIUrl'] ?? 'localhost';

        $this->SN = GeneralUtility::makeInstance(ScoutnetApi::class, $api_url);
        $this->json = GeneralUtility::makeInstance(JsonRPCClientHelper::class, $api_url);
    }
}
