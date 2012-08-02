<?php
class Page {
    protected $data=array();
	
	/********************/
	/* PUBLIC FUNCTIONS */
	/********************/
	public function __construct($pg_slug=NULL,$pgid=NULL) {
		$this->data['pgid'] = $pgid;
		$pluginWClause = false;
		$wc = "";
		/* Plugin option */
		$plugin_code = "class.page.construct.wc";
		include(INCLUDE_PLUGIN_ROOT . "core.php");
		if(!$pluginWClause) {
			if($pg_slug != NULL) { $wc = "pg_slug='$pg_slug' "; }
			if($pgid != NULL) { $wc = "pgid='$pgid' "; }
		}
		
		if($wc != "") { 
			if(!Member::isLoggedin('A')) { $wc .= " and pg_publisheddate <= '" . date('Y-m-d H:i:s') . "' "; }
			$rs = mq("select * from " . DB_TBL_PAGES . " where $wc and pg_posttype != 'inherit'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				foreach($rw as $arg=>$val) {
					$this->data[$arg] = stripslashes($val);
				}
				if($this->data['pg_type'] == "bs") { $pg_type_eng = "Bespoke"; } else { $pg_type_eng = "Text"; }
				$this->data['pg_type_eng'] = $pg_type_eng;
				
				/* Plugin option */
				$plugin_code = "class.page.construct.setup";
				include(INCLUDE_PLUGIN_ROOT . "core.php");
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
        if($arg == "pgid") { return; }
		
        $val = mres($val);
    	$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_PAGES . "' AND COLUMN_NAME = '$arg'");
		if(mnr($rs) > 0) {
			$rs = mq("update " . DB_TBL_PAGES . " set $arg='$val' where pgid='" . $this->data['pgid'] . "'");
		}
		$this->data[$arg] = $val;
    }
	
	/**************************/
	/* PUBLIC UPDATE FUNCTION */
	/**************************/
	public function updatePage($post_array) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		
		if(isset($this->data['isDefault']) && $this->data['isDefault'] == '1') {
			$sluggy = $this->data['pg_slug'];
		}
		else {
		    if($pg_slug != "") { $sluggy = $pg_slug; } else { $sluggy = $pg_meta_title; }
			if($sluggy != "") { $pg_slug = strToSlug($sluggy,'page',$this->data['pgid']); }  else { $pg_slug = "temp"; }
		}
		$post_array['pg_slug'] = $pg_slug;
	    
	    if($this->data['pgid'] != "") {
	    	$pgid = $this->data['pgid'];
	    	
			if($sluggy != $this->data['pg_slug']) {
				$rs = mq("update " . DB_TBL_PAGES . " set pg_slug='$sluggy' where pg_parent='$pgid'");
			}
			
	    	$rs = mq("insert into " . DB_TBL_PAGES . " (pg_meta_title,pg_slug,pg_meta_description,pg_meta_keywords,pg_type,pg_content,pg_publisheddate,isAdmin,pg_active,pg_parent,pg_posttype,pg_origposttype) values (
	    	'" . $this->data['pg_meta_title'] . "',
	    	'" . $pg_slug . "',
	    	'" . $this->data['pg_meta_description'] . "',
	    	'" . $this->data['pg_meta_keywords'] . "',
	    	'" . $this->data['pg_type'] . "',
	    	'" . $this->data['pg_content'] . "',
	    	'" . $this->data['pg_publisheddate'] . "',
	    	'0',
	    	'0',
	    	'$pgid',
	    	'inherit',
	    	'" . $this->data['pg_posttype'] . "'
			)");
			$pgid_n = miid();
			
			/* Plugin option */
			$plugin_code = "class.page.update.extra";
			include(INCLUDE_PLUGIN_ROOT . "core.php");
			
			if($pg_posttype == "published") { 
				if($pg_publisheddate != $this->data['pg_publisheddate']) {
					$pub_datetime = date("Y-m-d H:i:s",strtotime($pg_publisheddate));
				} else {
					$pub_datetime = $this->data['pg_publisheddate'];
				}
				$post_array['pg_publisheddate'] = $pub_datetime;
				$rs = mq("update " . DB_TBL_PAGES . " set pg_createdate='" . DB_SAFE_DATETIME ."' where pgid='$pgid'");
			}
			foreach($post_array as $arg => $val) {
				$this->$arg = $val;
			}
			
			/* Plugin option */
			$plugin_code = "class.page.update.old.return";
			include(INCLUDE_PLUGIN_ROOT . "core.php");
			$_SESSION['_msg'] = "updatedpage";
	    } else {
	    	if($pg_posttype == "published") {
				if($pg_publisheddate != "") {
					$pubDate = date("Y-m-d H:i:s",strtotime($pg_publisheddate));
				} else {
					$pubDate = DB_SAFE_DATETIME;
				}
				$post_array['pg_publisheddate'] = $pubDate;
			}
			if($pg_type == "") $post_array['pg_type'] = "bs";
			
			/* Plugin option */
			$plugin_code = "class.page.update.new.setiso";
			include(INCLUDE_PLUGIN_ROOT . "core.php");
			
	    	$rs = mq("insert into " . DB_TBL_PAGES . " (isAdmin,pg_active) values ('0','1')");
	        $pgid = miid();
			$this->data['pgid'] = $pgid;
			
			foreach($post_array as $arg => $val) {
				$this->$arg = $val;
			}
			
			/* Plugin option */
			$plugin_code = "class.page.update.new.return";
			include(INCLUDE_PLUGIN_ROOT . "core.php");
			$_SESSION['_msg'] = "newpage";
	    }
		
		$i=0;
		foreach($post_array as $arg => $val) {
			if(substr($arg,0,6) == "md_arg") {
				$i++;
        		$this->insertMetaData($post_array['md_arg_' . $i],$post_array['md_val_' . $i],$i);
			}
		}

		$_SESSION['_mtype'] = "S";
		return $pgid;
	}
	
	/************************/
	/* GET HELPER FUNCTIONS */
	/************************/
	public function theShort($size=50) {
		return mb_substr($this->data['pg_content'],0,$size);
	}
	public function updatedDate() {
		return $this->data['pg_createDate'];
	}
	public function publishedDate() {
		$rw = mfa(mq("select min(pg_createdate) as pub_date where pg_slug='" . $this->data['pg_slug'] . "'"));
		return $rw['pub_date'];
	}
	
	/***********************/
	/* META DATA FUNCTIONS */
	/***********************/
	public function metaData() {
		if(isset($this->data['pgid'])) {
			return getMetaData($this->data['pgid'],'page');
		}
		return array();
	}
	public function insertMetaData($a,$v,$ui) {
		insertMetaData($a,$v,$this->data['pgid'],'page',$ui);
	}
	
	/*****************************/
	/* PAGE ALTERATION FUNCTIONS */
	/*****************************/
	public function deletePage() {
		if(isset($this->data['pgid'])) {
			$rs = mq("update " . DB_TBL_PAGES . " set pg_active='0' where pgid='" . $this->data['pgid'] . "'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedpage";
		}
	}
	public function removePage() {
		if(isset($this->data['pgid'])) {
			if($this->data['pg_type'] == "bs") { unlink("../../modules/" . $this->data['pg_slug'] . ".php"); }
			$rs = mq("delete from " . DB_TBL_PAGES . " where pgid='" . $this->data['pgid'] . "'");
		}
	}
	public function restorePage() {
		if(isset($this->data['pgid'])) {
			$rs = mq("update " . DB_TBL_PAGES . " set pg_active='1' where pgid='" . $this->data['pgid'] . "'");
		}
	}


	/*********************/
	/* STATIC FUNCTIONS */
	/*********************/
	public static function emptyTrash() {
		$rs = mq("select pgid from " . DB_TBL_PAGES . " where pg_active='0'");
		while($rw = mfa($rs)) {
			$page = new Page($rw['pgid']);
			$page->removePage();
		}
	}
	public static function numberTrashItems() {
		$rw = mfa(mq("select count(pgid) as n from " . DB_TBL_PAGES . " where pg_active='0' and pg_posttype != 'inherit'"));
		return $rw['n'];
	}
	public static function numberNonTrashItems() {
		$rw = mfa(mq("select count(pgid) as n from " . DB_TBL_PAGES . " where pg_active='1' and pg_posttype != 'inherit'"));
		return $rw['n'];
	}
}
?>