<?php
include("../../scripts/system.php");
include("scripts/ad_functions.php");
$userid = getMid();

switch (strtolower($_GET['ajaxcall'])) {
	case "showhidemenu":
		$menuid = $_GET['menu'];
		$rs = mq("select soid, so_val from " . DB_TBL_SITE_OPTIONS . " where so_arg='$menuid' and so_userid='$userid'");
		if(mnr($rs) > 0) {
			$rw = mfa($rs);
			$soid = $rw['soid'];
			$nv = "SHOW";
			if($rw['so_val'] == "SHOW") { $nv = "HIDE"; }
			$rsu = mq("update " . DB_TBL_SITE_OPTIONS . " set so_val='$nv' where soid='$soid'");
		} else {
			$rsi = mq("insert into " . DB_TBL_SITE_OPTIONS . " (so_arg,so_val,so_type,so_userid) values ('$menuid','SHOW','admin_option','$userid')");
		}
		break;
	case "getshowhidemenu":
		$menuid = $_GET['menu'];
		$rs = mq("select soid, so_val from " . DB_TBL_SITE_OPTIONS . " where so_arg='$menuid' and so_userid='$userid'");
		if(mnr($rs) > 0) {
			$rw = mfa($rs);
			echo strtoupper($rw['so_val']);
		} else {
			echo "HIDE";
		}
		break;
}
?>