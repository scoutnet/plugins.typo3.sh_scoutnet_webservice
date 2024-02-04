<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "sh_scoutnet_webservice"
 *
 ***************************************************************/

/** @var string $_EXTKEY */
$EM_CONF[$_EXTKEY] = [
	'title' => 'Official Scoutnet Webservice class',
	'description' => 'This class is needed to communicate with the scoutnet server.',
	'category' => 'services',
	'author' => 'Stefan "Mütze" Horst',
	'author_email' => 'muetze@scoutnet.de',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'clearCacheOnLoad' => 1,
	'version' => '6.0.0',
	'constraints' => [
		'depends' => [
			'typo3' => '12.4.0-12.4.99',
		],
		'conflicts' => [],
		'suggests' => [],
	],
];
