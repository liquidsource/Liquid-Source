<?php
class LS {
	/********************/
	/* PUBLIC FUNCTIONS */
	/********************/
	public function __construct() {
		
	}
	
	public static function posts($arr=array()) {
		$wc = "";
		$jc = "";
		$orderdir = "asc";
		$orderby = "pid";
	    extract($arr);
	    if(isset($num)) { $limit = $num; } else { $limit = " 0,1000"; }
	    if(isset($category_id)) {
	    	if(!array($category_id)) $category_id = array($category_id);
			foreach($category_id as $val) $wc .= " and pid in (select uid from " . DB_TBL_CATEGORY_LINK . " where cid='$val' and l_type='post') ";
	    }
		if(!isset($active)) { $wc .= " and p_active='1' "; } else { $wc .= " and p_active='$active' "; }
		if(!isset($state)) { $wc .= " and p_posttype='published' "; } else {
			switch($state) {
				case "draft":
					$wc .= " and p_posttype == 'draft' "; break;
				case "published":
					$wc .= " and p_posttype == 'published' "; break;
			}	
		}
		if(isset($datefrom)) { $wc .= " and p_publisheddate >= '$datefrom' "; }
		if(isset($dateto)) { $wc .= " and p_publisheddate <= '$datefrom' "; }
		if(isset($content)) { $wc .= " and p_content like '%$content%' "; }
		
		/* Plugin option */
		$plugin_code = "class.liquid_source.posts.wc"; include(INCLUDE_PLUGIN_ROOT . "core.php");
		
		if(!Member::isLoggedin('A')) { $wc .= " and p_publisheddate <= '" . date('Y-m-d H:i:s') . "' "; }
		
	    $ret_arr = array();
	    $rs = mq("select pid from " . DB_TBL_POSTS . " $jc where p_type='post' $wc and p_posttype != 'inherit' order by $orderby $orderdir limit $limit");
	    while($rw = mfa($rs)) {
			$ret = new Post($rw['pid']);
			if(!empty($ret)) $ret_arr[] = $ret;
	    }
		global $curPosts;
		$curPosts = $ret_arr;
	    return $ret_arr;
	}
	public static function templates($arr=array()) {
		$wc = "";
		$orderdir = "asc";
		$orderby = "pid";
		
	    extract($arr);
	    if(isset($num)) { $limit = $num; } else { $limit = " 0,1000"; }
		if(!isset($active)) { $wc .= " and p_active='1' "; } else { $wc .= " and p_active='$active' "; }
		if(!isset($state)) { $wc .= " and p_posttype='published' "; } else {
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
			if(!empty($ret)) $ret_arr[] = $ret;
	    }
	    return $ret_arr;
	}
	public static function pages($arr=array()) {
		$wc = "";
		$orderby = "pgid";
		$orderdir = "asc";
	    extract($arr);
	    if(isset($num)) { $limit = $num; } else { $limit = " 0,1000"; }
		if(!isset($active)) { $wc .= " and pg_active='1' "; } else { $wc .= " and pg_active='$active' "; }
		if(!isset($state)) { $wc .= " and pg_posttype='published' "; } else {
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
	    $rs = mq("select pgid from " . DB_TBL_PAGES . " where isAdmin=0 $wc and pg_posttype != 'inherit' order by $orderby");
	    while($rw = mfa($rs)) {
			$ret = new Page('',$rw['pgid']);
			if(!empty($ret)) $ret_arr[] = $ret;
	    }
	    return $ret_arr;
	}
	public static function members($arr=array()) {
		$wc = "";
		$orderby = "mid";
		$orderdir = "asc";
		extract($arr);
	    if(isset($num)) { $limit = $num; } else { $limit = " 0,1000"; }
		if(!isset($m_type)) { $m_type = "U"; }
		if(!isset($active)) { $wc .= " and m_active='1' "; } else { $wc .= " and m_active='$active' "; }
		
		/* Plugin option */
		$plugin_code = "class.liquid_source.members.wc"; include(INCLUDE_PLUGIN_ROOT . "core.php");
		
	    $ret_arr = array();
	    $rs = mq("select mid from " . DB_TBL_MEMBERS . " where m_type='$m_type' $wc order by $orderby $orderdir limit $limit");
	    while($rw = mfa($rs)) {
			$ret = new Member($rw['mid']);
			if(!empty($ret)) $ret_arr[] = $ret;
	    }
	    return $ret_arr;
	}
	public static function media($arr=array()) {
		$wc = "";
		$jc = "";
		$orderby = "mdid";
		$orderdir = "asc";
		extract($arr);
	    if(isset($num)) { $limit = $num; } else { $limit = " 0,1000"; }
		if(!isset($active)) { $wc .= " and md_active='1' "; } else { $wc .= " and md_active='$active' "; }
		if(isset($category_id)) {
	    	if(!array($category_id)) $category_id = array($category_id);
			foreach($category_id as $val) $wc .= " and mdid in (select uid from " . DB_TBL_CATEGORY_LINK . " where cid='$val' and l_type='media') ";
	    }
		
		/* Plugin option */
		$plugin_code = "class.liquid_source.media.wc"; include(INCLUDE_PLUGIN_ROOT . "core.php");
		$ret_arr = array();
	    $rs = mq("select distinct " . DB_TBL_MEDIA . ".mdid from " . DB_TBL_MEDIA . " $jc where 1=1 $wc order by $orderby $orderdir limit $limit");
	    while($rw = mfa($rs)) {
			$ret = new Media($rw['mdid']);
			if(!empty($ret)) $ret_arr[] = $ret;
	    }
	    return $ret_arr;
	}
	public static function links($arr=array()) {
		$orderdir = "desc";
		$orderby = "lid";
		extract($arr);
	    if(isset($num)) { $limit = $num; } else { $limit = " 0,1000"; }
		
		/* Plugin option */
		$plugin_code = "class.liquid_source.links.wc"; include(INCLUDE_PLUGIN_ROOT . "core.php");
		
		$ret_arr = array();
	    $rs = mq("select lid from " . DB_TBL_LINKS . " order by $orderby $orderdir limit $limit");
	    while($rw = mfa($rs)) {
			$ret = new Link($rw['lid']);
			if(!empty($ret)) $ret_arr[] = $ret;
	    }
	    return $ret_arr;
	}
	public static function categories($arr=array()) {
		$wc = "";
		$orderby = "cid";
		$orderdir = "asc";
		extract($arr);
	    if(isset($num)) { $limit = $num; } else { $limit = " 0,1000"; }
		if(!isset($parent)) { $parent = "0"; }
		if(!isset($type)) { $type = "post"; }
		$wc = " and c_type='$type' ";
		
		/* Plugin option */
		$plugin_code = "class.liquid_source.categories.wc"; include(INCLUDE_PLUGIN_ROOT . "core.php");
		
		$ret_arr = array();
		$rs = mq("select cid from " . DB_TBL_CATEGORIES . " where c_parent=$parent $wc order by $orderby $orderdir limit $limit");
		while($rw = mfa($rs)) {
			$ret = new Category($rw['cid']);
			if(!empty($ret)) $ret_arr[] = $ret;
		}
	    return $ret_arr;
	}
}
?>