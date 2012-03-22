<?php
/**
 * Member class, controls adding, editing and controlling access to member variables
 */

class Member {
    private $data, $m_hash, $m_password;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($mid=NULL) {
		if($mid != "") {
			$rs = mq("select * from " . DB_TBL_MEMBERS . " where mid='$mid'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				$this->data['id'] = $mid;
				$this->data['username'] = stripslashes($rw['m_username']);
				$this->data['hash'] = $rw['m_hash'];
				$this->data['type'] = $rw['m_type'];
				$this->data['email'] = $rw['m_email'];
				$this->data['active'] = $rw['m_active'];
				$this->data['lastlogin'] = $rw['m_lastlogin'];
				$this->data['createdate'] = $rw['m_createdate'];
				$this->data['protected'] = $rw['m_protected'];
			}
			
			$rs = mq("select * from " . DB_TBL_MEMBER_PROFILE . " p inner join " . DB_TBL_MEMBER_PROFILE_ARGUMENTS . " a on p.mpa_sc = a.mpa_sc where mid='$mid'");
			while($rw = mfa($rs)) {
				$this->data[$rw['mpa_sc']] = stripslashes($rw['mp_val']);
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
        if ($arg == "mid") { return; }
		
        if (isset($this->data[$arg])) {
        	$val = mres($val);
			
			$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_MEMBERS . "' AND COLUMN_NAME = '$arg'");
            $this->data[$arg] = $val;
			if(mnr($rs) > 0) {
				$rs = mq("update " . DB_TBL_MEMBERS . " set $arg='$val' where mid='" . $this->data['id'] . "'");
			} else {
				insertUpdateMembrProfile($arg,$val);
			}
        }
    }
	public function setCurrentUserIntoSession() {
		$_SESSION['mid'] = $this->data['id'];
        $_SESSION['loggedin'] = true;
        $_SESSION['m_type'] = $this->data['type'];
	}
	public function sendUserRegistrationEmail() {
		$member_array = $this->createUserArray();
    	$email = new Email('registration',$member_array,$this->data['email'],"Welcome to $companyName");
    	$success = $email->sendEmail();
        if($success) {
            $_SESSION['_msg'] = "welcome";
        } else {
            $_SESSION['_msg'] = "errorsendingemail";
        }
	}
	public function updateUser($post_array,$m_type='U') {
		$mid = $this->data['id'];
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		
		if($mid != "") {
	        if($m_password != "") {
	            $hash = $this->createUserHash($m_email,$m_password);
	            $mic[] = "m_hash='$hash',";
				$this->data['password'] = $m_password;
				$this->data['hash'] = $hash;
	        }
			if($m_email != "") {
		        $rw = mgr("select count(mid) as n from " . DB_TBL_MEMBERS . " where m_email='$m_email' and mid <> '$mid'");
		        if($rw['n'] == 0) { $mic[] = "m_email='$m_email',"; $this->data['email'] = $m_email; }
			}
			if($m_username != "") {
		        $rw = mgr("select count(mid) as n from " . DB_TBL_MEMBERS . " where m_username='$m_username' and mid <> '$mid'");
		        if($rw['n'] == 0) { $mic[] = "m_username='$m_username',"; $this->data['username'] = $m_username; }
			}
			
			if($m_type != "") { $mic[] = "m_type='$m_type'"; $this->data['>m_type'] = $m_type; }
			
			if(sizeof($mic) > 0) {
				foreach($mic as $val) { $mic_x .= $val; }
	        	$rs = mq("update " . DB_TBL_MEMBERS . " set $mic_x where mid='$mid'");
			}
			
			$this->updateMemberProfile($mid,$post_array);
			
			$_SESSION['_mtype'] = "S";
			$_SESSION['_msg'] = "newmember";
		} else {
			$mid = $this->createUser($post_array,$m_type);
		}
		return $mid;
	}
	public function checkLogin($u,$p,$m_type="U",$fail='login',$suc='account',$return=false) {
		log_me('ALI');
		if($_SESSION['tried'] < 9) {
	        $rs = mq("SELECT m_hash, mid from " . DB_TBL_MEMBERS . " where m_username='$u' and m_type='$m_type'");
	        if(mnr($rs) == 1) {
	            $rw = mfa($rs);
	            if($this->isCorrectPassword($p,$rw['m_hash'])) {
					$success = true;
	            	foreach($_SESSION as $arg => $var) { unset($_SESSION[$arg]); }
	                $_SESSION['mid'] = $rw['mid'];
	                $_SESSION['loggedin'] = true;
	                $_SESSION['m_type'] = $m_type;
					
					log_me('LGS');
					if(!$return) {
						header("Location: $suc");
						die;
					}
	            }
			}
	    }
		
		if(!$success) {
			$success = false;
			log_me('LGF');
			$_SESSION['mid'] = "";
			$_SESSION['loggedin'] = false;
			$_SESSION['m_type'] = "";
		    $_SESSION['tried'] = $_SESSION['tried'] + 1;
		    $_SESSION['_msg'] = "loginfail";
		    $_SESSION['_mtype'] = "E";
			if(!$return) {
			    header("Location: $fail");
				die;
			}
		}
		return $success;
	}
	public function sendForgotPassword() {
		$mid = $this->data['id'];
        $pswd = generatePassword(9);
		$hash = $this->createUserHash($this->data['email'], $pswd);
        $rsu = mq("update " . DB_TBL_MEMBERS . " set m_hash='$hash' where mid='$mid'");
        
		$member_array = $this->createUserArray();
    	$email = new Email('forgot-password',$member_array,$this->m_email,"$companyName Forgotten Password");
    	$success = $email->sendEmail();
        if($success) {
        	
		} else {
			
		}
		log_me('FGS');
	}
	public function deleteMember() {
		log_me('DLM');
		$mid = $this->data['id'];
		$rs = mq("update " . DB_TBL_MEMBERS . " set m_active='0' where mid='$mid'");
		$_SESSION['_mtype'] = "W";
		$_SESSION['_msg'] = "deletedmember";
	}
    public function changePassword($newPassword) {
    	$hash = $this->createUser($this->data['type'],$this->data['email']);
		if($this->data['id'] > 0) {
			$rsu = mq("update " . DB_TBL_MEMBERS . " set m_hash='$hash' where mid='" . $this->data['id'] . "'");
		}
    }
	public function removeMember() {
		$mid = $this->data['id'];
		log_me('RMM','Removing member with ID: ' . $mid);
		if($mid > 0) {
			$rs = mq("delete from " . DB_TBL_MEMBERS . " where mid='$mid'");
			$rs = mq("delete from " . DB_TBL_MEMBER_PROFILE . " where mid='$mid'");
		}
	}
	
	/* STATIC PUBLIC FUNCTIONS */
	public static function isLoggedin($m_type='U') {
		if($_SESSION['mid'] == "" || $_SESSION['loggedin'] != '1' || $_SESSION['m_type'] != $m_type) { return false; }
		return true;
	}
	public static function logout() {
		log_me('LGO');
		foreach($_SESSION as $arg => $var) { unset($_SESSION[$arg]); }
        session_destroy();
	}
	public static function getMemberProfileArray() {
		$i = 0;
		$arr = array();
		$rs = mq("select * from " . DB_TBL_MEMBER_PROFILE_ARGUMENTS);
		while($rw = mfa($rs)) {
			$arr[$i][0] = $rw['mpa_sc'];
			$arr[$i][1] = $rw['mpa_english'];
			$i++;
		}
		return $arr;
	}
	
	/* PROECTED FUNCTIONS */
	protected function createUser($post_array,$m_type) {
		$success = false;
		$mid = $this->data['id'];
		if($mid == "") {
			foreach($post_array as $arg => $val) { $$arg = mres($val); }
			
			//requires username, email and password
			if($m_username != "" && $m_email != "" && $m_password != "") {
				$success = true;
				
				$rs = mq("SELECT mid, m_active FROM " . DB_TBL_MEMBERS . " WHERE m_username='$m_username' LIMIT 1");
			    if(mnr($rs) > 0) {
			        $rw = mfa($rs);
			        if($rw['m_active'] == "0") {
			        	$d_member = new Member($rw['mid']);
			        	$d_member->removeUser();
			        } else {
			            $_SESSION['_msg'] = "r_userexists";
			            $_SESSION['_mtype'] = "E";
			            $success = false;
			        }
			    }
			    $rs = mq("SELECT mid, m_active FROM " . DB_TBL_MEMBERS . " WHERE m_email='$m_email' LIMIT 1");
			    if(mnr($rs) > 0) {
			        $rw = mfa($rs);
			        if($rw['m_active'] == "0") {
			        	$d_member = new Member($rw['mid']);
			        	$d_member->removeUser();
			        } else {
			            $_SESSION['_msg'] = "r_emailtaken";
			            $_SESSION['_mtype'] = "E";
			            $success = false;
			        }
			    }
				
				if($success) {
					$hash = $this->createUserHash($m_email,$m_password);
			        $rs = mq("insert into " . DB_TBL_MEMBERS . " (m_username,m_type,m_hash,m_email,m_active,m_lastlogin) values ('$m_username','$m_type','$hash','$m_email','1','$datetime')");
			        $mid = miid();
					
					$this->data['id'] = $mid;
			        $this->data['username'] = $m_username;
			        $this->data['type'] = $m_type;
			        $this->data['email'] = $m_email;
			        $this->data['active'] = '1';
			        $this->data['lastlogin'] = $datetime;
			        $this->data['protected'] = 0;
			        $this->data['password'] = $m_password;
					
					$this->updateMemberProfile($mid,$post_array);
					
					$_SESSION['_mtype'] = "S";
					$_SESSION['_msg'] = "newmember";
				}
			} else {
				$_SESSION['_mtype'] = "E";
				$_SESSION['_msg'] = "missingmember";
			}
		}
		return $mid;
	}
	
	/* PRIVATE FUNCTIONS */
	private function updateMemberProfile($mid,$post_array) {
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		$arr = $this->getMemberProfileArray();
		foreach($arr as $arg) {
			$post_field = "mp_" . $arg[0];
			if($$post_field != NULL) {
				$this->insertUpdateMemberProfile($arg[0],$$post_field);
			}
		}
	}
	private function insertUpdateMemberProfile($arg,$val) {
		$mpid = $this->userHasProfileArgument($arg);
		if($mpid > 0) {
			$rs = mq("update " . DB_TBL_MEMBER_PROFILE . " set mp_val='$val' where mpid='$mpid'");
		} else {
			$rs = mq("insert into " . DB_TBL_MEMBER_PROFILE . " (mid,mpa_sc,mp_val) values ('$mid','$arg','$val')");
		}
		$this->data[$arg] = $val;
	}
	private function isCorrectPassword($pswd,$_hash) {
		$salt = substr($_hash, 0, 64);
	    $hash = $salt . $pswd;
	    
	    for ( $i = 0; $i < 100000; $i ++ ) { $hash = hash('sha256', $hash); }
	    $hash = $salt . $hash;
	    if($hash == $_hash) { return true; }
		return false;
	}
	private function createUserHash($email,$pswd) {
		$salt = hash('sha256', uniqid(mt_rand(), true) . 'projectspecific' . $email);
	    $hash = $salt . $pswd;
	    for ( $i = 0; $i < 100000; $i ++ ) { $hash = hash('sha256', $hash); }
	    $hash = $salt . $hash;
		return $hash;
	}
	private function userHasProfileArgument($arg) {
		$rs = mq("select mpid from " . DB_TBL_MEMBER_PROFILE . " where mpa_sc='$arg'");
		if(mnr($rs) > 0) {
			$rw = mfa($rs);
			return $rw['mpid'];
		}
		return 0;
	}
}
?>