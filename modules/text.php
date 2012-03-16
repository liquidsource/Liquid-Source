<p>This is an example text page.</p>
<hr />
<?php
$page = new Page(MODULE);
echo "<h1>" . $page->title . "</h1>";
echo $page->content;
?>
