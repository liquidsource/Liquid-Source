<?php
function check_plugin_install($pluginname) {
	$rs = mq("select * from " . DB_TBL_OPTIONS . " where op_section='installedplugin' and op_arg='installedplugin' and op_val='$pluginname'");
	if(mnr($rs) == 0)  {
		$fname = INCLUDE_WEB_ROOT . "scripts/plugins/" . $pluginname . "/install.php";
		if(file_exists($fname)) {
			include($fname);
			$rsi = mq("insert into " . DB_TBL_OPTIONS . " (op_section,op_arg,op_val) values ('installedplugin','installedplugin','$pluginname')");
		} else {
			echo "Installing '" . $pluginname . "' plugin error. Quitting";
			die;
		}
		
	}
}

function register_niche_site($niche) {
	$rs = mq("select * from " . DB_TBL_OPTIONS . " where op_section='niche_site' and op_arg='niche_site' and op_val='$niche'");
	if(mnr($rs) == 0) {
		$rsi = mq("insert into " . DB_TBL_OPTIONS . " (op_section,op_arg,op_val) values ('niche_site','niche_site','$niche')");
	}
}
?>