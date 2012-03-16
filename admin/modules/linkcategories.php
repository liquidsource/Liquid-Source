<?php
$llimit = "0";
$hlimit = "20";

if($_GET['llimit'] != "") $llimit = $_GET['llimit'];
if($_GET['hlimit'] != "") $hlimit = $_GET['hlimit'];

$cid = $_GET['cid'];
if($cid != "") {
    $category = new Category($cid);
} else {
	$category = new Category();
}
?>
<article class="module width_full">
<header><h3 class="tabs_involved">Link Categories</h3></header>
<div class="tab_container">
    <table class="tablesorter" cellspacing="0"> 
    <thead> 
        <tr> 
            <th>cid</th> 
            <th>Category</th> 
            <th>Actions</th> 
        </tr> 
    </thead> 
    <tbody>
        <?php
        $arr = getCategories(array("num" => $llimit . "," . $hlimit, "orderby" => $orderby, "orderdir" => $orderdir, "type" => 'link'));
		foreach($arr as $cat) {
			echo showCategoryRow($cat);
		}
		?>
    </tbody> 
    </table>
</div>
</article>

<form method="post" action="scripts/action.php?action=newcategory&to=linkcategories&c_type=link&cid=<?php echo $cid; ?>">
<article class="module width_full">
<header><h3 class="tabs_involved">Edit / New Link Category</h3></header>
<div class="tab_container">

    <table class="tablesorter" cellspacing="0">
    <tbody>
        <tr>
            <td>Parent: 
            <select name="c_parent" id="c_parent">
                <option value="0">Parent</option>
                <?php
                $arr = getCategories(array("num" => $llimit . "," . $hlimit, "orderby" => $orderby, "orderdir" => $orderdir, "type" => "link"));
				foreach($arr as $i_category) {
					echo showCategoryOption($i_category,'0',$category->parent);
				}
                ?>
            </select>
            </td>
            <td><input type="text" name="c_name" id="c_name" value="<?php echo $category->name; ?>" /></td>
            <td><input type="submit" value="Edit / New" class="alt_btn" /></td>
        </tr>
    </tbody> 
    </table>
</div>
</article>
</form>