<?php
/* Config */
if ( !defined('INCLUDE_WEB_ROOT') ) define('INCLUDE_WEB_ROOT', dirname(__FILE__) . '/../');
include(INCLUDE_WEB_ROOT . "scripts/config/site_config.php");

/* Classes */
include(INCLUDE_WEB_ROOT . "scripts/classes/core/member.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/page.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/post.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/link.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/media.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/template.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/category.php");
include(INCLUDE_WEB_ROOT . "scripts/classes/core/email.php");

/* System Function Files */
include(INCLUDE_WEB_ROOT . "scripts/core/base.php");
include(INCLUDE_WEB_ROOT . "scripts/core/page_structure.php");
include(INCLUDE_WEB_ROOT . "scripts/core/useful.php");

/* Your project specific php functions */
include(INCLUDE_WEB_ROOT . "scripts/general.php");
?>