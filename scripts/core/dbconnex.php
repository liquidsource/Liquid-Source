<?php
/* Database connection strings */
if(USE_MYSQLI) {
	$db = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	if(mysqli_connect_errno()) {
		$dbconnexfailed = true;
	}
} else {
	if(!mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
		$dbconnexfailed = true;
	}
	if(! @mysql_select_db(DB_NAME)) {
		$dbconnexfailed = true;
	}
}
session_start();
?>