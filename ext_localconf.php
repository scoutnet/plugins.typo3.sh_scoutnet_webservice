<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}


// add converter for our objects
ExtensionUtility::registerTypeConverter('ScoutNet\ShScoutnetWebservice\Property\TypeConverter\EventConverter');
ExtensionUtility::registerTypeConverter('ScoutNet\ShScoutnetWebservice\Property\TypeConverter\StructureConverter');

