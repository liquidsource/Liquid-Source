<?php
/*
 * To create a new plugin you need to setup the plugins in the 'niche site' core file, using these two lines:
 * 
 * check_plugin_install('pluginname');
 * include(INCLUDE_PLUGIN_ROOT . "pluginname/pluginname.php");
 * 
 * The plugin requires 2 files, located in 'scripts/plugins/pluginname/' folder:
 *  install.php - only ran once when plugin is first installed
 *  pluginname.php - ran every page load
 */
 
 
/* Funciton that is called throughout the site, to run plugin hooks */

$rs_plugin_load = mq("select * from " . DB_TBL_OPTIONS . " where op_arg='$plugin_code' and op_section='plugin_hook'");
while($rw_plugin_load = mfa($rs_plugin_load)) {
	$fname = INCLUDE_PLUGIN_ROOT . $rw_plugin_load['op_val'];
	if(file_exists($fname)) include($fname);
}
?>