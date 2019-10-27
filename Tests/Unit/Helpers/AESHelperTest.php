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

use ScoutNet\ShScoutnetWebservice\Helpers\AESHelper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class AESHelperTest extends UnitTestCase {
    /**
     * @throws \ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetException
     */
    public function testEncryptDecrypt() {
        $key = [
            'key' => '12345678901234567890123456789012',
            'iv' => '1234567890123456',
            'mode' => 'CBC',
        ];

        $pt = 'testtest';

        $aes = new AESHelper($key['key'], $key['mode'], $key['iv']);

        $crypt = $aes->encrypt($pt);

        $aes = new AESHelper($key['key'], $key['mode'], $key['iv']);

        $this->assertEquals($aes->decrypt($crypt), $pt);
    }

    public function dataProviderCorrectKeyLength() {
        return [
            'aes128' => [
                '1234567890123456',
            ],
            'aes192' => [
                '123456789012345678901234',
            ],
            'aes256' => [
                '12345678901234567890123456789012',
            ],
            'empty' => [
                '',
                [1572194460],
            ],
            'wrong length' => [
                '123',
                [1572194460],
            ],
        ];
    }

    /**
     * @param $key
     * @param $expExceptions
     *
     * @throws \ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetException
     * @dataProvider dataProviderCorrectKeyLength
     */
    public function testCorrectKeyLength($key, $expExceptions = []) {
        if ($expExceptions and count($expExceptions) > 0) {
            foreach ($expExceptions as $expExc) {
                $this->expectExceptionCode($expExc);
            }
        }
        new AESHelper($key);
    }


    public function dataProviderEncrypt() {
        return [
            'short block' => [ // should be padded with 0x00
                [
                    'key' => '12345678901234567890123456789012',
                    'iv' => '1234567890123456',
                    'mode' => 'CBC',
                ],
                'testtest',
//                'FCbM1hpe5vAbYvq3LQv5yg==', // pkcs#7
                'ruIH7F3mHozAP9aU5cZD1A==', // zero padded
            ],
            'no padding' => [
                [
                    'key' => '12345678901234567890123456789012',
                    'iv' => '1234567890123456',
                    'mode' => 'CBC',
                ],
                'testtesttesttest',
                'O4p8xIJFm5/EHinKjrB/Uw==', // no padding
            ],
            'more than one block' => [
                [
                    'key' => '12345678901234567890123456789012',
                    'iv' => '1234567890123456',
                    'mode' => 'CBC',
                ],
                'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest',
//                'O4p8xIJFm5/EHinKjrB/U8ySfWjP9s0J3fTbi3/0LcXmNVWbRvcvaCin63IuFcOE9Cg6nQylpSabUfW9m/WO+8r87PO7U2P/JcqN8lSzoErLoVmPjYF3YM0AgAGTCk6ns5JWKJ/gvJC3FhmA8Tmw8OpbXuKosGDU2kZRrzHKMkk=', // pkcs#7
                'O4p8xIJFm5/EHinKjrB/U8ySfWjP9s0J3fTbi3/0LcXmNVWbRvcvaCin63IuFcOE9Cg6nQylpSabUfW9m/WO+8r87PO7U2P/JcqN8lSzoErLoVmPjYF3YM0AgAGTCk6ns5JWKJ/gvJC3FhmA8Tmw8LYp1olEN+pE8rhBu5yG328=', // zero padding
            ],
        ];
    }


    /**
     * @dataProvider dataProviderEncrypt
     *
     * @param $key
     * @param $plaintext
     * @param $cyphertext
     *
     * @throws \ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetException
     */
    public function testEncrypt($key, $plaintext, $cyphertext)
    {
        $aes = new AESHelper($key['key'], $key['mode'], $key['iv']);
        $crypt = base64_encode($aes->encrypt($plaintext));

        $this->assertEquals($cyphertext, $crypt);
    }


    public function dataProviderDecrypt() {
        return [
            'less than one block' => [
                [
                    'key' => '12345678901234567890123456789012',
                    'iv' => '1234567890123456',
                    'mode' => 'CBC',
                ],
//                'FCbM1hpe5vAbYvq3LQv5yg==', // pkcs#7
                'ruIH7F3mHozAP9aU5cZD1A==', // zero padded
                'testtest',
            ],
            'exact one block' => [
                [
                    'key' => '12345678901234567890123456789012',
                    'iv' => '1234567890123456',
                    'mode' => 'CBC',
                ],
                'O4p8xIJFm5/EHinKjrB/Uw==', // no padding
                'testtesttesttest',
            ],
            'more than one block' => [
                [
                    'key' => '12345678901234567890123456789012',
                    'iv' => '1234567890123456',
                    'mode' => 'CBC',
                ],
                //'O4p8xIJFm5/EHinKjrB/U8ySfWjP9s0J3fTbi3/0LcXmNVWbRvcvaCin63IuFcOE9Cg6nQylpSabUfW9m/WO+8r87PO7U2P/JcqN8lSzoErLoVmPjYF3YM0AgAGTCk6ns5JWKJ/gvJC3FhmA8Tmw8OpbXuKosGDU2kZRrzHKMkk=', // pkcs#7 padding
                'O4p8xIJFm5/EHinKjrB/U8ySfWjP9s0J3fTbi3/0LcXmNVWbRvcvaCin63IuFcOE9Cg6nQylpSabUfW9m/WO+8r87PO7U2P/JcqN8lSzoErLoVmPjYF3YM0AgAGTCk6ns5JWKJ/gvJC3FhmA8Tmw8LYp1olEN+pE8rhBu5yG328=', // zero padding
                'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest',
            ],
//            'broken length' => [
//                [
//                    'key' => '12345678901234567890123456789012',
//                    'iv' => '1234567890123456',
//                    'mode' => 'CBC',
//                ],
//                'O4p8xIJFm5/EHinKjrB/U8ySfWjP9s0J3fTbi3/0LcXmNVWbRvcvaCin63IuFcOE9Cg6nQylpSabUfW9m/WO+8r87PO7U2P/JcqN8lSzoErLoVmPjYF3YM0AgAGTCk6ns5JWKJ/gvJC3FhmA8Tmw8OpbXuKosGDU2kZRrzHKMk',
//                false,
//            ],
        ];
    }


    /**
     * @dataProvider dataProviderDecrypt
     *
     * @param $key
     * @param $cyphertext
     * @param $plaintext
     *
     * @throws \ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetException
     */
    public function testDecrypt($key, $cyphertext, $plaintext)
    {
        $aes = new AESHelper($key['key'], $key['mode'], $key['iv']);
        $plain = $aes->decrypt(base64_decode($cyphertext));

        $this->assertEquals($plaintext, $plain);
    }
}
