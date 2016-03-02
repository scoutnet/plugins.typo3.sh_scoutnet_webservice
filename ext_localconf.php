<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}


// add converter for our object
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('ScoutNet\ShScoutnetWebservice\Property\TypeConverter\EventConverter');
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('ScoutNet\ShScoutnetWebservice\Property\TypeConverter\StructureConverter');

/*
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService($_EXTKEY,  'webservice' ,  'tx_shscoutnetwebservice_sv1',
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
*/
