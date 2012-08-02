<?php
class JobAlert {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($jaid=NULL) {
		if($jaid != NULL) {
			$rs = mq("select * from " . DB_TBL_JOB_ALERT . " where jaid='$jaid'");
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
        if ($arg == "jaid") { return; }
		
		$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_JOB_ALERT . "' AND COLUMN_NAME = '$arg'");
		if(mnr($rs) > 0) {
			$rsu = mq("update " . DB_TBL_JOB_ALERT . " set $arg='$val' where jaid='" . $this->data['jaid'] . "'");
		}
        $this->data[$arg] = $val;
    }
	public function updateJobAlert($post_array) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
	    
		$jaid = $this->data['jaid'];
	    if($jaid == "") {
	    	if(!isset($ja_name)) { $ja_name = ""; }
	    	if(!isset($ja_datecreated)) { $ja_datecreated = DB_SAFE_DATETIME; }
			
	        $rsi = mq("insert into " . DB_TBL_JOB_ALERT . " (ja_name,ja_datecreated) values ('$ja_name','$ja_datecreated')");
			$jaid = miid();
			$this->data['jaid'] = $jaid;
			$_SESSION['_msg'] = "newjobalert";
	    } else {
			$_SESSION['_msg'] = "updatedjobalert";
	    }

	    foreach($post_array as $arg => $val) {
	    	$this->$arg = $val;
	    }
		
		$_SESSION['_mtype'] = "S";
		return $jaid;
	}
	public function deleteJobAlert() {
		if(isset($this->data['jaid'])) {
			$rsu = mq("delete from " . DB_TBL_JOB_ALERT . " where jaid='" . $this->data['jaid'] . "'");
			$_SESSION['_mtype'] = "W";
			$_SESSION['_msg'] = "removedjobalert";
		}
	}
	
	
}
?>