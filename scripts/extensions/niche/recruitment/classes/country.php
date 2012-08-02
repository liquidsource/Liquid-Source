<?php
class Country {
    private $data;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($iso=NULL) {
		if($iso != NULL) {
			$rs = mq("select * from " . DB_TBL_COUNTRIES . " where iso='$iso'");
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
	
	public function getJobCategories() {
		if(isset($this->data['iso'])) {
			$arr = array();
			$iso = $this->data['iso'];
			$jpid = getJPID('country');
			$rs = mq("select distinct cid  from " . DB_TBL_CATEGORY_LINK . " l inner join " . DB_TBL_JOB_PROPERTIES . " j on l.uid = j.jid where jpid='$jpid' and jp_value='$iso'");
			while($rw = mfa($rs)) {
				$cat = new Category($rw['cid']);
				$rwa = mfa(mq("select count(distinct uid) as n from " . DB_TBL_CATEGORY_LINK . " where l_type='job' and cid='" . $rw['cid'] . "'"));
				$cat->amount = $rwa['n'];
				$arr[] = $cat;
			}
			return $arr;
		}
	}
}
?>