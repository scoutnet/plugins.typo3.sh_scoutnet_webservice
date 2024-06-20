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

use Prophecy\Prophet;
use ScoutNet\Api\Helpers\JsonRPCClientHelper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class JsonRPCClientHelperTest extends UnitTestCase
{
    private Prophet $prophet;

    public function setup(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->prophet = new Prophet();
    }

    protected function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public static function dataProviderConnect(): array
    {
        return [
            'unable to connect' => [
                'no_connect',
                [1492679926],
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
     * @param string $url
     * @param array|null $expectedExceptions
     * @param bool|string $expReturn
     */
    public function testConnect(string $url, ?array $expectedExceptions, bool|string $expReturn = false): void
    {
        if ($expectedExceptions && count($expectedExceptions) > 0) {
            foreach ($expectedExceptions as $expExc) {
                $this->expectExceptionCode($expExc);
            }
        }

        $sn = new JsonRPCClientHelper($url);
        $ret = $sn->test();

        if ($expReturn) {
            self::assertEquals($expReturn, $ret);
        }
    }
}

// hooks for stream

namespace ScoutNet\Api\Helpers;

function stream_context_create($opts): array
{
    return [
        'opts' => $opts,
    ];
}

function fopen($filename, $mode, $use_include_path = null, $context = null): bool|array
{
    $param = json_decode($context['opts']['http']['content'], true);

    if ($filename === 'no_connect') {
        return false;
    }

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

function fgets(&$fp): bool|string
{
    $id = $fp['param']['id'];
    if (!$fp['finished']) {
        $fp['finished'] = true;

        if ($fp['filename'] === 'error') {
            $ret = [
                'id' => $id,
                'error' => 'error',
            ];
        } elseif ($fp['filename'] === 'correct') {
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
