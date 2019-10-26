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


namespace ScoutNet\ShScoutnetWebservice\Tests\Unit\Fixtures;


use TYPO3\CMS\Core\Crypto\Random;

class NotSoRandomFixture extends Random
{
    public function generateRandomBytes(int $length): string
    {
        $random = str_repeat("4", $length);
        return $random;
    }

}