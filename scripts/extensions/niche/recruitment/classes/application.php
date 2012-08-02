<?php
class Application {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($aid=NULL) {
		if($aid != NULL) {
			$rs = mq("select * from " . DB_TBL_APPLICATIONS . " where aid='$aid'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				foreach($rw as $arg=>$val) {
					$this->data[$arg] = stripslashes($val);
				}
				$this->data['job'] = new Job($rw['jid']);
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
		
		$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_APPLICATIONS . "' AND COLUMN_NAME = '$arg'");
		if(mnr($rs) > 0) {
			$rsu = mq("update " . DB_TBL_APPLICATIONS . " set $arg='$val' where aid='" . $this->data['aid'] . "'");
		}
        $this->data[$arg] = $val;
		if($arg == "jid") {
			$this->data['job'] = new Job($val);
		}
    }
	public function updateApplication($jid,$mid,$post_array) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
	    
		$aid = $this->data['aid'];
	    if($aid == "") {
	    	if(!isset($a_datetime)) { $a_datetime = DB_SAFE_DATETIME; }
			
	        $rsi = mq("insert into " . DB_TBL_APPLICATIONS . " (jid,mid,a_datetime) values ('$jid','$mid','$a_datetime')");
			$aid = miid();
			$this->data['aid'] = $aid;
			
			$this->data['job'] = new Job($jid);
	    }

	    foreach($post_array as $arg => $val) {
	    	$this->$arg = $val;
	    }
		return $aid;
	}
	public function sendApplicationEmail() {
		if($this->data['aid'] != "") {
			$application_email = $this->data['job']->application_email;
			if($application_email != "") {
				$arr = array();
				$email = new Email('application-email',$arr,$application_email,'Application from ' . COMPANY_NAME);
				if(isset($this->data['cvid'])) {
					$cv = new CV($this->data['cvid']);
					$email->addAttachment($cv->location);
				}
				$email->sendEmail();
				$this->a_datesent = date("Y-m-d H:i:s");
			}
		}
	}
}
?>