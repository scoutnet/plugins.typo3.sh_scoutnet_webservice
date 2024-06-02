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

namespace ScoutNet\ShScoutnetWebservice\Tests\Unit\Fixtures;

use ScoutNet\Api\Helpers\JsonRPCClientHelper;

// we need this fixture, since prophecy cannot set up __call methods
class JsonRPCClientHelperFixture extends JsonRPCClientHelper
{
    public function get_data_by_global_id(array|int|null $globalId, $filter): array
    {
        return [];
    }
}
