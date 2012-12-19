<?php
/* *********************************************************************
File: site_config.php
File description:
The base configurations for Liquid Recruitment. This file has the following configuration options:
 * REQUIRED
 * - Licence key settings
 * - Base directory settings
 * - Database settings
 * OPTIONAL:
 * - Table prefix settings
 * - Error reporting
 * - Time zone settings
********************************************************************* */

/* CONFIGURATION BASE OPTIONS - REQUIRED */

/**
 * Licence key settings
 */
define('LICENCE_KEY','1234-5678-90AB');


/**
 * Base directory settings
 */
define('BASE_HREF',"/liquid_source/");

/**
 * MySQL settings - You can get this info from your web host
 */
/** Whether to use MySQLi or base MySQL commands. **/
define('USE_MYSQLI',true);
/** Database hostname **/
define('DB_HOST',"localhost");
/** The name of the database for Loiquid Recruitment **/
define('DB_NAME',"liquid_source");
/** Database username **/
define('DB_USER',"root");
/** Database password **/
define('DB_PASSWORD',"root");



/* CONFIGURATION BASE OPTIONS - OPTIONAL */


/**
 * Table prefix settings.
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */ 
define('TABLE_PREFIX','ls_');

/**
 * Error reporting.
 */ 
/** Internal database recording of actions throughout the site. We recommend you leave this set to true **/
define('SITE_LOGGING',true);
/** Turn on / off PHP error warnings. During development we recommend you leave this set to true **/
define('PHP_ERROR_REPORTING',true);


/**
 * Time zone settings
 * Set this to the required Timezone for the website.
 * A list of supported types can be found here: http://php.net/manual/en/timezones.php
 */ 
date_default_timezone_set('Europe/London');




/* DO NOT EDIT BELOW THIS LINE */

/* This is set to recruitment. It defines which Liquid Source 'niche' site you are using. */
define('NICHE_SITE','');

global $settingup, $failedDB;
include(INCLUDE_WEB_ROOT . "scripts/core/database.php");
include(INCLUDE_WEB_ROOT . "scripts/core/dbconnex.php");
include(INCLUDE_WEB_ROOT . "scripts/core/extensions.php");
if(!defined('REDIRECT_ROOT_PATH')) define('REDIRECT_ROOT_PATH',"http://" . $_SERVER['HTTP_HOST'] . BASE_HREF);
if(!defined('INCLUDE_PLUGIN_ROOT')) define('INCLUDE_PLUGIN_ROOT',INCLUDE_WEB_ROOT . "scripts/extensions/plugins/");
if(!defined('INCLUDE_NICHE_ROOT')) define('INCLUDE_NICHE_ROOT',INCLUDE_WEB_ROOT . "scripts/extensions/niche/");
if(!defined('DB_SAFE_DATETIME')) define('DB_SAFE_DATETIME',date("Y-m-d H:i:s"));
if(!defined('DB_SAFE_DATE')) define('DB_SAFE_DATE',date("Y-m-d"));

$site_tables = array('alert_warnings','categories','category_link','links','log_shortcodes','media','members','member_profile','member_profile_master','meta_data','options','pages','posts','selectbox','site_log','site_options');
foreach($site_tables as $tbl) { if(!defined(TABLE_PREFIX . $tbl)) define('DB_TBL_' . strtoupper($tbl), TABLE_PREFIX . $tbl); }

if($settingup != "true") {
	global $dbconnexfailed;
	if($dbconnexfailed ) {
		header('Location: ' . REDIRECT_ROOT_PATH . 'setup/setup.php?error=dbconnection');
		die;
	}
	
	
	if(USE_MYSQLI) {
		global $db;
		mysqli_set_charset($db,'utf8');
	}
	else {
		mysql_set_charset('utf8');
	}
	
	$rs = mq("SELECT * FROM " . DB_TBL_SITE_OPTIONS);
	if (!$rs) {
		header('Location: ' . REDIRECT_ROOT_PATH . 'setup/setup.php');
		die;
	}
	
	
	
	$rs = mq("select * from " . DB_TBL_SITE_OPTIONS . " where so_type='define'");
	while($rw = mfa($rs)) {
		$arg = $rw['so_arg'];
		$val = $rw['so_val'];
		
		if($rw['so_field_type'] == 'bool') {
			if(strtolower($val) == "false") {
				$val = 0;
			} else {
				$val = 1;
			}
			
		}
		if(!defined($arg)) define($arg,$val);
	}
	
	if(!defined("ISO")) define("ISO","gb");
	if(!defined("LANG")) define("LANG","en");
	
	global $curPosts;
	$curPosts = NULL;
} else {
	$failedWriteable = false;
	if(!is__writable('../uploads/')) { $failedWriteable = true; }
	
}
?>