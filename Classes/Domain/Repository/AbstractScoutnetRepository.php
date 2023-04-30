<?php

namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

use ScoutNet\ShScoutnetWebservice\Helpers\AuthHelper;
use ScoutNet\ShScoutnetWebservice\Helpers\JsonRPCClientHelper;
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
     * @var \ScoutNet\ShScoutnetWebservice\Helpers\AuthHelper
     */
    protected $authHelper;

    /**
     * @var \ScoutNet\ShScoutnetWebservice\Domain\Repository\BackendUserRepository
     */
    protected $backendUserRepository;

    /**
     * @var \ScoutNet\ShScoutnetWebservice\Helpers\JsonRPCClientHelper
     */
    public $SN;

    /**
     * @param \ScoutNet\ShScoutnetWebservice\Helpers\AuthHelper $authHelper
     */
    public function injectAuthHelper(AuthHelper $authHelper)
    {
        $this->authHelper = $authHelper;
    }

    /**
     * @param \ScoutNet\ShScoutnetWebservice\Domain\Repository\BackendUserRepository $backendUserRepository
     */
    public function injectBackendUserRepository(BackendUserRepository $backendUserRepository)
    {
        $this->backendUserRepository = $backendUserRepository;
    }

    /**
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    public function initializeObject()
    {
        /** @var ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $extConfig = $extensionConfiguration->get('sh_scoutnet_webservice');

        $api_url = $extConfig['ScoutnetJsonAPIUrl']??'localhost';

        $this->SN = GeneralUtility::makeInstance(JsonRPCClientHelper::class, $api_url);
    }

    /**
     * @param mixed $ids
     * @param mixed $query
     *
     * @return array
     */
    protected function loadDataFromScoutnet(?array $ids, $query): array
    {
        return $this->SN->get_data_by_global_id($ids, $query);
    }
}
