<?php
class Post {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($pid=NULL,$p_slug=NULL) {
		if($p_slug != NULL) { $wc = "p_slug='$p_slug' "; }
		if($pid != NULL) { $wc = "pid='$pid' "; }
		if($wc != "") { 
			$rs = mq("select * from " . DB_TBL_POSTS . " where $wc");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				$this->data['pid'] = $rw['pid'];
				$this->data['p_type'] = $rw['p_type'];
				$this->data['p_title'] = stripslashes($rw['p_title']);
				$this->data['p_slug'] = $rw['p_slug'];
				$this->data['p_createdate'] = $rw['p_createdate'];
				$this->data['p_updatedate'] = $rw['p_updatedate'];
				$this->data['p_content'] = stripslashes($rw['p_content']);
				$this->data['p_active'] = $rw['p_active'];
			}
		}
	}
	public function __get($arg) {
        if (isset($this->data[$arg])) {
            return $this->data[$arg];
        }
    }
    public function __set($arg, $val) {
        if ($arg == "pid") { return; }
		
        if (isset($this->data[$arg])) {
            $this->data[$arg] = $val;
        	$val = mres($val);
			try {
				$rs = mq("update " . DB_TBL_POSTS . " set $arg='$val' where pid='" . $this->data['pid'] . "'");
			} catch(Exception $e) { }
        }
    }
	
	public function updatePost($post_array,$p_type='post') {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		$datetime = date("Y-m-d H:i:s");
		
	    if($p_slug != "") {  $sluggy = $p_slug; } else { $sluggy = $p_title; }
	    if($sluggy != "") { $p_slug = strToSlug($sluggy,$p_type,$this->data['pid']); }
		
	    if($this->data['pid'] != "") {
	    	$pid = $this->data['pid'];
	    	$u_arr = array('p_title','p_content','p_slug');
			foreach($u_arr as $val) {
				if($$val != NULL) { $uc_x .= $val . "='" . $$val . "', "; }
			}
	        $rs = mq("update " . DB_TBL_POSTS . " set $uc_x p_updatedate='$datetime' where pid='$pid'");
		} else {
	        $rs = mq("insert into " . DB_TBL_POSTS . " (p_title,p_content,p_slug,p_type,p_updatedate) values ('$p_title','$p_content','$p_slug','$p_type','$datetime')");
	        $pid = miid();
			$this->data['pid'] = $pid;
		}
		$this->data['p_type'] = $p_type;
		
		if($cid != "") {
			updateCategoryLink($cid,$pid,$p_type);
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
	function getCategoryName() {
		if($this->data['pid'] != "") {
			return getCategoryName($this->data['pid']);
		}
	}
	function getCid() {
		if($this->data['pid'] != "") {
			return getCategoryId($this->data['pid']);
		}
	}
	public function getMetaDataArray() {
		if($this->data['pid'] != "") {
			return getMetaData($this->data['pid'],$this->p_type);
		}
		return array();
	}
	public function insertMetaData($a,$v,$ui) {
		insertMetaData($a,$v,$this->data['pid'],$this->p_type,$ui);
	}
	public function deletePost() {
		if($this->data['pid'] != NULL) {
			$rs = mq("update " . DB_TBL_POSTS . " set p_active='0' where pid='" . $this->data['pid'] . "'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedpost";
		}
	}
}
?>