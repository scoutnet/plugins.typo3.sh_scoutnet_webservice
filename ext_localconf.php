<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

/*
$_EXTCONF = unserialize($_EXTCONF);
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tx_shscoutnetwebservice']['AES_key']=$_EXTCONF['AES_key'];
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tx_shscoutnetwebservice']['AES_iv']=$_EXTCONF['AES_iv'];
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tx_shscoutnetwebservice']['ScoutnetLoginPage']=$_EXTCONF['ScoutnetLoginPage'];
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tx_shscoutnetwebservice']['ScoutnetProviderName']=$_EXTCONF['ScoutnetProviderName'];
 */

<<<EOF
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService($_EXTKEY,  'webservice' /* sv type */,  'tx_shscoutnetwebservice_sv1' /* sv key */,
		array(

			'title' => 'SN',
			'description' => 'Scoutnet webserver',

			'subtype' => '',

			'available' => TRUE,
			'priority' => 50,
			'quality' => 50,

			'os' => '',
			'exec' => '',

			'classFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'sv1/class.tx_shscoutnetwebservice_sv1.php',
			'className' => 'tx_shscoutnetwebservice_sv1',
		)
	);
EOF
?>
