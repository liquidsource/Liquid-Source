<?php
include("scripts/system.php");
$module = getModule($_GET['module']);
?>
<?php topPage($module); ?>
<header>
    <?php include("incs/header.php"); ?>
</header>
<section id="main" role="main">
    <?php include("incs/main.php"); ?>
</section>
<footer>
    <?php include("incs/footer.php"); ?>
</footer>
<?php bottomPage($module) ?>