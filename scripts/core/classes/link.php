<?php
class Link {
    protected $data=array();
	
	/********************/
	/* PUBLIC FUNCTIONS */
	/********************/
	public function __construct($lid=NULL) {
		$this->data['lid'] = $lid;
		if($lid != NULL) {
			$rs = mq("select * from " . DB_TBL_LINKS . " where lid='$lid'");
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
        if($arg == "lid") { return; }
		
        if (isset($this->data[$arg])) {
        	$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_LINKS . "' AND COLUMN_NAME = '$arg'");
			if(mnr($rs) > 0) {
        		$val = mres($val);
				$rs = mq("update " . DB_TBL_LINKS . " set $arg='$val' where lid='" . $this->data['lid'] . "'");
			}
        }
        $this->data[$arg] = $val;
    }
	
	/**************************/
	/* PUBLIC UPDATE FUNCTION */
	/**************************/
	public function updateLink($post_array) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		
	    $lid = $this->data['lid'];
	    if($lid == "") {
	        $rs = mq("insert into " . DB_TBL_LINKS . " (l_text,l_link,l_active) values ('$l_text','$l_link','1')");
			$lid = miid();
			$this->data['lid'] = $lid;
			$_SESSION['_msg'] = "newlink";
	    } else {
	    	$_SESSION['_msg'] = "updatedlink";
	    }
		foreach($post_array as $arg => $val) {
			$this->$arg = $val;
		}
		
		if(isset($post_array['cid'])) updateCategoryLink($post_array['cid'],$lid,'link');
	    
		$i=0;
		foreach($post_array as $arg => $val) {
			if(substr($arg,0,6) == "md_arg") {
				$i++;
        		$this->insertMetaData($post_array['md_arg_' . $i],$post_array['md_val_' . $i],$i);
			}
		}
		
		$_SESSION['_mtype'] = "S";
		return $lid;
		
	}
	
	/*****************************/
	/* LINK ALTERATION FUNCTIONS */
	/*****************************/
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
	
	/***********************/
	/* META DATA FUNCTIONS */
	/***********************/
	public function getMetaData() {
		if(isset($this->data['lid'])) {
			return getMetaData($this->data['lid'],'link');
		}
		return array();
	}
	public function insertMetaData($a,$v,$ui) {
		insertMetaData($a,$v,$this->data['lid'],'link',$ui);
	}
	
	/***************************/
	/* LINK CATEGORY FUNCTIONS */
	/***************************/
	public function categoryArray() {
		if($this->data['lid'] != "") {
			return getCategoryArray($this->data['lid'],'link');
		}
	}
	public function categoryNames() {
		return getCategoryNames($this->data['lid'],'link',',');
	}
	public function inCategory($catids) {
		return inCategory($catids,$this->data['lid'],'link');
	}
}
?>