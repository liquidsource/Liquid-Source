<?php
/**
 * This is a special reserved page for loading up textual page content. Style it as you need and then use it for all the generic text pages on your site. 
 */
?>
<p>This is an example text page.</p>
<hr />
<?php
$page = new Page(MODULE);
echo "<h1>" . $page->title . "</h1>";
echo $page->content;
?>
