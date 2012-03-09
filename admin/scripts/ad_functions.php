<?php
function tableSorter($colname) {
    global $module, $m_type;
	if($_SESSION['curorderby'] == $colname) {
		if($_SESSION['orderdir'] == "desc") {
			$img = "desc";
		} else {
			$img = "asc";
		}
	} else {
		$img = "ascdesc";
	}
	$img = "images/arr_" . $img . ".png";
	if($module == "editusers") {
		$sc = "&m_type=$m_type";
	}
    return " onclick=\"location.href='?module=$module&orderby=$colname" . $sc . "'\"><img src=\"$img\" ";
}
function setOrderBy($def) {
	$orderby = $_SESSION['curorderby'];
	$orderdir = $_SESSION['orderdir'];
	if($orderby == "") $orderby = $def;
	if($orderdir == "") $orderdir = "asc";
	
	if($_GET['orderby'] != "" && $_GET['llimit'] == "" && $_GET['mtype'] == "") {
		$orderby = $_GET['orderby'];
		if($orderby == $_SESSION['curorderby']) {
			if($_SESSION['orderdir'] == "asc") {
				$orderdir = "desc";
			} else {
				$orderdir = "asc";
			}
		} else {
			$orderdir = "asc";
		}
	}
	$_SESSION['curorderby'] = $orderby;
	$_SESSION['orderdir'] = $orderdir;
}
function clearSessionSorts() {
	$_SESSION['curorderby'] = "";
	$_SESSION['orderdir'] = "";
	$_SESSION['jsql'] = "";
	$_SESSION['usql'] = "";
	$_SESSION['csql'] = "";
	$_SESSION['tsql'] = "";
	$_SESSION['modsql'] = "";
	$_SESSION['psql'] = "";
	
	$_SESSION['srch_j_title'] = "";
	$_SESSION['srch_cmid'] = "";
	$_SESSION['srch_jid'] = "";
	$_SESSION['srch_active'] = "";
	$_SESSION['srch_j_ref'] = "";
}
function showCategoryRow($category,$lvl=0,$type='post') {
	$ret = "";
	$dots = "";
	for($i=0;$i<$lvl;$i++) { $dots .= " -- "; }
	$cid = $category->cid;
	$ret .= "
	<tr>
        <td>$cid</td>
        <td>" . $dots . $category->c_name . "</td>
        <td>
            <a href=\"?module=" . $type . "categories&cid=$cid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>
            <a href=\"scripts/action.php?action=deletecategory&cid=$cid&to=" . $type . "categories&c_type=$type\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Trash\"></a>
        </td> 
    </tr>";
    if(!empty($category->children)) {
    	$lvl++;
    	foreach($category->children as $child_category) {
    		$ret .= showCategoryRow($child_category,$lvl,$type);
    	}
	}
	return $ret;
}
function updateCategoryLink($cid,$uid,$l_type,$forceInsert=false) {
	$rs = mq("select * from category_link where uid='$uid' and l_type='$l_type'");
    if(mnr($rs) > 0 && !$forceInsert) {
        $rsi = mq("update category_link set cid='$cid' where uid='$uid' and l_type='$l_type'");
    } else {
        $rsi = mq("insert into category_link (uid,cid,l_type) values ('$uid','$cid','$l_type')");
    }
}
function showCategoryOption($category,$lvl=0,$chosen_uid) {
	$dots = "";
	for($i=0;$i<$lvl;$i++) { $dots .= "-  "; }
	$cid = $category->cid;
	$ret .= "<option value=\"$cid\"";
	
	if(is_array($chosen_uid)) {
		foreach($chosen_uid as $chuid) {
			if($cid == $chuid) { $ret .= " selected "; }
		}
	} else {
		if($cid == $chosen_uid) { $ret .= " selected "; }
	}
	$ret .= ">" . $dots . $category->c_name . "</option>";
    if(!empty($category->children)) {
    	$lvl++;
    	foreach($category->children as $child_category) {
    		$ret .= showCategoryOption($child_category,$lvl,$chosen_uid);
    	}
	}
	return $ret;
}
?>