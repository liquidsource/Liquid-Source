<?php
$llimit = "0";
$hlimit = "20";

if($_GET['llimit'] != "") $llimit = $_GET['llimit'];
if($_GET['hlimit'] != "") $hlimit = $_GET['hlimit'];

$l_type = "media";
include("incs/category_master.php");
?>