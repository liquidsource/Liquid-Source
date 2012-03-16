<?php
class Link {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($lid=NULL) {
		if($lid != NULL) {
			$rs = mq("select * from " . DB_TBL_LINKS . " where lid='$lid'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				$this->data['id'] = $rw['lid'];
				$this->data['link'] = stripslashes($rw['l_link']);
				$this->data['text'] = stripslashes($rw['l_text']);
				$this->data['active'] = $rw['l_active'];
				
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
        if ($arg == "lid") { return; }
		
        if (isset($this->data[$arg])) {
            $this->data[$arg] = $val;
        	$val = mres($val);
			try {
				$rs = mq("update " . DB_TBL_LINKS . " set $arg='$val' where lid='" . $this->data['id'] . "'");
			} catch(Exception $e) { }
        }
    }
	
	public function updateLink($post_array) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
	    
		$lid = $this->data['id'];
	    if($lid != "") {
	        $rs = mq("update " . DB_TBL_LINKS . " set l_text='$l_text', l_link='$l_link' where lid='$lid'");
	    } else {
	        $rs = mq("insert into " . DB_TBL_LINKS . " (l_text,l_link,l_active) values ('$l_text','$l_link','1')");
			$lid = miid();
			$this->data['id'] = $lid;
	    }
		
		if(sizeof($post_array['cid']) > 0) {
			updateCategoryLink($post_array['cid'],$lid,'link');
		}
	    
		$i=0;
		foreach($post_array as $arg => $val) {
			if(substr($arg,0,6) == "md_arg") {
				$i++;
        		$this->insertMetaData($post_array['md_arg_' . $i],$post_array['md_val_' . $i],$i);
			}
		}
		
		$_SESSION['_mtype'] = "S";
		$_SESSION['_msg'] = "newlink";
		return $lid;
	}
	
	public function deleteLink() {
		$lid = $this->data['id'];
		if($lid > 0) {
	        $rs = mq("delete from " . DB_TBL_LINKS . " where lid='$lid'");
	        $rs = mq("delete from " . DB_TBL_CATEGORY_LINK . " where uid='$lid'");
	        $rs = mq("delete from " . DB_TBL_META_DATA . " where uid='$lid' and md_type='link'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedlink";
		}
	}
	
	public function categories() {
		if($this->data['id'] != "") {
			return getCategoryArray($this->data['id'],'link');
		}
	}
	public function categoryNames() {
		return getCategoryNames($this->data['id'],'link',',');
	}
	public function inCategory($catids) {
		return inCategory($catids,$this->id,'link');
	}
	public function getCid() {
		if($this->data['id'] != "") {
			return getCategoryId($this->data['id'],'link');
		}
	}
	public function metaData() {
		if($this->data['id'] != "") {
			return getMetaData($this->data['id'],'link');
		}
		return array();
	}
	
	public function insertMetaData($a,$v,$ui) {
		insertMetaData($a,$v,$this->data['id'],'link',$ui);
	}
}
?>