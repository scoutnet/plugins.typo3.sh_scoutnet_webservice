<?php
/**
 * Copyright (c) 2016-2024 Stefan (Mütze) Horst
 *
 * I don't have the time to read through all the licences to find out
 * what they exactly say. But it's simple. It's free for non-commercial
 * projects, but as soon as you make money with it, I want my share :-)
 * (License: Free for non-commercial use)
 *
 * Authors: Stefan (Mütze) Horst <muetze@scoutnet.de>
 */

namespace ScoutNet\ShScoutnetWebservice\Domain\Model;

/**
 * Model for backend user
 */
class BackendUser
{
    /**
     * @var string
     */
    protected string $scoutnetUsername = '';

    /**
     * @var string
     */
    protected string $scoutnetApikey = '';

    /**
     * @return string
     */
    public function getScoutnetUsername(): string
    {
        return $this->scoutnetUsername;
    }

    /**
     * @param string $scoutnetUsername
     */
    public function setScoutnetUsername(string $scoutnetUsername): void
    {
        $this->scoutnetUsername = $scoutnetUsername;
    }

    /**
     * @return string
     */
    public function getScoutnetApikey(): string
    {
        return $this->scoutnetApikey;
    }

    /**
     * @param string $scoutnetApikey
     */
    public function setScoutnetApikey(string $scoutnetApikey): void
    {
        $this->scoutnetApikey = $scoutnetApikey;
    }
}
