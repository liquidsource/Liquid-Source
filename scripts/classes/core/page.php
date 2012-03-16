<?php
class Page {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($pg_slug=NULL,$pgid=NULL) {
		if($pg_slug != NULL) { $wc = "pg_slug='$pg_slug' "; }
		if($pgid != NULL) { $wc = "pgid='$pgid' "; }
		if($wc != "") { 
			$rs = mq("select * from " . DB_TBL_PAGES . " where $wc");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				$this->data['id'] = $rw['pgid'];
				$this->data['meta_title'] = stripslashes($rw['pg_meta_title']);
				$this->data['title'] = $this->meta_title;
				$this->data['slug'] = $rw['pg_slug'];
				$this->data['meta_description'] = stripslashes($rw['pg_meta_description']);
				$this->data['meta_keywords'] = stripslashes($rw['pg_meta_keywords']);
				$this->data['isdefault'] = $rw['isDefault'];
				$this->data['isadmin'] = $rw['isAdmin'];
				$this->data['createdate'] = $rw['pg_createdate'];
				$this->data['type'] = $rw['pg_type'];
				$this->data['updatedate'] = $rw['pg_updatedate'];
				$this->data['content'] = stripslashes($rw['pg_content']);
				$this->data['active'] = $rw['pg_active'];
				if($this->data['type'] == "bs") { $pg_type_eng = "Bespoke"; } else { $pg_type_eng = "Text"; }
				$this->data['type_eng'] = $pg_type_eng;
			}
		}
	}
	public function updatePage($post_array) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		$datetime = date("Y:m:d H:i:s");
		
		if($this->data['isdefault'] == '1') {
			$pg_slug = $this->data['slug'];
		}
		else {
		    if($pg_slug != "") { $sluggy = $pg_slug; } else { $sluggy = $pg_title; }
			if($sluggy != "") { $pg_slug = strToSlug($sluggy,'page',$this->data['id']); }
		}
	    
	    if($this->data['id'] != "") {
	    	$pgid = $this->data['id'];
	    	$u_arr = array('pg_meta_title','pg_slug','pg_meta_description','pg_meta_keywords','pg_type','pg_content');
			foreach($u_arr as $val) {
				if($$val != NULL) { $uc_x .= $val . "='" . $$val . "', "; }
			}
			$rs = mq("update " . DB_TBL_PAGES . " set $uc_x pg_updatedate='$datetime' where pgid='$pgid'");
	    } else {
	    	$rs = mq("insert into " . DB_TBL_PAGES . " (pg_meta_title,pg_slug,pg_meta_description,pg_meta_keywords,pg_type,pg_content,isAdmin,pg_active,pg_updatedate) values ('$pg_meta_title','$pg_slug','$pg_meta_description','$pg_meta_keywords','$pg_type','$pg_content','0','1','$datetime')");
	        $pgid = miid();
			$this->data['id'] = $pgid;
	    }
		
		if($pg_type == "tx") {
			$i=0;
			foreach($post_array as $arg => $val) {
				if(substr($arg,0,6) == "md_arg") {
					$i++;
	        		$this->insertMetaData($post_array['md_arg_' . $i],$post_array['md_val_' . $i],$i);
				}
			}
		}

		$_SESSION['_mtype'] = "S";
		$_SESSION['_msg'] = "newpage";
		return $pgid;
	}
	public function __get($arg) {
		$m = "get_$arg";
		if(method_exists($this, $m)) return $this->$m();
		
        if (isset($this->data[$arg])) {
            return $this->data[$arg];
        }
    }
    public function __set($arg, $val) {
        if ($arg == "pgid") { return; }
		
        if (isset($this->data[$arg])) {
            $this->data[$arg] = $val;
        	$val = mres($val);
			try {
				$rs = mq("update " . DB_TBL_PAGES . " set $arg='$val' where pgid='" . $this->data['id'] . "'");
			} catch(Exception $e) { }
        }
    }
	
	public function theShort($size=50) {
		return mb_substr($this->data['content'],0,$size);
	}
	public function updatedDate() {
		return $this->updateDate;
	}
	public function publishedDate() {
		return $this->createDate;
	}
	
	
	
	public function metaData() {
		if($this->data['id'] != "") {
			return getMetaData($this->data['id'],'page');
		}
		return array();
	}
	public function insertMetaData($a,$v,$ui) {
		insertMetaData($a,$v,$this->data['id'],'page',$ui);
	}
	
	public function deletePage() {
		if($this->data['id'] != NULL) {
			$rs = mq("update " . DB_TBL_PAGES . " set pg_active='0' where pgid='" . $this->data['id'] . "'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedpage";
		}
	}
}
?>