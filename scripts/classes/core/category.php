<?php
class Category {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($cid=NULL) {
		if($cid != NULL) {
			$rs = mq("select * from " . DB_TBL_CATEGORIES . " where cid='$cid'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				$this->data['id'] = $rw['cid'];
				$this->data['name'] = stripslashes($rw['c_name']);
				$this->data['parent'] = $rw['c_parent'];
				$this->data['type'] = $rw['c_type'];
				
				$rs = mq("select * from " . DB_TBL_CATEGORIES . " where c_parent='" . $this->data['id'] . "'");
				while($rw = mfa($rs)) {
					$children[] = new Category($rw['cid']);
				}
				if(sizeof($children) > 0) $this->data['children'] = $children;
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
        if ($arg == "cid") { return; }
		
        if (isset($this->data[$arg])) {
        	$val = mres($val);
        	$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_CATEGORIES . "' AND COLUMN_NAME = '$arg'");
            $this->data[$arg] = $val;
			if(mnr($rs) > 0) {
				$rs = mq("update " . DB_TBL_CATEGORIES . " set $arg='$val' where cid='" . $this->data['id'] . "'");
			}
        }
    }
	
	public function updateCategory($post_array,$c_type='post') {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		$cid = $this->data['id'];
		
		
	    if($c_parent == "") { $c_parent = '0'; }
	    
	    if($cid != "") {
	        $rs = mq("update " . DB_TBL_CATEGORIES . " set c_name='$c_name', c_parent='$c_parent', c_type='$c_type' where cid='$cid'");
	    } else {
	        $rs = mq("insert into " . DB_TBL_CATEGORIES . " (c_name,c_type,c_parent) values ('$c_name','$c_type','$c_parent')");
			$cid = miid();
	    }
		$_SESSION['_mtype'] = "S";
		$_SESSION['_msg'] = "newcategory";
		return $cid;
	}
	public function deleteCategory() {
		$cid = $this->data['id'];
		if($cid > 0) {
            $rs = mq("delete from " . DB_TBL_CATEGORY_LINK . " where cid='$cid'");
            $rs = mq("update " . DB_TBL_CATEGORIES . " set c_parent='0' where c_parent='$cid'");
            $rs = mq("delete from " . DB_TBL_CATEGORIES . " where cid='$cid'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedcategory";
		}
	}
}
?>