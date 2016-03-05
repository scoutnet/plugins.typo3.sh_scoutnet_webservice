<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// be_user fileds
$tempColumns = Array (
	'tx_shscoutnet_username' => Array (
		'exclude' => 1,
		"label" => "LLL:EXT:sh_scoutnet_webservice/Resources/Private/Language/locallang_csh_be_users.xlf:be_users.scoutnet_username",
		'config' => Array (
			'type' => 'input',
			'size' => '255',
		)
	),
	'tx_shscoutnet_apikey' => Array (
		'exclude' => 1,
		"label" => "LLL:EXT:sh_scoutnet_webservice/Resources/Private/Language/locallang_csh_be_users.xlf:be_users.scoutnet_apikey",
		'config' => Array (
			'type' => 'input',
			'size' => '255',
		)
	),
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('be_users',$tempColumns,1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('be_users','--div--;LLL:EXT:sh_scoutnet_webservice/Resources/Private/Language/locallang_csh_be_users.xlf:be_users.scoutnet_tab, tx_shscoutnet_username, tx_shscoutnet_apikey');
