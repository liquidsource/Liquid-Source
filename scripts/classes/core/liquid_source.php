<?php
class LS {
	public function __construct() {
		
	}
	
	public static function posts($arr=array()) {
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
			$ret = new Post('',$rw['pid']);
			if($ret != "") $ret_arr[] = $ret;
	    }
	    return $ret_arr;
	}
	public static function templates($arr=array()) {
	    extract($arr);
	    if($num != "") { $limit = $num; } else { $limit = " 0,1000"; }
		if($orderby == "") { $orderby = "pid"; }
		if($orderdir == "") { $orderdir = "asc"; }
		if($active == "") { $wc .= " and p_active='1' "; } else { $wc .= " and p_active='$active' "; }
		
	    $ret_arr = array();
	    $rs = mq("select pid from " . DB_TBL_POSTS . " where p_type='template' $wc order by $orderby $orderdir limit $limit");
	    while($rw = mfa($rs)) {
			$ret = new Template('',$rw['pid']);
			if($ret != "") $ret_arr[] = $ret;
	    }
	    return $ret_arr;
	}
	public static function pages($arr=array()) {
	    extract($arr);
	    if($num != "") { $limit = $num; } else { $limit = " 0,1000"; }
		if($orderby == "") { $orderby = "pgid"; }
		if($orderdir == "") { $orderdir = "asc"; }
		if($active == "") { $wc .= " and pg_active='1' "; } else { $wc .= " and pg_active='$active' "; }
	    $ret_arr = array();
	    $rs = mq("select pgid from " . DB_TBL_PAGES . " where isAdmin=0 $wc order by $orderby $orderdir limit $limit");
	    while($rw = mfa($rs)) {
			$ret = new Page('',$rw['pgid']);
			if($ret != "") $ret_arr[] = $ret;
	    }
	    return $ret_arr;
	}
	public static function members($arr=array()) {
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
	public static function media($arr=array()) {
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
	public static function links($arr=array()) {
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
	public static function categories($arr=array()) {
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
}
?>