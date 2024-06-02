<?php
/**
 * Copyright (c) 2024-2024 Stefan (Mütze) Horst
 *
 * I don't have the time to read through all the licences to find out
 * what they exactly say. But it's simple. It's free for non-commercial
 * projects, but as soon as you make money with it, I want my share :-)
 * (License: Free for non-commercial use)
 *
 * Authors: Stefan (Mütze) Horst <muetze@scoutnet.de>
 */

namespace ScoutNet\ShScoutnetWebservice\Tests\Unit\Domain\Model;

use ScoutNet\ShScoutnetWebservice\Domain\Model\BackendUser;
use ScoutNet\TestingTools\Domain\Model\AnnotationTestTrait;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class BackendUserTest extends UnitTestCase
{
    use AnnotationTestTrait;

    /**
     * @var BackendUser
     */
    protected BackendUser $subject;

    /**
     * @var string
     */
    protected string $testedClass = BackendUser::class;
    protected array $map_classes = [];

    protected string $overriddenTCAFile = 'vendor/typo3/cms-core/Configuration/TCA/be_users.php';

    protected function setUp(): void
    {
        $this->subject = new BackendUser();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    protected function getDocComment($str, $tag = ''): string
    {
        if (empty($tag)) {
            return $str;
        }

        $matches = [];
        preg_match('/' . $tag . ' (.*)(\\r\\n|\\r|\\n)/U', $str, $matches);

        if (isset($matches[1])) {
            return trim($matches[1]);
        }

        return '';
    }
}
