<?php
function showCategoryRow($category,$lvl=0,$type='post') {
	$ret = "";
	$dots = "";
	for($i=0;$i<$lvl;$i++) { $dots .= " -- "; }
	$cid = $category->cid;
	$to = $type . "categories";
	if(defined('GLOBAL_CAT_TO')) $to = GLOBAL_CAT_TO;
	$ret .= "
	<tr>
        <td>$dots $cid</td>
        <td>" . $dots . $category->c_name . "</td>
        <td>
            <a href=\"?module=" . $to . "&cid=$cid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>
            <a href=\"scripts/action.php?action=deletecategory&cid=$cid&to=" . $type . "categories&c_type=$type\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Trash\"></a>
        </td> 
    </tr>";
    if($category->c_children) {
    	$lvl++;
    	foreach($category->c_children as $child_category) {
    		$ret .= showCategoryRow($child_category,$lvl,$type);
    	}
	}
	return $ret;
}
?>