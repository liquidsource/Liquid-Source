<?php
class Category {
    protected $data=array();
	
	/********************/
	/* PUBLIC FUNCTIONS */
	/********************/
	public function __construct($cid=NULL) {
		$this->data['cid'] = $cid;
		if($cid != NULL) {
			$rs = mq("select * from " . DB_TBL_CATEGORIES . " where cid='$cid'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				foreach($rw as $arg=>$val) {
					$this->data[$arg] = stripslashes($val);
				}
				
				$children = array();
				$rsi = mq("select * from " . DB_TBL_CATEGORIES . " where c_parent='" . $this->data['cid'] . "'");
				while($rwi = mfa($rsi)) {
					$children[] = new Category($rwi['cid']);
				}
				if(sizeof($children) > 0) $this->data['c_children'] = $children;
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
        if($arg == "cid") { return; }
		
        if (isset($this->data[$arg])) {
        	$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_CATEGORIES . "' AND COLUMN_NAME = '$arg'");
			if(mnr($rs) > 0) {
        		$val = mres($val);
				$rs = mq("update " . DB_TBL_CATEGORIES . " set $arg='$val' where cid='" . $this->data['cid'] . "'");
			}
        }
        $this->data[$arg] = $val;
    }
	
	/**************************/
	/* PUBLIC UPDATE FUNCTION */
	/**************************/
	public function updateCategory($post_array,$c_type='post') {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		
		$cid = $this->data['cid'];
	    if($cid == "") {
	    	if(!isset($c_parent)) $c_parent = '0';
			
	        $rs = mq("insert into " . DB_TBL_CATEGORIES . " (c_name,c_type,c_parent) values ('$c_name','$c_type','$c_parent')");
			$cid = miid();
			$this->data['cid'] = $cid;
			$_SESSION['_msg'] = "newcategory";
	    } else {
	    	$_SESSION['_msg'] = "updatedcategory";
	    }
		
		foreach($post_array as $arg => $val) {
			$this->$arg = $val;
		}
		$_SESSION['_mtype'] = "S";
		return $cid;
	}
	
	/*********************************/
	/* CATEGORY ALTERATION FUNCTIONS */
	/*********************************/
	public function deleteCategory() {
		$cid = $this->data['cid'];
		if($cid > 0) {
            $rsd = mq("delete from " . DB_TBL_CATEGORY_LINK . " where cid='$cid'");
            $rsd = mq("delete from " . DB_TBL_CATEGORIES . " where cid='$cid'");
            $rsu = mq("update " . DB_TBL_CATEGORIES . " set c_parent='0' where c_parent='$cid'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedcategory";
		}
	}
}
?>