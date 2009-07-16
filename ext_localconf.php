<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

t3lib_extMgm::addService($_EXTKEY,  'webservice' /* sv type */,  'tx_shscoutnetwebservice_sv1' /* sv key */,
		array(

			'title' => 'SN',
			'description' => 'Scoutnet webserver',

			'subtype' => '',

			'available' => TRUE,
			'priority' => 50,
			'quality' => 50,

			'os' => '',
			'exec' => '',

			'classFile' => t3lib_extMgm::extPath($_EXTKEY).'sv1/class.tx_shscoutnetwebservice_sv1.php',
			'className' => 'tx_shscoutnetwebservice_sv1',
		)
	);
?>