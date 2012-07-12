<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

/* Config */
if ( !defined('INCLUDE_WEB_ROOT') ) define('INCLUDE_WEB_ROOT', dirname(__FILE__) . '/../');
include(INCLUDE_WEB_ROOT . "scripts/config/site_config.php");

/* Classes */
include(INCLUDE_WEB_ROOT . "scripts/classes/core/liquid_source.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/member.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/page.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/post.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/link.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/media.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/template.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/category.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/email.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/feed.php");

/* System Function Files */
include(INCLUDE_WEB_ROOT . "scripts/core/base.php");
include(INCLUDE_WEB_ROOT . "scripts/core/page_structure.php");
include(INCLUDE_WEB_ROOT . "scripts/core/useful.php");

/* Your project specific php functions */
include(INCLUDE_WEB_ROOT . "scripts/general.php");

global $settingup;
if(!$settingup) {
	
	if(NICHE_SITE != "") {
		$fname = INCLUDE_WEB_ROOT . "scripts/niche/" . NICHE_SITE . ".php";
		if(file_exists($fname)) include($fname);
	}
	
	if(!isset($_GET['module'])) { $module_system = ""; } else { $module_system = $_GET['module']; }
	define('MODULE', getModule($module_system));
}
?>