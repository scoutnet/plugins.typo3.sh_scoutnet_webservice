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

use ScoutNet\ShScoutnetWebservice\Helpers\AuthHelper;
use ScoutNet\ShScoutnetWebservice\Tests\Unit\Fixtures\NotSoRandomFixture;
use ScoutNet\ShScoutnetWebservice\Tests\Unit\Fixtures\NotSoTimeFixture;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

use DateTime;

class AuthHelperTest extends UnitTestCase {
    protected $authHelper = null;

    public function setup() {
        $this->authHelper = new AuthHelper();

        $cm = $this->prophesize(ConfigurationManager::class);

        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sh_scoutnet_webservice'] = serialize([
            'AES_key' => '12345678901234567890123456789012',
            'AES_iv' => '1234567890123456',
        ]);

        $this->authHelper->injectConfigurationManager($cm->reveal());
    }

    /**
     * @return array
     */
    public function dataProviderGenerateAuth() {
        return [
            'normal' => [
                '12345678901234567890123456789012',
                'test',
                '1TxlYBdYb-H0MwwsznIm47NsnDfKTDkh7Ah8btarmCk6FyeXvvGSXZli5mX-nl14EBHiwi0uMNVTtO7aVPbkPMP4uy-rteK4Uww5pF-gRYF4hl1X4Uw0AhFfr57HasNCDnMvLtzp2_Eo5M_0n37d6A-wP7zJ_xaIMR904tLCBQE~'


            ],
        ];
    }

    /**
     * @param $apiKey
     * @param $checkValue
     * @param $expectedResult
     *
     * @throws \Exception
     * @dataProvider dataProviderGenerateAuth
     */
    public function testGenerateAuth($apiKey, $checkValue, $expectedResult) {
        // no random
        GeneralUtility::addInstance(Random::class, new NotSoRandomFixture());
        // no time
        GeneralUtility::addInstance(DateTime::class, new NotSoTimeFixture());

        $ret = $this->authHelper->generateAuth($apiKey, $checkValue);

        $this->assertEquals($expectedResult, $ret);
    }

    public function testGetApiKeyFromData() {

    }
}
