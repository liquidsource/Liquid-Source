<?php
$cid = "";
if(isset($_GET['cid'])) $cid = $_GET['cid'];
if($cid != "") {
    $category = new Category($cid);
	$editnew = "Edit";
} else {
	$category = new Category();
	$editnew = "New";
}
?>
<article class="module width_full">
<header><h3 class="tabs_involved"><?php echo $l_type; ?> Categories</h3></header>
<div class="tab_container">
    <table class="tablesorter dataTable" cellspacing="0"> 
    <thead> 
        <tr> 
            <th> cid</th> 
            <th> Category</th> 
            <th> Actions</th> 
        </tr> 
    </thead> 
    <tbody>
        <?php
        $arr = getCategories(array("type" => $l_type));
		foreach($arr as $cat) {
			echo showCategoryRow($cat,0,$l_type);
		}
		?>
    </tbody> 
    </table>
    <p style="clear:both">&nbsp;</p>
    <br />
</div>
<?php if($cid != "") { ?>
<footer>
    <div class="submit_link">
    	<input type="button" value="Add a New Category" class="alt_btn" onclick="location.href='index.php?module=<?php echo $l_type; ?>categories'" />
    </div>
</footer>
<?php } ?>
</article>

<form method="post" action="scripts/action.php?action=newcategory&c_type=<?php echo $l_type; ?>&cid=<?php echo $cid; ?>">
<article class="module width_full">
<header><h3 class="tabs_involved"><?php echo $editnew . " " . $l_type; ?> Category</h3></header>
<div class="tab_container">

    <table class="tablesorter" cellspacing="0">
    <tbody>
        <tr>
            <td>Parent: 
            <select name="c_parent" id="c_parent">
                <option value="0">Parent</option>
                <?php
                $arr = getCategories(array("type" => $l_type));
				foreach($arr as $i_category) {
					echo showCategoryOption($i_category,'0',$category,true,true);
				}
                ?>
            </select>
            </td>
            <td><input type="text" name="c_name" id="c_name" value="<?php echo $category->c_name; ?>" /></td>
            <td><input type="submit" value="<?php echo $editnew; ?>" class="alt_btn" /></td>
        </tr>
    </tbody> 
    </table>
</div>

</article>
</form>