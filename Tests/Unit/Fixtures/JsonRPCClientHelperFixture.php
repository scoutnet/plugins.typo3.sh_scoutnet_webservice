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

use ScoutNet\ShScoutnetWebservice\Helpers\JsonRPCClientHelper;
use TYPO3\CMS\Core\Exception;

// we need this fixture, since prophecy cannot stup __call methods
class JsonRPCClientHelperFixture extends JsonRPCClientHelper
{
    public function get_data_by_global_id($globalid, $filter)
    {
        throw new Exception('Function not moked!!');
    }
}
