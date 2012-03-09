<?php
class Page {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($pgid=NULL,$pg_slug=NULL) {
		if($pg_slug != NULL) { $wc = "pg_slug='$pg_slug' "; }
		if($pgid != NULL) { $wc = "pgid='$pgid' "; }
		if($wc != "") { 
			$rs = mq("select * from " . DB_TBL_PAGES . " where $wc");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				$this->data['pgid'] = $rw['pgid'];
				$this->data['pg_meta_title'] = stripslashes($rw['pg_meta_title']);
				$this->data['pg_title'] = $this->pg_meta_title;
				$this->data['pg_slug'] = $rw['pg_slug'];
				$this->data['pg_meta_description'] = stripslashes($rw['pg_meta_description']);
				$this->data['pg_meta_keywords'] = stripslashes($rw['pg_meta_keywords']);
				$this->data['isdefault'] = $rw['isDefault'];
				$this->data['isadmin'] = $rw['isAdmin'];
				$this->data['pg_createdate'] = $rw['pg_createdate'];
				$this->data['pg_type'] = $rw['pg_type'];
				$this->data['pg_updatedate'] = $rw['pg_updatedate'];
				$this->data['pg_content'] = stripslashes($rw['pg_content']);
				$this->data['pg_active'] = $rw['pg_active'];
				if($this->data['pg_type'] == "bs") { $pg_type_eng = "Bespoke"; } else { $pg_type_eng = "Text"; }
				$this->data['pg_type_eng'] = $pg_type_eng;
			}
		}
	}
	public function __get($arg) {
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
				$rs = mq("update " . DB_TBL_PAGES . " set $arg='$val' where pgid='" . $this->data['pgid'] . "'");
			} catch(Exception $e) { }
        }
    }
	
	public function updatePage($post_array) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		$datetime = date("Y:m:d H:i:s");
		
		if($this->data['isdefault'] == '1') {
			$pg_slug = $this->data['pg_slug'];
		}
		else {
		    if($pg_slug != "") { $sluggy = $pg_slug; } else { $sluggy = $pg_title; }
			if($sluggy != "") { $pg_slug = strToSlug($sluggy,'page',$this->data['pgid']); }
		}
	    
	    if($this->data['pgid'] != "") {
	    	$pgid = $this->data['pgid'];
	    	$u_arr = array('pg_meta_title','pg_slug','pg_meta_description','pg_meta_keywords','pg_type','pg_content');
			foreach($u_arr as $val) {
				if($$val != NULL) { $uc_x .= $val . "='" . $$val . "', "; }
			}
	        $rs = mq("update " . DB_TBL_PAGES . " set $uc_x pg_updatedate='$datetime' where pgid='$pgid'");
	    } else {
	        $rs = mq("insert into " . DB_TBL_PAGES . " (pg_meta_title,pg_slug,pg_meta_description,pg_meta_keywords,pg_type,pg_content,isAdmin,pg_active,pg_updatedate) values ('$pg_meta_title','$pg_slug','$pg_meta_description','$pg_meta_keywords','$pg_type','$pg_content','0','1','$datetime')");
	        $pgid = miid();
			$this->data['pgid'] = $pgid;
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
	
	public function getMetaDataArray() {
		if($this->data['pgid'] != "") {
			return getMetaData($this->data['pgid'],'page');
		}
		return array();
	}
	public function deletePage() {
		if($this->data['pgid'] != NULL) {
			$rs = mq("update " . DB_TBL_PAGES . " set pg_active='0' where pgid='" . $this->data['pgid'] . "'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedpage";
		}
	}
	
	public function insertMetaData($a,$v,$ui) {
		insertMetaData($a,$v,$this->data['pgid'],'page',$ui);
	}
}
?>