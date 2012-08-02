<?php
class Job_Member extends Member{
	public function __construct($mid=NULL) {
       parent::__construct($mid);
	   $this->data['password'] = "Not shown for security purposes";
   	}
    public function createMemberJobFolder() {
    	$mid = $this->data['mid'];
		if(!isset($this->data['member_folder']) ) {
			$target_path = INCLUDE_WEB_ROOT . "uploads/members/" . date("Y") . "/";
			if(!file_exists($target_path)) { mkdir($target_path,0777); }
			$target_path = INCLUDE_WEB_ROOT . "uploads/members/" . date("Y") . "/" . date("m") . "/";
			if(!file_exists($target_path)) mkdir($target_path,0777);
			$target_path = INCLUDE_WEB_ROOT . "uploads/members/" . date("Y") . "/" . date("m") . "/" . $mid . "/";
			if(!file_exists($target_path)) mkdir($target_path,0777);
			$target_path = INCLUDE_WEB_ROOT . "uploads/members/" . date("Y") . "/" . date("m") . "/" . $mid . "/cvs/";
			if(!file_exists($target_path)) mkdir($target_path,0777);
			
			$this->member_folder = "uploads/members/" . date("Y") . "/" . date("m") . "/" . $mid . "/";
		}
		
    	
    }
	public function getCvFolder() {
		return $this->data['member_folder'] . "cvs/";
	}
	public function saveJob($jid) {
		if($this->data['mid'] > 0) {
			$rs = mq("select sjid from " . DB_TBL_SAVED_JOBS . " where mid='" . $this->data['mid'] . "' and jid='$jid'");
			if(mnr($rs) == 0) {
				$rsi = mq("insert into " . DB_TBL_SAVED_JOBS . " (mid,jid) values ('" . $this->data['mid'] . "','$jid')");
			}
			$_SESSION['_msg'] = "jobsaved";
			$_SESSION['_mtype'] = "S";
		} else {
			$_SESSION['_msg'] = "savejob_needtobeloggedin";
			$_SESSION['_mtype'] = "E";
		}
		return false;
	}
	public function getCurrentCV() {
		$rs = mq("select cvid from " . DB_TBL_MEMBER_CV . " where mid='" . $this->data['mid'] . "' order by cvid desc");
		if(mnr($rs) > 0) {
			$rw = mfa($rs);
			return new CV($rw['cvid']);
		}
		return new CV();
	}
	public function apply($jid,$arr) {
		$mid = $this->mid;
		if($mid > 0 && $jid > 0) {
			$application = new Application();
			$aid = $application->updateApplication($jid,$mid,$arr);
			if($aid > 0) {
				$application->sendApplicationEmail();
				$_SESSION['_msg'] = "jobappliedok";
				$_SESSION['_mtype'] = "S";
			}
			return true;
		}
		return false;
	}
	public function updateCV($cvfile) {
		$cvid = "";
		$mid = $this->data['mid'];
		$this->createMemberJobFolder();
		$cvfolder = $this->getCvFolder();
		
		$target_path = INCLUDE_WEB_ROOT . $cvfolder;
		$filename = basename($cvfile['name']);
		
		$fullurl = $target_path . $filename;
		while(file_exists($fullurl)) {
			$rantext = genRandomString(5);
			$filename = $rantext . "_" . basename($cvfile['name']);
			$fullurl = $target_path . $filename;
		}
		
		if(move_uploaded_file($cvfile['tmp_name'], $fullurl)) {
			$rs = mq("insert into " . DB_TBL_MEMBER_CV . " (mid,cv_filename,cv_folder,cv_dateuploaded) values ('$mid','" . $filename . "','" . $cvfolder . "','" . DB_SAFE_DATETIME . "')");
			$cvid = miid();
		}
		
		$_SESSION['_msg'] = "cvupdated";
		$_SESSION['_mtype'] = "S";
		return $cvid;
	}
	public function getJobAlerts() {
		$ret = array();
		$rs = mq("select * from " . DB_TBL_JOB_ALERT . " where mid='" . $this->data['mid'] . "'");
		while($rw = mfa($rs)) {
			$ret[] = new JobAlert($rw['jaid']);
		}
		return $ret;
	}
	public function getSavedJobs() {
		$ret = array();
		$rs = mq("select * from " . DB_TBL_SAVED_JOBS . " where mid='" . $this->data['mid'] . "'");
		while($rw = mfa($rs)) {
			$job = new Job($rw['jid']);
			$ret[] = $job;
		}
		return $ret;
	}
	public function hasAppliedToJob($jid) {
		$mid = $this->data['mid'];
		if($mid > 0) {
			$rs = mq("select aid from " . DB_TBL_APPLICATIONS . " where jid='$jid' and mid='$mid'");
			if(mnr($rs) > 0) {
				return true;
			}
		}
		return false;
	}
	public function getAppliedToJobDate($jid) {
		$mid = $this->data['mid'];
		if($mid > 0) {
			$rs = mq("select a_datetime from " . DB_TBL_APPLICATIONS . " where jid='$jid' and mid='$mid'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				return $rw['a_datetime'];
			}
		}
		return false;
	}
}
?>