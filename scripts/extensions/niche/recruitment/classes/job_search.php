<?php
class JobSearch {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($scid=NULL,$arr=NULL) {
		if($scid != NULL) {
			$rs = mq("select * from " . DB_TBL_JOB_SEARCH . " where scid='$scid'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				foreach($rw as $arg=>$val) {
					$this->data[$arg] = stripslashes($val);
				}
			}
		}
		if($arr != NULL) {
			$scid = $this->updateJobSearch(array('sc_arr' => $arr));
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
        if ($arg == "scid") { return; }
		
		$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_JOB_SEARCH . "' AND COLUMN_NAME = '$arg'");
		if(mnr($rs) > 0) {
			if($arg == "sc_arr") {
				$val = json_encode($val);
			}
			$rsu = mq("update " . DB_TBL_JOB_SEARCH . " set $arg='$val' where scid='" . $this->data['scid'] . "'");
		}
        $this->data[$arg] = $val;
    }
	public function updateJobSearch($post_array) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
	    
		$scid = $this->data['scid'];
	    if($scid == "") {
	    	if(!isset($sc_datecreated)) { $sc_datecreated = DB_SAFE_DATETIME; }
			
	        $rsi = mq("insert into " . DB_TBL_JOB_SEARCH . " (sc_datecreated, sc_ip, sc_sessionid) values ('$sc_datecreated','" . getRealIpAddr() . "','" . session_id() . "')");
			$scid = miid();
			$this->data['scid'] = $scid;
	    }

	    foreach($post_array as $arg => $val) {
	    	$this->$arg = $val;
	    }
		return $scid;
	}
}
?>