<?php
class LS {
	public function __construct() {
		
	}
	
	public static function posts($arr=array()) {
	    extract($arr);
	    if($num != "") { $limit = $num; } else { $limit = " 0,1000"; }
	    if($category_id != "") {
	        $jc = " inner join " . DB_TBL_CATEGROY_LINK . " c on posts.pid = c.uid ";
	        $wc = " and c.l_type='post' and cid='$cid' ";
	    }
		if($orderby == "") { $orderby = "pid"; }
		if($orderdir == "") { $orderdir = "asc"; }
		if($active == "") { $wc .= " and p_active='1' "; } else { $wc .= " and p_active='$active' "; }
		if($state == "") { $wc .= " and p_posttype='published' "; } else {
			switch($state) {
				case "draft":
					$wc .= " and p_posttype == 'draft' "; break;
				case "published":
					$wc .= " and p_posttype == 'published' "; break;
			}	
		}
		if($datefrom != "") { $wc .= " and p_publisheddate >= '$datefrom' "; }
		if($dateto != "") { $wc .= " and p_publisheddate <= '$datefrom' "; }
		if($content != "") { $wc .= " and p_content like '%$content%' "; }
		
		/* Plugin option */
		$plugin_code = "class.liquid_source.posts.wc"; include(INCLUDE_PLUGIN_ROOT . "core.php");
		
		if(!Member::isLoggedin('A')) { $wc .= " and p_publisheddate <= '" . date('Y-m-d H:i:s') . "' "; }
		
	    $ret_arr = array();
	    $rs = mq("select pid from " . DB_TBL_POSTS . " $jc where p_type='post' $wc and p_posttype != 'inherit' order by $orderby $orderdir limit $limit");
	    while($rw = mfa($rs)) {
			$ret = new Post($rw['pid']);
			if($ret != "") $ret_arr[] = $ret;
	    }
		global $curPosts;
		$curPosts = $ret_arr;
	    return $ret_arr;
	}
	public static function templates($arr=array()) {
	    extract($arr);
	    if($num != "") { $limit = $num; } else { $limit = " 0,1000"; }
		if($orderby == "") { $orderby = "pid"; }
		if($orderdir == "") { $orderdir = "asc"; }
		if($active == "") { $wc .= " and p_active='1' "; } else { $wc .= " and p_active='$active' "; }
		if($state == "") { $wc .= " and p_posttype='published' "; } else {
			switch($state) {
				case "draft":
					$wc .= " and p_posttype == 'draft' "; break;
				case "published":
					$wc .= " and p_posttype == 'published' "; break;
			}	
		}
		
		/* Plugin option */
		$plugin_code = "class.liquid_source.templates.wc"; include(INCLUDE_PLUGIN_ROOT . "core.php");
		
	    $ret_arr = array();
	    $rs = mq("select pid from " . DB_TBL_POSTS . " where p_type='template' $wc and p_posttype != 'inherit' order by $orderby $orderdir limit $limit");
	    while($rw = mfa($rs)) {
			$ret = new Template($rw['pid']);
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
		if($state == "") { $wc .= " and pg_posttype='published' "; } else {
			switch($state) {
				case "draft":
					$wc .= " and pg_posttype = 'draft' "; break;
				case "published":
					$wc .= " and pg_posttype = 'published' "; break;
			}	
		}
		if(!Member::isLoggedin('A')) { $wc .= " and pg_publisheddate <= '" . date('Y-m-d H:i:s') . "' "; }
		
		/* Plugin option */
		$plugin_code = "class.liquid_source.pages.wc"; include(INCLUDE_PLUGIN_ROOT . "core.php");
		
	    $ret_arr = array();
	    $rs = mq("select pgid from " . DB_TBL_PAGES . " where isAdmin=0 $wc and pg_posttype != 'inherit' order by $orderby $orderdir limit $limit");
	    while($rw = mfa($rs)) {
			$ret = new Page('',$rw['pgid']);
			if($ret != "") $ret_arr[] = $ret;
	    }
	    return $ret_arr;
	}
	public static function members($arr=array()) {
		extract($arr);
	    if($num != "") { $limit = $num; } else { $limit = " 0,1000"; }
		if($orderby == "") { $orderby = "mid"; }
		if($orderdir == "") { $orderdir = "asc"; }
		if($m_type == "") { $m_type = "U"; }
		if($active == "") { $wc .= " and m_active='1' "; } else { $wc .= " and m_active='$active' "; }
		
		/* Plugin option */
		$plugin_code = "class.liquid_source.members.wc"; include(INCLUDE_PLUGIN_ROOT . "core.php");
		
	    $ret_arr = array();
	    $rs = mq("select mid from " . DB_TBL_MEMBERS . " where m_type='$m_type' $wc order by $orderby $orderdir limit $limit");
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
		
		/* Plugin option */
		$plugin_code = "class.liquid_source.media.wc"; include(INCLUDE_PLUGIN_ROOT . "core.php");
		
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
		
		/* Plugin option */
		$plugin_code = "class.liquid_source.links.wc"; include(INCLUDE_PLUGIN_ROOT . "core.php");
		
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
		
		/* Plugin option */
		$plugin_code = "class.liquid_source.categories.wc"; include(INCLUDE_PLUGIN_ROOT . "core.php");
		
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