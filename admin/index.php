<?php
include("../scripts/system.php");
include("scripts/ad_functions.php");

if(Member::isLoggedin('A')) {
	$module = getModule($_GET['module']);
} else{
	$module = "login";
}
include("incs/page_head.php");
?>
	<header id="header"><?php include("incs/header.php"); ?></header>
	
    <?php if(Member::isLoggedin('A')) { include("incs/side_nav.php"); } ?>
    
	<section id="main" class="column" <?php if($_SESSION['m_type'] != 'A') { ?>style="width: 100%;"<?php } ?>>
		<?php echo getAlertMessage($_SESSION['_mtype'],$_SESSION['_msg']); ?>
		<?php getModuleData($module); ?>
	</section>
</body>
</html>