<?php
class Company {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($cmid=NULL) {
		if($cmid != NULL) {
			$rs = mq("select * from " . DB_TBL_COMPANIES . " where cmid='$cmid'");
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
        if ($arg == "cmid") { return; }
		
		$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_COMPANIES . "' AND COLUMN_NAME = '$arg'");
		if(mnr($rs) > 0) {
			$rsu = mq("update " . DB_TBL_COMPANIES . " set $arg='$val' where cmid='" . $this->data['cmid'] . "'");
		}
        $this->data[$arg] = $val;
    }
	public function updateCompany($post_array) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
	    
		$cmid = $this->data['cmid'];
	    if($cmid == "") {
	    	if(!isset($cm_name)) { $cm_name = ""; }
	    	if(!isset($cm_createdate)) { $cm_createdate = DB_SAFE_DATETIME; }
			
	        $rsi = mq("insert into " . DB_TBL_COMPANIES . " (cm_name,cm_createdate) values ('$cm_name','$cm_createdate')");
			$cmid = miid();
			$this->data['cmid'] = $cmid;
			$_SESSION['_msg'] = "newcompany";
	    } else {
			$_SESSION['_msg'] = "updatedcompany";
	    }

	    foreach($post_array as $arg => $val) {
	    	$this->$arg = $val;
	    }
		
		$_SESSION['_mtype'] = "S";
		return $cmid;
	}
	public function deleteCompany() {
		if(isset($this->data['cmid'])) {
			$rsu = mq("update " . DB_TBL_COMPANIES . " set cm_active='0' where cmid='" . $this->data['cmid'] . "'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "deletedcompany";
		}
	}
	
	
	public static function getCompanies($arr) {
		$ret = array();
		$rs = mq("select cmid from " . DB_TBL_COMPANIES . " where cm_active='1' order by cm_name");
		while($rw = mfa($rs)) {
			$ret[] = new Company($rw['cmid']);
		}
		return $ret;
	}
}
?>