<?php
$inAdmin = true;
include("../scripts/system.php");
include("scripts/ad_functions.php");

if(Member::isLoggedin('A')) {
	if(!isset($_GET['module'])) { $module_system = ""; } else { $module_system = $_GET['module']; }
	$module = getModule($module_system);
} else{
	$module = "login";
}
include("incs/page_head.php");
?>
	<header id="header"><?php include("incs/header.php"); ?></header>
	
    <?php if(Member::isLoggedin('A')) { include("incs/side_nav.php"); } ?>
    
	<section id="main" class="column" <?php if(isset($_SESSION['m_type']) && $_SESSION['m_type'] != 'A') { ?>style="width: 100%;"<?php } ?>>
		<?php echo showAlertMessage(); ?>
		<?php getModuleData($module); ?>
	</section>
</body>
</html>