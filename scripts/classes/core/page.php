<?php
class Page {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($pg_slug=NULL,$pgid=NULL) {
		if($pg_slug != NULL) { $wc = "pg_slug='$pg_slug' "; }
		if($pgid != NULL) { $wc = "pgid='$pgid' "; }
		if($wc != "") { 
			if(!Member::isLoggedin('A')) { $wc .= " and pg_publisheddate <= '" . date('Y-m-d H:i:s') . "' "; }
			$rs = mq("select * from " . DB_TBL_PAGES . " where $wc and pg_posttype != 'inherit'");
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
				$this->data['publisheddate'] = $rw['pg_publisheddate'];
				$this->data['type'] = $rw['pg_type'];
				$this->data['posttype'] = $rw['pg_posttype'];
				$this->data['content'] = stripslashes($rw['pg_content']);
				$this->data['active'] = $rw['pg_active'];
				if($this->data['type'] == "bs") { $pg_type_eng = "Bespoke"; } else { $pg_type_eng = "Text"; }
				$this->data['type_eng'] = $pg_type_eng;
			}
		}
	}
	public function updatePage($post_array) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		$datetime = date("Y-m-d H:i:s");
		
		if($this->data['isdefault'] == '1') {
			$pg_slug = $this->data['slug'];
		}
		else {
		    if($pg_slug != "") { $sluggy = $pg_slug; } else { $sluggy = $pg_title; }
			if($sluggy != "") { $pg_slug = strToSlug($sluggy,'page',$this->data['id']); }
		}
	    
	    if($this->data['id'] != "") {
	    	$pgid = $this->data['id'];
	    	
			if($sluggy != $this->data['slug']) {
				$rs = mq("update " . DB_TBL_PAGES . " set pg_slug='$sluggy' where pg_parent='$pgid'");
			}
			
	    	$rs = mq("insert into " . DB_TBL_PAGES . " (pg_meta_title,pg_slug,pg_meta_description,pg_meta_keywords,pg_type,pg_content,pg_publisheddate,isAdmin,pg_active,pg_parent,pg_posttype,pg_origposttype) values (
	    	'" . $this->data['meta_title'] . "',
	    	'" . $pg_slug . "',
	    	'" . $this->data['meta_description'] . "',
	    	'" . $this->data['meta_keywords'] . "',
	    	'" . $this->data['type'] . "',
	    	'" . $this->data['content'] . "',
	    	'" . $this->data['publisheddate'] . "',
	    	'0',
	    	'0',
	    	'$pgid',
	    	'inherit',
	    	'" . $this->data['posttype'] . "'
			)");
			
			
			
	    	$u_arr = array('pg_meta_title','pg_slug','pg_meta_description','pg_meta_keywords','pg_type','pg_content','pg_posttype');
			foreach($u_arr as $val) {
				if($$val != NULL) { $uc_x .= $val . "='" . $$val . "', "; }
			}
			if($uc_x != "") $uc_x = substr($uc_x,0,-2);
			
			if($pg_posttype == "published") { 
				if($pg_publisheddate != $this->data['publisheddate']) {
					$pub_datetime = date("Y-m-d H:i:s",strtotime($pg_publisheddate));
				} else {
					$pub_datetime = $this->data['publisheddate'];
				}
				$uc_x .= ", pg_publisheddate='$pub_datetime' ";
			}
			
			$rs = mq("update " . DB_TBL_PAGES . " set $uc_x, pg_createdate='$datetime' where pgid='$pgid'");
	    } else {
	    	if($pg_posttype == "published") {
				if($pg_publisheddate != "") {
					$pubDate = date("Y-m-d H:i:s",strtotime($pg_publisheddate));
				} else {
					$pubDate = $datetime;
				}
			}
	    	$rs = mq("insert into " . DB_TBL_PAGES . " (pg_meta_title,pg_slug,pg_meta_description,pg_meta_keywords,pg_type,pg_content,pg_publisheddate,isAdmin,pg_active,pg_posttype) values ('$pg_meta_title','$pg_slug','$pg_meta_description','$pg_meta_keywords','$pg_type','$pg_content','$pubDate','0','1','$pg_posttype')");
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
        	$val = mres($val);
        	$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_PAGES . "' AND COLUMN_NAME = '$arg'");
            $this->data[$arg] = $val;
			if(mnr($rs) > 0) {
				$rs = mq("update " . DB_TBL_PAGES . " set $arg='$val' where pgid='" . $this->data['id'] . "'");
			}
        }
    }
	public function theShort($size=50) {
		return mb_substr($this->data['content'],0,$size);
	}
	public function updatedDate() {
		return $this->createDate;
	}
	public function publishedDate() {
		$rw = mfa(mq("select min(pg_createdate) as pub_date where pg_slug='" . $this->data['slug'] . "'"));
		return $rw['pub_date'];
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
	public function removePage() {
		$rs = mq("delete from " . DB_TBL_PAGES . " where pgid='" . $this->data['id'] . "'");
	}
}
?>