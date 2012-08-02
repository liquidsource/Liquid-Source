<?php
define("DB_TBL_COUNTRIES",TABLE_PREFIX . "countries");

include(INCLUDE_PLUGIN_ROOT . "multilang/class_countries.php");
include(INCLUDE_PLUGIN_ROOT . "multilang/class_translation.php");

$iso = $_SESSION['meta_iso'];
$lang = $_SESSION['meta_lang'];

if($_GET['iso'] != "") {
	$iso = $_GET['iso'];
	$country = new Country($iso);
	$lang = $country->lang_code;
}
if($iso == "") {
	$iso = Country::getDefaultCountry()->iso;
	$country = new Country($iso);
	$lang = $country->lang_code;
}

if($iso == "") {
	$lang = "en";
	$iso = "gb";
}

$_SESSION['meta_iso'] = $iso;
$_SESSION['meta_lang'] = $lang;

define("ISO",$iso);
define("LANG",$lang);
?>