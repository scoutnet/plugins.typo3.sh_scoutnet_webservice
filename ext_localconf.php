<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}


// add converter for our objects
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('ScoutNet\ShScoutnetWebservice\Property\TypeConverter\EventConverter');
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('ScoutNet\ShScoutnetWebservice\Property\TypeConverter\StructureConverter');

