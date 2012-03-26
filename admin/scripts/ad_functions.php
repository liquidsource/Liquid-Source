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
	$cid = $category->id;
	$ret .= "
	<tr>
        <td>$dots $cid</td>
        <td>" . $dots . $category->name . "</td>
        <td>
            <a href=\"?module=" . $type . "categories&cid=$cid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>
            <a href=\"scripts/action.php?action=deletecategory&cid=$cid&to=" . $type . "categories&c_type=$type\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Trash\"></a>
        </td> 
    </tr>";
    if($category->children) {
    	$lvl++;
    	foreach($category->children as $child_category) {
    		$ret .= showCategoryRow($child_category,$lvl,$type);
    	}
	}
	return $ret;
}
function updateCategoryLink($cids,$uid,$l_type) {
	if(!is_array($cids)) {
		$cids[] = $cids;
	}
	
	foreach($cids as $cid) {
		$rs = mq("select * from " . DB_TBL_CATEGORY_LINK . " where cid='$cid' and uid='$uid' and l_type='$l_type'");
	    if(mnr($rs) > 0) {
	    	
	    } else {
	        $rsi = mq("insert into " . DB_TBL_CATEGORY_LINK . " (uid,cid,l_type) values ('$uid','$cid','$l_type')");
	    }
		$xsql .= " cid != '$cid' and ";
	}
	
	$rs = mq("select * from " . DB_TBL_CATEGORY_LINK . " where ($xsql 1=1) and l_type='$l_type' and uid='$uid'");
	while($rw = mfa($rs)) {
		$rsd = mq("delete from " . DB_TBL_CATEGORY_LINK . " where jcid='" . $rw['jcid'] . "'");
	}
}
function showCategoryOption($category,$lvl=0,$cur_category,$hide=false) {
	if(!is_array($cur_category)) {
		$chosen_uid = $cur_category->parent;
		$catid = $cur_category->id;
	}
	
	
	$cid = $category->id;
	if($catid == $cid && $hide) {
		
	} else {
		$dots = "";
		for($i=0;$i<$lvl;$i++) { $dots .= "-  "; }
		
		$ret .= "<option value=\"$cid\"";
		
		
		if(is_array($cur_category)) {
			foreach($cur_category as $cat) {
				$chuid = $cat->id;
				if($cid == $chuid) { $ret .= " selected "; }
			}
		} else {
			if($cid == $cur_category->id) { $ret .= " selected "; }
		}
		$ret .= ">" . $dots . $category->name . "</option>";
	    if($category->children) {
	    	$lvl++;
	    	foreach($category->children as $child_category) {
	    		$ret .= showCategoryOption($child_category,$lvl,$cur_category);
	    	}
		}
	}
	return $ret;
}
?>