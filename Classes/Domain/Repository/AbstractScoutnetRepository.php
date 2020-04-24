<?php
namespace ScoutNet\ShScoutnetWebservice\Domain\Repository;

use ScoutNet\ShScoutnetWebservice\Helpers\JsonRPCClientHelper;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\Inject;

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
 * @package	TYPO3
 * @subpackage	tx_shscoutnetwebservice
 *

 */
class AbstractScoutnetRepository {
	/**
	 * @var \ScoutNet\ShScoutnetWebservice\Helpers\AuthHelper
	 * @Inject
	 */
	protected $authHelper = null;

	/**
	 * @var \ScoutNet\ShScoutnetWebservice\Domain\Repository\BackendUserRepository
	 * @Inject
	 */
	protected $backendUserRepository = null;

	/**
	 * @var \ScoutNet\ShScoutnetWebservice\Helpers\JsonRPCClientHelper
	 */
	var $SN = null;

    /**
     */
	public function initializeObject(){
        $extConfig = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('sh_scoutnet_webservice');
		$api_url = isset($extConfig['scoutnetJsonAPIUrl'])?$extConfig['ScoutnetJsonAPIUrl']:'localhost';
		$this->SN = GeneralUtility::makeInstance(JsonRPCClientHelper::class, $api_url);
	}

	/**
	 * @param mixed $ids
	 * @param mixed $query
	 *
	 * @return mixed
     */
    protected function loadDataFromScoutnet($ids, $query){
		$res = $this->SN->get_data_by_global_id($ids, $query);

		return $res;
	}
}

