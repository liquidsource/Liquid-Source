<?php
class Link {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($lid=NULL) {
		if($lid != NULL) {
			$rs = mq("select * from " . DB_TBL_LINKS . " where lid='$lid'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				$this->data['lid'] = $rw['lid'];
				$this->data['l_link'] = stripslashes($rw['l_link']);
				$this->data['l_text'] = stripslashes($rw['l_text']);
				$this->data['l_active'] = $rw['l_active'];
				
			}
		}
	}
	public function __get($arg) {
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
				$rs = mq("update " . DB_TBL_LINKS . " set $arg='$val' where lid='" . $this->data['lid'] . "'");
			} catch(Exception $e) { }
        }
    }
	
	public function updateLink($post_array) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
	    
		$lid = $this->data['lid'];
	    if($lid != "") {
	        $rs = mq("update " . DB_TBL_LINKS . " set l_text='$l_text', l_link='$l_link' where lid='$lid'");
	    } else {
	        $rs = mq("insert into " . DB_TBL_LINKS . " (l_text,l_link,l_active) values ('$l_text','$l_link','1')");
			$lid = miid();
			$this->lid = $lid;
	    }
		
		updateCategoryLink($cid,$lid,'link');
	    
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
		$lid = $this->data['lid'];
		if($lid > 0) {
	        $rs = mq("delete from " . DB_TBL_LINKS . " where lid='$lid'");
	        $rs = mq("delete from " . DB_TBL_CATEGORY_LINK . " where uid='$lid'");
	        $rs = mq("delete from " . DB_TBL_META_DATA . " where uid='$lid' and md_type='link'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedlink";
		}
	}
	public function getCategoryName() {
		if($this->lid != "") {
			return getCategoryName($this->data['lid'],'link');
		}
	}
	public function getCid() {
		if($this->lid != "") {
			return getCategoryId($this->data['lid'],'link');
		}
	}
	public function getMetaDataArray() {
		if($this->lid != "") {
			return getMetaData($this->data['lid'],'link');
		}
		return array();
	}
	
	public function insertMetaData($a,$v,$ui) {
		insertMetaData($a,$v,$this->data['lid'],'link',$ui);
	}
}
?>