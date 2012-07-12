<?php
function showCategoryRow($category,$lvl=0,$type='post') {
	$ret = "";
	$dots = "";
	for($i=0;$i<$lvl;$i++) { $dots .= " -- "; }
	$cid = $category->cid;
	$ret .= "
	<tr>
        <td>$dots $cid</td>
        <td>" . $dots . $category->c_name . "</td>
        <td>
            <a href=\"?module=" . $type . "categories&cid=$cid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>
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
function showCategoryOption($category,$lvl=0,$cur_category,$hide=false,$selectparent=false) {
	$catid = "";
	if(!is_array($cur_category)) {
		$chosen_uid = $cur_category->c_parent;
		$catid = $cur_category->cid;
	}
	
	
	$cid = $category->cid;
	$ret = "";
	
	$dots = "";
	for($i=0;$i<$lvl;$i++) { $dots .= "-  "; }
	$ret .= "<option value=\"$cid\"";
	if($catid == $cid && $hide) {
		$ret .= " disabled ";
	}
	
	if(is_array($cur_category)) {
		foreach($cur_category as $cat) {
			$chuid = $cat->cid;
			if($cid == $chuid) { $ret .= " selected "; }
		}
	} else {
		if($selectparent) {
			$parent = $cur_category->c_parent;
			if($parent == $category->cid) {
				$ret .= " selected ";
			}
		} else {
			if($cid == $cur_category->cid) { $ret .= " selected "; }
		}
	}
	$ret .= ">" . $dots . $category->c_name . "</option>";
    if($category->c_children) {
    	$lvl++;
    	foreach($category->c_children as $child_category) {
    		$ret .= showCategoryOption($child_category,$lvl,$cur_category,$hide,$selectparent);
    	}
	}
	return $ret;
}
?>