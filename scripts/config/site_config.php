<?php
/* *********************************************************************
[PROJECT] by Liquid Source
http://www.liquid-source.com

File: site_config.php
File description: Sets out base variables for the site
Author: Nick Wilkins
********************************************************************* */

/* CONFIGURATION BASE OPTIONS */
define('BASE_HREF',"/liquid_source/");

define('USE_MYSQLI',true);
define('DB_HOST',"localhost");
define('DB_NAME',"liquid_source");
define('DB_USER',"root");
define('DB_PASSWORD',"root");

define('TABLE_PREFIX','ls_');

/* This is intentionally blank. It is used for niche site deployment, and can be ignored with this install */
define('NICHE_SITE','');


/* DO NOT EDIT BELOW THIS LINE */
global $settingup;
include(INCLUDE_WEB_ROOT . "scripts/core/database.php");
include(INCLUDE_WEB_ROOT . "scripts/core/dbconnex.php");
include(INCLUDE_WEB_ROOT . "scripts/core/extensions.php");
if(!defined('REDIRECT_ROOT_PATH')) define('REDIRECT_ROOT_PATH',"http://" . $_SERVER['HTTP_HOST'] . BASE_HREF);
if(!defined('INCLUDE_PLUGIN_ROOT')) define('INCLUDE_PLUGIN_ROOT',INCLUDE_WEB_ROOT . "scripts/plugins/");

$site_tables = array('alert_warnings','categories','category_link','links','log_shortcodes','media','members','member_profile','member_profile_arguments','meta_data','options','pages','posts','site_log','site_options');
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
	
	if(NICHE_SITE != "") {
		$fname = INCLUDE_WEB_ROOT . "scripts/niche/" . NICHE_SITE . ".php";
		if(file_exists($fname)) include($fname);
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
	
	if(function_exists('niche_config')) { niche_config(); }
	if(!defined("ISO")) define("ISO","gb");
	if(!defined("LANG")) define("LANG","en");
	
	global $curPosts;
	$curPosts = NULL;
} else {
	$failedWriteable = false;
	if(!is__writable('../uploads/')) { $failedWriteable = true; }
	
	$rs = mq("SELECT * FROM " . DB_TBL_SITE_OPTIONS);
	if ($rs) {
		header('Location: ' . REDIRECT_ROOT_PATH);
		die;
	}
}
?>