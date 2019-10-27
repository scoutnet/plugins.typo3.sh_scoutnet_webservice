<?php
/**
 ************************************************************************
 * Copyright (c) 2005-2019 Stefan (Muetze) Horst                        *
 ************************************************************************
 * I don't have the time to read through all the licences to find out   *
 * what the exactly say. But it's simple. It's free for non commercial  *
 * projects, but as soon as you make money with it, i want my share :-) *
 * (License : Free for non-commercial use)                              *
 ************************************************************************
 * Authors: Stefan (Muetze) Horst <muetze@DPSG-Liblar.de>               *
 ************************************************************************
 */

namespace ScoutNet\ShScoutnetWebservice\Tests\Unit\Helpers;

use ScoutNet\ShScoutnetWebservice\Helpers\ScoutNetConnectHelper;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

class ScoutNetConnectHelperTest extends TestCase {
    protected $scoutNetConnectHelper = null;

    public function setup() {
        $this->scoutNetConnectHelper = new ScoutNetConnectHelper();

        $cm = $this->prophesize(ConfigurationManager::class);

        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sh_scoutnet_webservice'] = serialize([
            'AES_key' => '12345678901234567890123456789012',
            'AES_iv' => '1234567890123456',
            'ScoutnetLoginPage' => 'https://www.scoutnet.de/auth',
            'ScoutnetProviderName' => 'unitTest',
        ]);

        $this->scoutNetConnectHelper->injectConfigurationManager($cm->reveal());
    }

    public function dataProviderGetScoutNetConnectLoginButton() {
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
            ]
        ];
    }

    /**
     * @param $returnUrl
     * @param $requestApiKey
     * @param $exp
     *
     * @dataProvider dataProviderGetScoutNetConnectLoginButton
     * @throws \ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetExceptionMissingConfVar
     */
    public function testGetScoutNetConnectLoginButton($returnUrl, $requestApiKey, $exp) {
        $ret = $this->scoutNetConnectHelper->getScoutNetConnectLoginButton($returnUrl, $requestApiKey);
        $this->assertEquals($exp, $ret);
    }
}
