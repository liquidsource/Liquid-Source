<?php
class CV {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($cvid=NULL) {
		if($cvid != NULL) {
			$rs = mq("select * from " . DB_TBL_MEMBER_CV . " where cvid='$cvid'");
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
	
	// Gets a $_FILES array
	
	public function get_location() { return $this->data['cv_folder'] . $this->data['cv_filename']; }
	public function get_filename() { return $this->data['cv_filename']; }
}
?>