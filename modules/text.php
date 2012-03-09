<?php
$page = new Page('',$module);
echo "<h1>" . $page->pg_title . "</h1>";
echo $page->pg_content;
?>