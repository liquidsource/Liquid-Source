<?php
class Post {
    private $data=array();
	
	/********************/
	/* PUBLIC FUNCTIONS */
	/********************/
	public function __construct($pid=NULL) {
		$wc = "";
		if(isset($pid)) { $wc = "pid='$pid' "; }
		$this->data['pid'] = "";
		if($wc != "") {
			if(!Member::isLoggedin('A')) { $wc .= " and p_publisheddate <= '" . date('Y-m-d H:i:s') . "' "; }
			$rs = mq("select * from " . DB_TBL_POSTS . " where $wc and p_posttype != 'inherit'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				foreach($rw as $arg=>$val) {
					$this->data[$arg] = stripslashes($val);
				}
			}
		}
	}
	public function __get($arg) {
		$m = "get_$arg";
		if(method_exists($this, $m)) return $this->$m();
        if (isset($this->data[$arg])) {
            return $this->data[$arg];
        }
    }
    public function __set($arg, $val) {
        if($arg == "pid") { return; }
		
    	$val = mres($val);
    	$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_POSTS . "' AND COLUMN_NAME = '$arg'");
		if(mnr($rs) > 0) {
			$rs = mq("update " . DB_TBL_POSTS . " set $arg='$val' where pid='" . $this->data['pid'] . "'");
		}
		$this->data[$arg] = $val;
    }
	
	/**************************/
	/* PUBLIC UPDATE FUNCTION */
	/**************************/
	public function updatePost($post_array,$p_type='post') {
		$post_array['p_type'] = $p_type;
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		
	    if(isset($this->data['p_slug'])) {  $sluggy = $this->data['p_slug']; } else { $sluggy = $p_title; }
	    if($sluggy != "") { $p_slug = strToSlug($sluggy,$p_type,$this->data['pid']); } else { $p_slug = "temp"; }
		$post_array['p_slug'] = $p_slug;
		
	    if($this->data['pid'] != "") {
	    	$pid = $this->data['pid'];
			
	    	$rs = mq("insert into " . DB_TBL_POSTS . " (p_title,p_content,p_slug,p_type,p_createdate,p_publisheddate,p_active,p_posttype,p_parent,p_origposttype) values (
	    	'" . $this->data['p_title'] . "',
	    	'" . $this->data['p_content'] . "',
	    	'" . $p_slug . "',
	    	'" . $this->data['p_type'] . "',
	    	'" . $this->data['p_createdate'] . "',
	    	'" . $this->data['p_publisheddate'] . "',
	    	'0',
	    	'inherit',
	    	'$pid',
	    	'" . $this->data['p_posttype'] . "'
	    	)");
			
			if($p_posttype == "published") { 
				if(isset($p_publisheddate) && $p_publisheddate != $this->data['p_publisheddate']) {
					$pub_datetime = date("Y-m-d H:i:s",strtotime($p_publisheddate));
				} else {
					$pub_datetime = $this->data['p_publisheddate'];
				}
				$post_array['p_publisheddate'] = $pub_datetime;
	        	$rs = mq("update " . DB_TBL_POSTS . " set p_createdate='" . DB_SAFE_DATETIME ."' where pid='$pid'");
			}
			foreach($post_array as $arg => $val) {
				$this->$arg = $val;
			}
			$_SESSION['_msg'] = "updatedpost";
		} else {
			if($p_posttype == "published") {
				if(isset($p_publisheddate)) {
					$pub_datetime = date("Y-m-d H:i:s",strtotime($p_publisheddate));
				} else {
					$pub_datetime = DB_SAFE_DATETIME;
				}
				$post_array['p_publisheddate'] = $pub_datetime;
			}
	        $rs = mq("insert into " . DB_TBL_POSTS . " (p_createdate) values ('" . DB_SAFE_DATETIME . "')");
	        $pid = miid();
			$this->data['pid'] = $pid;
			
			foreach($post_array as $arg => $val) {
				$this->$arg = $val;
			}
			$_SESSION['_msg'] = "newpost";
		}
		
		if(isset($post_array['cid'])) updateCategoryLink($post_array['cid'],$pid,$p_type);
		
		$i=0;
		foreach($post_array as $arg => $val) {
			if(substr($arg,0,6) == "md_arg") {
				$i++;
        		$this->insertMetaData($post_array['md_arg_' . $i],$post_array['md_val_' . $i],$i);
			}
		}
		$_SESSION['_mtype'] = "S";
		return $pid;
	}
	
	/************************/
	/* GET HELPER FUNCTIONS */
	/************************/
	public function theShort($size=50) {
		return mb_substr($this->data['p_content'],0,$size);
	}
	public function updatedDate() {
		return $this->data['p_createDate'];
	}
	public function publishedDate() {
		return $this->data['p_publisheddate'];
	}
	
	/***************************/
	/* POST CATEGORY FUNCTIONS */
	/***************************/
	public function categoryArray() {
		if($this->data['pid'] != "") {
			return getCategoryArray($this->data['pid'],'post');
		}
	}
	public function categoryNames($seperator=",") {
		return getCategoryNames($this->data['pid'],'post',$seperator);
	}
	public function inCategory($catids) {
		return inCategory($catids,$this->data['pid'],'post');
	}
	
	/***********************/
	/* META DATA FUNCTIONS */
	/***********************/
	public function metaData() {
		if(isset($this->data['pid'])) {
			if(!isset($this->data['p_type'])) $this->data['p_type'] = "post";
			return getMetaData($this->data['pid'],$this->data['p_type']);
		}
		return array();
	}
	public function insertMetaData($a,$v,$ui) {
		insertMetaData($a,$v,$this->data['pid'],$this->data['p_type'],$ui);
	}
	
	/*************************/
	/* NEXT / PREV FUNCTIONS */
	/*************************/
	public function nextPost($arr=array()) {
		return $this->prevNext('asc',$arr);
	}
	public function prevPost($arr=array()) {
		return $this->prevNext('desc',$arr);
	}
	private function prevNext($ascdesc,$arr=array()) {
		extract($arr);
		if($category_id != NULL) {
			$i_cat = $category_id;
			$icats = explode(",",$i_cat);
			$xsql = " and pid in (select uid from " . DB_TBL_CATEGORY_LINK . " where l_type='post' and (";
			foreach($icats as $catid) {
				$xsql .= " cid='$catid' or ";
			}
			$xsql .= " 1=0))";
		}
		
		$curPostDate = $this->publishedDate();
		global $curPosts;
		if($curPosts != NULL) {
			$xsql .= " and pid in (";
			foreach($curPosts as $post) {
				$xsql .= $post->id . ",";
			}
			$xsql .= "0)";
		}
		
		$lowhigh = "<";
		if($ascdesc == "asc") { $lowhigh = ">"; }
		
		$rs = mq("select pid from " . DB_TBL_POSTS . " where p_publisheddate $lowhigh '$curPostDate' and p_posttype='published' and p_type='post' $xsql order by p_createdate " . $ascdesc . " limit 0,1");
		$rw = mfa($rs);
		$retPost = new Post($rw['pid']);
		
		return $retPost;		
	}
	
	/*****************************/
	/* POST ALTERATION FUNCTIONS */
	/*****************************/
	public function deletePost() {
		if(isset($this->data['pid'])) {
			$rs = mq("update " . DB_TBL_POSTS . " set p_active='0' where pid='" . $this->data['pid'] . "'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedpost";
		}
	}
}
?>