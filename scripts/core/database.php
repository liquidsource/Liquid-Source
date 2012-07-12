<?php
/* Database helper functions */
function mq($query) {
	if(USE_MYSQLI) {
		global $db;
	    return $db -> query($query);
	} else {
		return mysql_query($query);
	}
}
function mnr($rs) {
	if(USE_MYSQLI) {
		return $rs -> num_rows;
	} else {
		return mysql_num_rows($rs);
	}
}
function mfa($rs) {
	if(USE_MYSQLI) {
		return $rs -> fetch_assoc();
	} else {
		return mysql_fetch_assoc($rs);
	}
	
}
function mres($val) {
	if(gettype($val) == "string") {
		if(USE_MYSQLI) {
			global $db;
			return mysqli_real_escape_string($db,trim($val));
		} else {
			return mysql_real_escape_string(trim($val));
		}
	}
}
function miid() {
	if(USE_MYSQLI) {
		global $db;
		return mysqli_insert_id($db);
	} else {
		return mysql_insert_id();
	}
}
function mgr($sql) {
	$rs = mq($sql);
	if(mnr($rs) > 0) {
		$rw = mfa($rs);
		return $rw;
	} else {
		return "";
	}
}
?>
