<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>Dashboard I Admin Panel</title>
	<link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" /><link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/base/jquery-ui.css" type="text/css" media="all" />
    <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.8.2/css/jquery.dataTables.css" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="js/hideshow.js" type="text/javascript"></script>
	<script src="../js/core/ls_forms.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/general.js"></script>
    <script type="text/javascript" src="js/timepicker.js"></script>
    <script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.8.2/jquery.dataTables.min.js"></script>
    <script><?php echo getParserJavascript(); ?></script>
	<?php
    /* Plugin option */
	$plugin_code = "admin.head.css"; include(INCLUDE_PLUGIN_ROOT . "core.php");
	$plugin_code = "admin.head.javascript"; include(INCLUDE_PLUGIN_ROOT . "core.php");
    ?>
</head>
<body>