<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package CronInfo
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'BugBuster',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Modules
	'BugBuster\CronInfo\CronRegistry' => 'system/modules/cron_info/modules/CronRegistry.php',
    
    // Classes
    'BugBuster\CronInfo\Cron_Encryption'   => 'system/modules/cron_info/classes/Cron_Encryption.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_cron_registry_be' => 'system/modules/cron_info/templates',
	'mod_cron_start_be'    => 'system/modules/cron_info/templates',
));
