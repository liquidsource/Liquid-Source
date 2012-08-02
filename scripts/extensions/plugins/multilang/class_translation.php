<?php
/**
 * Translation class
 */

class Translation {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($type,$origuid,$iso) {
		if($type == "page") {
			if($origuid != "" && $iso != "") {
				$datetime = date("Y-m-d H:i:s");
				if(!Member::isLoggedin('A')) {
					 $timeclause = " and pg_publisheddate <= '$datetime' ";
				}
				$rs = mq("select pgid from " . DB_TBL_PAGES . " where pg_iso_parent='$origuid' and pg_iso='$iso' and pg_active='1' and pg_posttype != 'inherit' $timeclause");
				if(mnr($rs) > 0) {
					$rw = mfa($rs);
					$this->data['iso_pgid'] = $rw['pgid'];
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
}
?>