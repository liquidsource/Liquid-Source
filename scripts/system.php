<?php
/* Config */
if ( !defined('INCLUDE_WEB_ROOT') ) define('INCLUDE_WEB_ROOT', dirname(__FILE__) . '/../');
include(INCLUDE_WEB_ROOT . "scripts/config/site_config.php");

/* Classes */
include(INCLUDE_WEB_ROOT . "scripts/core/classes/liquid_source.php");
include(INCLUDE_WEB_ROOT . "scripts/core/classes/member.php");
include(INCLUDE_WEB_ROOT . "scripts/core/classes/page.php");
include(INCLUDE_WEB_ROOT . "scripts/core/classes/post.php");
include(INCLUDE_WEB_ROOT . "scripts/core/classes/link.php");
include(INCLUDE_WEB_ROOT . "scripts/core/classes/media.php");
include(INCLUDE_WEB_ROOT . "scripts/core/classes/template.php");
include(INCLUDE_WEB_ROOT . "scripts/core/classes/category.php");
include(INCLUDE_WEB_ROOT . "scripts/core/classes/email.php");
include(INCLUDE_WEB_ROOT . "scripts/core/classes/feed.php");

/* System Function Files */
include(INCLUDE_WEB_ROOT . "scripts/core/base.php");
include(INCLUDE_WEB_ROOT . "scripts/core/page_structure.php");
include(INCLUDE_WEB_ROOT . "scripts/core/useful.php");


php_error_reporting(PHP_ERROR_REPORTING);
global $settingup;
if(!$settingup) {
	checkLicenceKey();
	if(NICHE_SITE != "") {
		$fname = INCLUDE_WEB_ROOT . "scripts/extensions/niche/" . NICHE_SITE . ".php";
		if(file_exists($fname)) include($fname);
	}
	
	if(!isset($_GET['module'])) { $module_system = ""; } else { $module_system = $_GET['module']; }
	define('MODULE', getModule($module_system));
	
}
?>