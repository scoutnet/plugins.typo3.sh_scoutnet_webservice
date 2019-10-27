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

use ScoutNet\ShScoutnetWebservice\Helpers\JsonRPCClientHelper;
use PHPUnit\Framework\TestCase;

class JsonRPCClientHelperTest extends TestCase {
    public function dataProviderConnect(){
        return [
            'unable to connect' => [
                'no_connect',
                [1572202683],
            ],
            'error' => [
                'error',
                [1572203301],
            ],
            'correct' => [
                'correct',
                [],
                'correct',
            ],
        ];
    }

    /**
     * @dataProvider dataProviderConnect
     *
     * @param             $url
     * @param             $expectedExceptions
     * @param bool|string $expReturn
     */
    public function testConnect($url, $expectedExceptions, $expReturn = false) {
        if ($expectedExceptions and count($expectedExceptions) > 0) {
            foreach($expectedExceptions as $expExc) {
                $this->expectExceptionCode($expExc);
            }
        }

        $sn = new JsonRPCClientHelper($url);
        $ret = $sn->test();

        if ($expReturn) {
            $this->assertEquals($expReturn, $ret);
        }
    }
}

// hooks for stream
namespace ScoutNet\ShScoutnetWebservice\Helpers;

function stream_context_create($opts) {
    return [
        'opts' => $opts,
    ];
}

function fopen ($filename, $mode, $use_include_path = null, $context = null) {
    $param = json_decode($context['opts']['http']['content'], true);

    if ($filename == 'no_connect') return false;

    // return filepointer
    return [
        'mode' => $mode,
        'use_include_path' => $use_include_path,
        'id' => 1,
        'context' => $context,
        'filename' => $filename,
        'param' => $param,
        'finished' => false,
    ];
}

function fgets(&$fp) {
    $id = $fp['param']['id'];
    if (!$fp['finished']) {
        $fp['finished'] = true;

        if ($fp['filename'] == 'error') {
            $ret = [
                'id' => $id,
                'error' => 'error',
            ];
        } elseif ($fp['filename'] == 'correct') {
            $ret = [
                'id' => $id,
                'result' => 'correct',
            ];
        } else {
            $ret = [
                'id' => $id,
                'error' => 'unknown request',
            ];
        }

        return json_encode($ret);
    }

    return false;
}
