<?php
class Post {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($p_slug=NULL,$pid=NULL) {
		if($p_slug != NULL) { $wc = "p_slug='$p_slug' "; }
		if($pid != NULL) { $wc = "pid='$pid' "; }
		if($wc != "") {
			$rs = mq("select * from " . DB_TBL_POSTS . " where $wc and p_posttype != 'inherit'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				$this->data['id'] = $rw['pid'];
				$this->data['title'] = stripslashes($rw['p_title']);
				$this->data['slug'] = $rw['p_slug'];
				$this->data['createdate'] = $rw['p_createdate'];
				$this->data['content'] = stripslashes($rw['p_content']);
				$this->data['active'] = $rw['p_active'];
				$this->data['type'] = $rw['p_type'];
				$this->data['posttype'] = $rw['p_posttype'];
				$this->data['lang'] = $rw['p_lang'];
			}
		}
	}
	public function updatePost($post_array,$p_type='post') {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		$datetime = date("Y-m-d H:i:s");
		
	    if($this->data['slug'] != "") {  $sluggy = $this->data['slug']; } else { $sluggy = $p_title; }
	    if($sluggy != "") { $p_slug = strToSlug($sluggy,$this->data['type'],$this->data['id']); }
		
	    if($this->data['id'] != "") {
	    	$pid = $this->data['id'];
			
	    	$rs = mq("insert into " . DB_TBL_POSTS . " (p_title,p_content,p_slug,p_type,p_createdate,p_active,p_posttype,p_parent,p_origposttype) values (
	    	'" . $this->data['title'] . "',
	    	'" . $this->data['content'] . "',
	    	'" . $p_slug . "',
	    	'" . $this->data['type'] . "',
	    	'" . $this->data['createdate'] . "',
	    	'0',
	    	'inherit',
	    	'$pid',
	    	'" . $this->data['posttype'] . "'
	    	)");
			
	    	$u_arr = array('p_title','p_content','p_slug');
			foreach($u_arr as $val) {
				if($$val != NULL) { $uc_x .= $val . "='" . $$val . "', "; }
			}
			if($uc_x != "") $uc_x = substr($uc_x,0,-2);
	        $rs = mq("update " . DB_TBL_POSTS . " set $uc_x, p_createdate='$datetime' where pid='$pid'");
			
		} else {
	        $rs = mq("insert into " . DB_TBL_POSTS . " (p_title,p_content,p_slug,p_type,p_createdate) values ('$p_title','$p_content','$p_slug','$p_type','$datetime')");
	        $pid = miid();
			$this->data['id'] = $pid;
		}
		$this->data['type'] = $p_type;
		
		if(sizeof($post_array['cid']) > 0) {
			updateCategoryLink($post_array['cid'],$pid,$p_type);
		}
		
		$i=0;
		foreach($post_array as $arg => $val) {
			if(substr($arg,0,6) == "md_arg") {
				$i++;
        		$this->insertMetaData($post_array['md_arg_' . $i],$post_array['md_val_' . $i],$i);
			}
		}
		$_SESSION['_mtype'] = "S";
		$_SESSION['_msg'] = "newpost";
		return $pid;
	}
	public function __get($arg) {
		$m = "get_$arg";
		if(method_exists($this, $m)) return $this->$m();
        if (isset($this->data[$arg])) {
            return $this->data[$arg];
        }
    }
    public function __set($arg, $val) {
        if ($arg == "pid") { return; }
		
        if (isset($this->data[$arg])) {
        	$val = mres($val);
        	$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_POSTS . "' AND COLUMN_NAME = '$arg'");
            $this->data[$arg] = $val;
			if(mnr($rs) > 0) {
				$rs = mq("update " . DB_TBL_POSTS . " set $arg='$val' where pid='" . $this->data['id'] . "'");
			}
        }
    }
	
	
	public function categories() {
		if($this->data['id'] != "") {
			return getCategoryArray($this->data['id'],'post');
		}
	}
	public function categoryNames($seperator=",") {
		return getCategoryNames($this->data['id'],'post',$seperator);
	}
	public function inCategory($catids) {
		return inCategory($catids,$this->id,'post');
	}
	public function theShort($size=50) {
		return mb_substr($this->data['content'],0,$size);
	}
	public function updateddDate() {
		return $this->createDate;
	}
	public function publishedDate() {
		$rw = mfa(mq("select min(p_createdate) as pub_date where p_slug='" . $this->data['slug'] . "'"));
		return $rw['pub_date'];
	}
	public function metaData() {
		if($this->data['id'] != "") {
			return getMetaData($this->data['id'],$this->data['type']);
		}
		return array();
	}
	public function insertMetaData($a,$v,$ui) {
		insertMetaData($a,$v,$this->data['id'],$this->data['type'],$ui);
	}
	
	public function deletePost() {
		if($this->data['id'] != NULL) {
			$rs = mq("update " . DB_TBL_POSTS . " set p_active='0' where pid='" . $this->data['id'] . "'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedpost";
		}
	}
	
	public function nextPost($arr=array()) {
		extract($arr);
		if($category_id != NULL) {
			
		}
		if($date != NULL) {
			
		}
	}
}
?>