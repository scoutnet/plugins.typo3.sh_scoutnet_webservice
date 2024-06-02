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

namespace ScoutNet\ShScoutnetWebservice\Helpers;

use ScoutNet\Api\Exceptions\ScoutNetExceptionMissingConfVar;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * Service "SN" for the "sh_scoutnet_webservice" extension.
 *
 * @author	Stefan Horst <muetze@scoutnet.de>
 */
class ScoutNetConnectHelper
{
    /**
     * @param string $returnUrl
     * @param bool   $requestApiKey
     *
     * @return string
     * @throws ScoutNetExceptionMissingConfVar
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public static function getScoutNetConnectLoginButton(string $returnUrl = '', bool $requestApiKey = false): string
    {
        // TODO: use a template here!!
        //		$lang = $GLOBALS['LANG']->lang;
        //        $lang = $TSFE->sys_language_isocode;
        // TODO: make this work again, deprecation
        $lang = '';

        /** @var ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);

        // load configuration for this Extension
        $extConfig = $extensionConfiguration->get('sh_scoutnet_webservice');

        if ($lang === 'default') {
            $lang = 'en';
        }

        self::_checkConfigValues($extConfig);
        $button = '<form action="' . $extConfig['ScoutnetLoginPage'] . '" id="scoutnetLogin" method="post" target="_self">';

        $button .= $returnUrl == '' ? '' : '<input type="hidden" name="redirect_url" value="' . $returnUrl . '" />';
        $button .= '<input type="hidden" name="lang" value="' . $lang . '"/>';
        $button .= '<input type="hidden" name="provider" value="' . $extConfig['ScoutnetProviderName'] . '" />';
        $button .= $requestApiKey ? '<input type="hidden" name="createApiKey" value="1" />' : '';

        $button .= '<a href="#" onclick="document.getElementById(\'scoutnetLogin\').submit(); return false;">';

        $button .= '<img src="' . PathUtility::getAbsoluteWebPath(ExtensionManagementUtility::extPath('sh_scoutnet_webservice') . 'Resources/Public/Images/scoutnetConnect.png') . '" title="scoutnet" alt="scoutnet"/>';
        $button .= '</a>';

        $button .= '</form>';

        return $button;
    }

    /**
     * Checks if $config contains all relevant parameters, otherwise throw Exception
     *
     * @param array $config
     *
     * @throws ScoutNetExceptionMissingConfVar
     */
    private static function _checkConfigValues(array $config): void
    {
        $configVars = ['AES_key', 'AES_iv', 'ScoutnetLoginPage', 'ScoutnetProviderName'];

        foreach ($configVars as $configVar) {
            if (trim($config[$configVar] ?? '') === '') {
                throw new ScoutNetExceptionMissingConfVar($configVar);
            }
        }
    }
}
