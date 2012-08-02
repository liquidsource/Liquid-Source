<?php
/* The core file that needs to be included on all pages */
include("../scripts/system.php");

/* This echos out everything upto and including the <body> tag */
topPage(MODULE);

/* You are now ready to roll with the page */
?>

Hey

<?php
/* And now echo out all the JS and closing body and html tags */
bottomPage(MODULE);
?>