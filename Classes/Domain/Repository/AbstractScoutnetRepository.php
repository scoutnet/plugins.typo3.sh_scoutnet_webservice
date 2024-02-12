<?php

namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

use ScoutNet\ShScoutnetWebservice\Helpers\AuthHelper;
use ScoutNet\ShScoutnetWebservice\Helpers\JsonRPCClientHelper;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
*  Copyright notice
*
*  (c) 2009 Stefan Horst <s.horst@dpsg-koeln.de>
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

/**
 * Service "SN" for the "sh_scoutnet_webservice" extension.
 *
 * @author	Stefan Horst <muetze@scoutnet.de>
 */
class AbstractScoutnetRepository
{
    /**
     * @var AuthHelper
     */
    protected AuthHelper $authHelper;

    /**
     * @var BackendUserRepository
     */
    protected BackendUserRepository $backendUserRepository;

    /**
     * @var JsonRPCClientHelper
     */
    public JsonRPCClientHelper $SN;

    /**
     * @param AuthHelper $authHelper
     */
    public function injectAuthHelper(AuthHelper $authHelper): void
    {
        $this->authHelper = $authHelper;
    }

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

        $this->SN = GeneralUtility::makeInstance(JsonRPCClientHelper::class, $api_url);
    }

    /**
     * @param mixed $ids
     * @param mixed $query
     *
     * @return array
     */
    protected function loadDataFromScoutnet(?array $ids, mixed $query): array
    {
        return $this->SN->get_data_by_global_id($ids, $query);
    }
}
