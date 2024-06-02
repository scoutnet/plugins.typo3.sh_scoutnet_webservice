<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// be_user fileds
$tempColumns =  [
    'tx_shscoutnet_username' =>  [
        'exclude' => 1,
        'label' => 'LLL:EXT:sh_scoutnet_webservice/Resources/Private/Language/locallang_csh_be_users.xlf:be_users.scoutnet_username',
        'config' =>  [
            'type' => 'input',
            'size' => '255',
        ],
    ],
    'tx_shscoutnet_apikey' =>  [
        'exclude' => 1,
        'label' => 'LLL:EXT:sh_scoutnet_webservice/Resources/Private/Language/locallang_csh_be_users.xlf:be_users.scoutnet_apikey',
        'config' =>  [
            'type' => 'input',
            'size' => '255',
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns('be_users', $tempColumns);
ExtensionManagementUtility::addToAllTCAtypes('be_users', '--div--;LLL:EXT:sh_scoutnet_webservice/Resources/Private/Language/locallang_csh_be_users.xlf:be_users.scoutnet_tab, tx_shscoutnet_username, tx_shscoutnet_apikey');
