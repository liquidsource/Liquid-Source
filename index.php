<?php
/* The core file that needs to be included on all pages */
include("scripts/system.php");

/* This echos out everything upto and including the <body> tag */
topPage(MODULE);

/* You are now ready to roll with the page */
?>

<header>
    <?php include("incs/header.php"); ?>
</header>
<section id="main" role="main">
    <?php include("incs/main.php"); ?>
</section>
<footer>
    <?php include("incs/footer.php"); ?>
</footer>

<?php
/* And now echo out all the JS and closing body and html tags */
bottomPage($module);
?>