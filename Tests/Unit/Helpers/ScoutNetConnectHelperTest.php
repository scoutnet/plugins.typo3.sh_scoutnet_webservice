<?php
/**
 * Copyright (c) 2005-2024 Stefan (Mütze) Horst
 *
 * I don't have the time to read through all the licences to find out
 * what they exactly say. But it's simple. It's free for non-commercial
 * projects, but as soon as you make money with it, I want my share :-)
 * (License: Free for non-commercial use)
 *
 * Authors: Stefan (Mütze) Horst <muetze@scoutnet.de>
 */

namespace ScoutNet\ShScoutnetWebservice\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use ScoutNet\Api\Exceptions\ScoutNetExceptionMissingConfVar;
use ScoutNet\ShScoutnetWebservice\Helpers\ScoutNetConnectHelper;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Package\UnitTestPackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ScoutNetConnectHelperTest extends TestCase
{
    use ProphecyTrait;

    public function setup(): void
    {
        parent::setUp();
        $em = $this->prophesize(ExtensionConfiguration::class);

        $em->get('sh_scoutnet_webservice')->willReturn(
            [
                'AES_key' => '12345678901234567890123456789012',
                'AES_iv' => '1234567890123456',
                'ScoutnetLoginPage' => 'https://www.scoutnet.de/auth',
                'ScoutnetProviderName' => 'unitTest',
            ]
        );

        GeneralUtility::addInstance(ExtensionConfiguration::class, $em->reveal());

        $package = $this->prophesize(Package::class);
        $package->getPackagePath()->willReturn('../../../../typo3conf/ext/sh_scoutnet_webservice/');

        $pm = $this->prophesize(UnitTestPackageManager::class);
        $pm->isPackageActive('sh_scoutnet_webservice')->willReturn(true);
        $pm->getPackage('sh_scoutnet_webservice')->willReturn($package->reveal());

        ExtensionManagementUtility::setPackageManager($pm->reveal());
    }

    public static function dataProviderGetScoutNetConnectLoginButton(): array
    {
        return [
            'no url and no apikey' => [
                '',
                false,
                '<form action="https://www.scoutnet.de/auth" id="scoutnetLogin" method="post" target="_self"><input type="hidden" name="lang" value=""/><input type="hidden" name="provider" value="unitTest" /><a href="#" onclick="document.getElementById(\'scoutnetLogin\').submit(); return false;"><img src="typo3conf/ext/sh_scoutnet_webservice/Resources/Public/Images/scoutnetConnect.png" title="scoutnet" alt="scoutnet"/></a></form>',
            ],
            'url and no apikey' => [
                'www.unitTest.baa',
                false,
                '<form action="https://www.scoutnet.de/auth" id="scoutnetLogin" method="post" target="_self"><input type="hidden" name="redirect_url" value="www.unitTest.baa" /><input type="hidden" name="lang" value=""/><input type="hidden" name="provider" value="unitTest" /><a href="#" onclick="document.getElementById(\'scoutnetLogin\').submit(); return false;"><img src="typo3conf/ext/sh_scoutnet_webservice/Resources/Public/Images/scoutnetConnect.png" title="scoutnet" alt="scoutnet"/></a></form>',
            ],
            'url and apikey' => [
                'www.unitTest.baa',
                true,
                '<form action="https://www.scoutnet.de/auth" id="scoutnetLogin" method="post" target="_self"><input type="hidden" name="redirect_url" value="www.unitTest.baa" /><input type="hidden" name="lang" value=""/><input type="hidden" name="provider" value="unitTest" /><input type="hidden" name="createApiKey" value="1" /><a href="#" onclick="document.getElementById(\'scoutnetLogin\').submit(); return false;"><img src="typo3conf/ext/sh_scoutnet_webservice/Resources/Public/Images/scoutnetConnect.png" title="scoutnet" alt="scoutnet"/></a></form>',
            ],
            'no url and apikey' => [
                '',
                true,
                '<form action="https://www.scoutnet.de/auth" id="scoutnetLogin" method="post" target="_self"><input type="hidden" name="lang" value=""/><input type="hidden" name="provider" value="unitTest" /><input type="hidden" name="createApiKey" value="1" /><a href="#" onclick="document.getElementById(\'scoutnetLogin\').submit(); return false;"><img src="typo3conf/ext/sh_scoutnet_webservice/Resources/Public/Images/scoutnetConnect.png" title="scoutnet" alt="scoutnet"/></a></form>',
            ],
        ];
    }

    /**
     * @param string $returnUrl
     * @param bool $requestApiKey
     * @param string $exp
     *
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws ScoutNetExceptionMissingConfVar
     * @dataProvider dataProviderGetScoutNetConnectLoginButton
     */
    public function testGetScoutNetConnectLoginButton(string $returnUrl, bool $requestApiKey, string $exp): void
    {
        $ret = ScoutNetConnectHelper::getScoutNetConnectLoginButton($returnUrl, $requestApiKey);
        self::assertEquals($exp, $ret);
    }
}
