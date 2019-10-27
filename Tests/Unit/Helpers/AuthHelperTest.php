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
            'ScoutnetLoginPage' => 'https://www.scoutnet.de/auth',
            'ScoutnetProviderName' => 'unitTest',
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
                // base64(aes_256_cbc('4444444444444444' . json_encode(['md5'=>md5('test'),'sha1'=>sha1('test'),'time'=>1234])))
            ],
            'no Api Key' => [
                '',
                '',
                '',
                [1572194190],
            ]
        ];
    }

    /**
     * @param $apiKey
     * @param $checkValue
     * @param $expectedResult
     * @param $expectedExceptions
     *
     * @throws \Exception
     * @dataProvider dataProviderGenerateAuth
     */
    public function testGenerateAuth($apiKey, $checkValue, $expectedResult, $expectedExceptions = []) {
        if ($expectedExceptions and count($expectedExceptions) > 0) {
            foreach($expectedExceptions as $expExc) {
                $this->expectExceptionCode($expExc);
            }
        } else {
            // no random
            $random = $this->prophesize(Random::class);
            $random->generateRandomBytes(16)->willReturn('4444444444444444');
            GeneralUtility::addInstance(Random::class, $random->reveal());

            // no time
            $time = $this->prophesize(DateTime::class);
            $time->getTimestamp()->willReturn(1234);
            GeneralUtility::addInstance(DateTime::class, $time->reveal());
        }

        $ret = $this->authHelper->generateAuth($apiKey, $checkValue);

        $this->assertEquals($expectedResult, $ret);
    }

    public function dataProviderGetApiKeyFromData() {
        return [
            'empty auth' => [
                '',
                [1572191918],
            ],
            'broken md5' => [
                '1TxlYBdYb-H0MwwsznIm49YD0B5NSjIHiHEQ3d5GMCj9bzTJFWGEWFS2DoZJY9-eZOQBH3xb_2z5WL_R0QXr3UgAYBHPKmtGweeJ322baVGg1zpNbHGFbRNKcqQsWmDBXqfzDe7UjwJWQQFxqtQe1EPRUgMSqu642kf82-zfXWaKSaNoYMzs6vDAEr92qjry',
                [1572192280],
            ],
            'broken sha1' => [
                '1TxlYBdYb-H0MwwsznIm49YD0B5NSjIHiHEQ3d5GMCj9bzTJFWGEWFS2DoZJY9-eZOQBH3xb_2z5WL_R0QXr3UgAYBHPKmtGweeJ322baVEY1clhmSc25hhkRlXP9n8o6Y2Jt76wnT7ni0Q9pElTAasw4UmbJyhucfohNvxhfjY~',
                [1572192281],
            ],
            'too old' => [
                // current time is always older than timestamp 1234
                '1TxlYBdYb-H0MwwsznIm49YD0B5NSjIHiHEQ3d5GMCj9bzTJFWGEWFS2DoZJY9-eZOQBH3xb_2z5WL_R0QXr3UgAYBHPKmtGweeJ322baVGg1zpNbHGFbRNKcqQsWmDBXqfzDe7UjwJWQQFxqtQe1EPRUgMSqu642kf82-zfXWZBaqw85SGz2BGL-fs1nzsTfkKbHRR-mpoaRdExA0Awurh1LHCVH2ROGxKBmkzPetI~',
                [1572192282],
            ],
            'wrong site' => [
                '1TxlYBdYb-H0MwwsznIm49YD0B5NSjIHiHEQ3d5GMChAozkulOICYVK4nwow8joAAXD7cuFAU06XSNLxHVDD1Ao6Z98MxJWqBD0IxKbwkVtZgfftjTeK4i5NHVFwqUfYwEFqXJj7QVIUdWTCEwbZzNjTXIHI1woW-FgDhBxE-quit0C4yxRFj6YE-R-pUBmetgsdlrbwc3S9ZdTwz6PGI34aV6snflbd81tuovwTUBc~',
                [1572192283],
                false,
                true, // fix time
            ],
            'correct_auth' => [
                '1TxlYBdYb-H0MwwsznIm49YD0B5NSjIHiHEQ3d5GMCj9bzTJFWGEWFS2DoZJY9-eZOQBH3xb_2z5WL_R0QXr3UgAYBHPKmtGweeJ322baVGg1zpNbHGFbRNKcqQsWmDBXqfzDe7UjwJWQQFxqtQe1EPRUgMSqu642kf82-zfXWZBaqw85SGz2BGL-fs1nzsTfkKbHRR-mpoaRdExA0Awurh1LHCVH2ROGxKBmkzPetI~',
                null,
                [
                    'your_domain' => 'unitTest',
                    'user' => 'unitTest',
                    'time' => 1234,
                ],
                true, // fix time
            ]
        ];
    }

    /**
     * @param $data
     * @param $expectedExceptions
     * @param $expectedReturn
     *
     * @dataProvider dataProviderGetApiKeyFromData
     * @throws \ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetExceptionMissingConfVar
     */
    public function testGetApiKeyFromData($data, $expectedExceptions, $expectedReturn = false, $fix_time = false) {
        if ($fix_time) {
            // no time
            $time = $this->prophesize(DateTime::class);
            $time->getTimestamp()->willReturn(3600); // auth is 1234, timeout is 3600, so this makes time always valid
            GeneralUtility::addInstance(DateTime::class, $time->reveal());
        }


        $exp = false;
        if ($expectedExceptions and count($expectedExceptions) > 0) {
            foreach($expectedExceptions as $expExc) {
                $this->expectExceptionCode($expExc);
            }
            $exp = true;
        }

        $ret = $this->authHelper->getApiKeyFromData($data);

        if (!$exp) {
            $this->assertEquals($expectedReturn, $ret);
        }
    }
}
