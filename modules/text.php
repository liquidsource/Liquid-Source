<?php
/**
 * This is a special reserved page for loading up textual page content. Style it as you need and then use it for all the generic text pages on your site. 
 */
?>
<p>This is an example text page.</p>
<p>Remove this line of text by editing <code>'modules/text.php'</code></p>
<hr />
<?php
$page = new Page(MODULE);
echo "<h1>" . $page->pg_meta_title . "</h1>";
echo $page->pg_content;
?>