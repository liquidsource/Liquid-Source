<?php
/* *** USER RELATED FUNCTIONS *** */
function getMid() { return $_SESSION['mid']; }
function getUserFullName($mid) {
	if($mid > 0) {
		if($_SESSION['userFullName_' . $mid] != "") { return $_SESSION['userFullName_' . $mid]; }
		$member = new Member($mid);
		return $member->userFullName;
	}
    return "";
}
function sendForgotPassword($email) {
	log_me('FGP');
	$rs = mq("select m.mid, m_username, mp_fname, mp_lname from " . DB_TBL_MEMBERS  ." m inner join " . DB_TBL_MEMBER_PROFILE . " p on m.mid = p.mid where m_email='$email' and m_active='1'");
    if(mnr($rs) > 0) {
        $rw = mfa($rs);
        $mid = $rw['mid'];
		$member = new Member($mid);
		$member->sendForgotPassword();
    } else {
        $_SESSION['_mtype'] = "E";
        $_SESSION['_msg'] = "noemailfound";
		log_me('FGF');
    }
}

/* *** HELPER FUNCTIONS *** */
function projectParsers($newf) {
	if(USE_FORM_PARSER) {
		$newf = str_replace("</form>",
							"
							<input type=\"hidden\" id=\"p_timeloaded\" name=\"p_timeloaded\" value=\"" . time() . "\" />
							<input type=\"text\" id=\"aformfield\" name=\"aformfield\" value=\"\" class=\"dnone\" />
							</form>
							",
							$newf);
	}
	return $newf;
}
function getAlertMessage($mtype,$msg_e) {
    if($msg_e != "") {
        if($mtype == "W") { $msg = "<h4 class=\"alert_warning alert\">"; }
        if($mtype == "E") { $msg = "<h4 class=\"alert_error alert\">"; }
        if($mtype == "S") { $msg = "<h4 class=\"alert_success alert\">"; }
        $rw = mgr("select aw_warning from " . DB_TBL_ALERT_WARNINGS . " where aw_slug='" . $msg_e . "'");
        if($rw != "") {  $msg .= stripslashes($rw['aw_warning']); }
        $msg .= "</h4>";
		
	    $_SESSION['_mtype'] = "";
	    $_SESSION['_msg'] = "";
    }
    return $msg;
}
function strToSlug($str,$module=NULL,$uid=NULL){
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    $str = preg_replace('/-+/', "-", $str);
	
	if(!empty($module)) {
		switch ($module) {
			case "page":
				$rs = mq("select pg_slug from " . DB_TBL_PAGES . " where pg_slug='$str' and pgid <> '$uid'");
				if(mnr($rs) > 0) {
					return strToSlug($str . rand(0,100),$module,$uid);
				}
				break;
			case "post":
				$rs = mq("select p_slug from " . DB_TBL_POSTS . " where p_slug='$str' and pid <> '$uid'");
				if(mnr($rs) > 0) {
					return strToSlug($str . rand(0,100),$module,$uid);
				}
				break;
		}
	}
    return $str;
}
function checkFormBot($arr) {
	if($_GET['g_timeloaded'] != $_POST['p_timeloaded']) return true;
	if($_POST['aformfield'] != "") return true;
	
	extract($arr);
	if($timecheck > 0) { if(time() - $_GET['p_timeloaded'] < $timecheck) return true; }
	if($nohtml != "") { if(strlen($nohtml) != striptags(strlen($nohtml))) { return true; } }
	
	return checkBotFromUserAgent();
}
function log_me($sc,$info=NULL) {
	$mid = getMid();
	$ip = getRealIpAddr();
	$ua = $_SERVER['HTTP_USER_AGENT'];
	$rs = mq("insert into " . DB_TBL_SITE_LOG . " (sl_ip,sl_useragent,mid,sl_sc,sl_info) values ('$ip','$ua','$mid','$sc','$info')");
}

/* *** GRAB TABLE INFORMATION, MULTIPLE ROWS *** */
function ls_m_getPostInfo($arr=array()) {
    extract($arr);
    if($num != "") { $limit = $num; } else { $limit = " 0,1000"; }
    if($cid != "") {
        $jc = " inner join " . DB_TBL_CATEGROY_LINK . " c on posts.pid = c.uid ";
        $wc = " and c.l_type='post' and cid='$cid' ";
    }
	if($orderby == "") { $orderby = "pid"; }
	if($orderdir == "") { $orderdir = "asc"; }
	if($active == "") { $wc .= " and p_active='1' "; } else { $wc .= " and p_active='$active' "; }
	
    $ret_arr = array();
    $rs = mq("select pid from " . DB_TBL_POSTS . " $jc where p_type='post' $wc order by $orderby $orderdir limit $limit");
    while($rw = mfa($rs)) {
		$ret = new Post($rw['pid']);
		if($ret != "") $ret_arr[] = $ret;
    }
    return $ret_arr;
}
function ls_m_getTemplateInfo($arr=array()) {
    extract($arr);
    if($num != "") { $limit = $num; } else { $limit = " 0,1000"; }
	if($orderby == "") { $orderby = "pid"; }
	if($orderdir == "") { $orderdir = "asc"; }
	if($active == "") { $wc .= " and p_active='1' "; } else { $wc .= " and p_active='$active' "; }
	
    $ret_arr = array();
    $rs = mq("select pid from " . DB_TBL_POSTS . " where p_type='template' $wc order by $orderby $orderdir limit $limit");
    while($rw = mfa($rs)) {
		$ret = new Template($rw['pid']);
		if($ret != "") $ret_arr[] = $ret;
    }
    return $ret_arr;
}
function ls_m_getPageInfo($arr=array()) {
    extract($arr);
    if($num != "") { $limit = $num; } else { $limit = " 0,1000"; }
	if($orderby == "") { $orderby = "pgid"; }
	if($orderdir == "") { $orderdir = "asc"; }
	if($active == "") { $wc .= " and pg_active='1' "; } else { $wc .= " and pg_active='$active' "; }
    $ret_arr = array();
    $rs = mq("select pgid from " . DB_TBL_PAGES . " where isAdmin=0 $wc order by $orderby $orderdir limit $limit");
    while($rw = mfa($rs)) {
		$ret = new Page($rw['pgid']);
		if($ret != "") $ret_arr[] = $ret;
    }
    return $ret_arr;
}
function ls_m_getMemberInfo($arr=array()) {
	extract($arr);
    if($num != "") { $limit = $num; } else { $limit = " 0,1000"; }
	if($orderby == "") { $orderby = "m.mid"; }
	if($orderdir == "") { $orderdir = "asc"; }
	if($m_type == "") { $m_type = "U"; }
	if($active == "") { $wc .= " and m_active='1' "; } else { $wc .= " and m_active='$active' "; }
	
    $ret_arr = array();
    $rs = mq("select m.mid from " . DB_TBL_MEMBERS . " m inner join " . DB_TBL_MEMBER_PROFILE . " p on m.mid = p.mid where m_type='$m_type' $wc order by $orderby $orderdir limit $limit");
    while($rw = mfa($rs)) {
		$ret = new Member($rw['mid']);
		if($ret != "") $ret_arr[] = $ret;
    }
    return $ret_arr;
}
function ls_m_getMediaInfo($arr=array()) {
	extract($arr);
    if($num != "") { $limit = $num; } else { $limit = " 0,1000"; }
	if($orderby == "") { $orderby = "lid"; }
	if($orderdir == "") { $orderdir = "asc"; }
	if($active == "") { $wc .= " and md_active='1' "; } else { $wc .= " and md_active='$active' "; }
	
	$ret_arr = array();
    $rs = mq("select mdid from " . DB_TBL_MEDIA . " where 1=1 $wc order by $orderby $orderdir limit $limit");
    while($rw = mfa($rs)) {
		$ret = new Media($rw['mdid']);
		if($ret != "") $ret_arr[] = $ret;
    }
    return $ret_arr;
}
function ls_m_getLinkInfo($arr=array()) {
	extract($arr);
    if($num != "") { $limit = $num; } else { $limit = " 0,1000"; }
	if($orderby == "") { $orderby = "lid"; }
	if($orderdir == "") { $orderdir = "asc"; }
	
	$ret_arr = array();
    $rs = mq("select lid from " . DB_TBL_LINKS . " order by $orderby $orderdir limit $limit");
    while($rw = mfa($rs)) {
		$ret = new Link($rw['lid']);
		if($ret != NULL) $ret_arr[] = $ret;
    }
    return $ret_arr;
}
function ls_m_getCategoryInfo($arr=array()) {
	extract($arr);
    if($num != "") { $limit = $num; } else { $limit = " 0,1000"; }
	if($orderby == "") { $orderby = "cid"; }
	if($orderdir == "") { $orderdir = "asc"; }
	if($parent == "") { $parent = "0"; }
	if($type == "") { $type = "post"; }
	$wc = " and c_type='$type' ";
	$ret_arr = array();
	$rs = mq("select * from " . DB_TBL_CATEGORIES . " where c_parent=$parent $wc order by $orderby $orderdir limit $limit");
	while($rw = mfa($rs)) {
		$ret = new Category($rw['cid']);
		if($ret != NULL) $ret_arr[] = $ret;
	}
    return $ret_arr;
}

/* *** HELPER TABLE RELATED FUNCTIONS *** */
function getCategoryId($uid,$l_type='post') {
	return getFieldValue("select cid as n from " . DB_TBL_CATEGORY_LINK . " where uid='$uid' and l_type='$l_type'");
}
function getCategoryName($uid,$l_type='post') {
	$cid = getCategoryId($uid,$l_type);
	if(is_array($cid)) {
		foreach($cid as $c) {
			$ret .= getFieldValue("select c_name as n from " . DB_TBL_CATEGORIES . " where cid='$c'") . ",";
		}
		$ret = substr($ret,0,-1);
	} else{
		$ret = getFieldValue("select c_name as n from " . DB_TBL_CATEGORIES . " where cid='$cid'");
	}
	return $ret;
}
function getFieldValue($sql,$arg='n') {
	$rs = mq($sql);
	if(mnr($rs) > 0) {
		if(mnr($rs) > 1) {
			while($rw=mfa($rs)) {
				$ret_arr[] = $rw[$arg];
			}
			return $ret_arr;
		} else {
			$rw = mfa($rs);
			return $rw[$arg];
		}
	}
	return "";
}
function insertMetaData($arg,$val,$uid,$typee,$ident) {
	if($arg != "") {
		$arg = mres($arg);
		$val = mres($val);
		$rw = mgr("select mdid from " . DB_TBL_META_DATA . " where md_recogniser='$ident' and uid='$uid' and md_type='$typee'");
		if($rw != "") {
			$mdid = $rw['mdid'];
			$rsi = mq("update " . DB_TBL_META_DATA . " set md_arg='$arg', md_val='$val' where mdid='$mdid'");
		} else {
			$rsi = mq("insert into " . DB_TBL_META_DATA . " (md_arg,md_val,uid,md_type,md_recogniser) values ('$arg','$val','$uid','$typee','$ident')");
		}
	}
}
function getMetaData($uid,$typee) {
	$i=0;
	$ret_arr = array();
	$rs = mq("select md_val,md_arg from " . DB_TBL_META_DATA . " where uid='$uid' and md_type='$typee' order by md_recogniser");
	while($rw = mfa($rs)) {
		$ret_arr[$i]['arg'] = stripslashes($rw['md_arg']);
		$ret_arr[$i]['val'] = stripslashes($rw['md_val']);
		$i++;
	}
	return $ret_arr;
}
?>