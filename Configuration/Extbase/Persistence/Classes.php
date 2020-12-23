<?php
/**
 ************************************************************************
 * Copyright (c) 2005-2020 Stefan (Muetze) Horst                        *
 ************************************************************************
 * I don't have the time to read through all the licences to find out   *
 * what the exactly say. But it's simple. It's free for non commercial  *
 * projects, but as soon as you make money with it, i want my share :-) *
 * (License : Free for non-commercial use)                              *
 ************************************************************************
 * Authors: Stefan (Muetze) Horst <muetze@DPSG-Liblar.de>               *
 ************************************************************************
 */


use ScoutNet\ShScoutnetWebservice\Domain\Model\BackendUser;

return [
    BackendUser::class => [
        'tableName' => 'be_users',
    ],
];

//config.tx_extbase.persistence.classes{
//    TYPO3\CMS\Extbase\Domain\Model\BackendUser {
//        subclasses {
//            BackendUser = ScoutNet\ShScoutnetWebservice\Domain\Model\BackendUser
//		}
//	}
//	ScoutNet\ShScoutnetWebservice\Domain\Model\BackendUser {
//        mapping {
//            tableName = be_users
//		}
//	}
//}
