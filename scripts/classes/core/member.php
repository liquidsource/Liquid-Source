<?php
class Member {
    private $data, $m_hash, $m_password;
	
	/* PUBLIC FUNCTIONS */
	public function __construct($mid=NULL) {
		if($mid != "") {
			$rs = mq("select * from " . DB_TBL_MEMBERS . " m inner join " . DB_TBL_MEMBER_PROFILE . " p on m.mid = p.mid where m.mid='$mid'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				$this->data['mid'] = $mid;
				$this->data['m_username'] = stripslashes($rw['m_username']);
				$this->data['m_hash'] = $rw['m_hash'];
				$this->data['m_type'] = $rw['m_type'];
				$this->data['m_email'] = $rw['m_email'];
				$this->data['m_active'] = $rw['m_active'];
				$this->data['m_lastlogin'] = $rw['m_lastlogin'];
				$this->data['m_createdate'] = $rw['m_createdate'];
				$this->data['m_protected'] = $rw['m_protected'];
				$this->data['mp_fname'] = stripslashes($rw['mp_fname']);
				$this->data['mp_lname'] = stripslashes($rw['mp_lname']);
				
				$this->setMemberFullName();
			}
		}
	}
	public function __get($arg) {
        if (isset($this->data[$arg])) {
            return $this->data[$arg];
        }
    }

    public function __set($arg, $val) {
        if ($arg == "mid") { return; }
		
        if (isset($this->data[$arg])) {
            $this->data[$arg] = $val;
        	$val = mres($val);
			try {
				$rs = mq("update " . DB_TBL_MEMBERS . " set $arg='$val' where mid='" . $this->data['mid'] . "'");
			} catch(Exception $e) { }
        }
    }
	public function setCurrentUserIntoSession() {
		$_SESSION['mid'] = $this->data['mid'];
        $_SESSION['loggedin'] = true;
        $_SESSION['m_type'] = $this->data['m_type'];
		$this->setMemberFullName();
	}
	public function sendUserRegistrationEmail() {
		$member_array = $this->createUserArray();
    	$email = new Email('registration',$data,$this->data['m_email'],"Welcome to $companyName");
    	$success = $email->sendEmail();
        if($success) {
            $_SESSION['_msg'] = "welcome";
        } else {
            $_SESSION['_msg'] = "errorsendingemail";
        }
	}
	public function updateUser($post_array,$m_type='U') {
		$mid = $this->data['mid'];
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		
		$success = false;
		if($mid != "") {
	        if($m_password != "") {
	            $hash = $this->createUserHash($m_email,$m_password);
	            $mic[] = "m_hash='$hash',";
				$this->data['m_password'] = $m_password;
				$this->data['m_hash'] = $hash;
	        }
			if($m_email != "") {
		        $rw = mgr("select count(mid) as n from " . DB_TBL_MEMBERS . " where m_email='$m_email' and mid <> '$mid'");
		        if($rw['n'] == 0) { $mic[] = "m_email='$m_email',"; $this->data['m_email'] = $m_email; }
			}
			if($m_username != "") {
		        $rw = mgr("select count(mid) as n from " . DB_TBL_MEMBERS . " where m_username='$m_username' and mid <> '$mid'");
		        if($rw['n'] == 0) { $mic[] = "m_username='$m_username',"; $this->data['m_username'] = $m_username; }
			}
			
			if($m_type != "") { $mic[] = "m_type='$m_type'"; $this->data['>m_type'] = $m_type; }
			
			if(sizeof($mic) > 0) {
				foreach($mic as $val) { $mic_x .= $val; }
	        	$rs = mq("update " . DB_TBL_MEMBERS . " set $mic_x where mid='$mid'");
			}
			
			
			if($mp_fname != NULL) { $mpic[] = "mp_fname='$mp_fname'"; $this->data['mp_fname'] = $mp_fname; }
			if($mp_lname != NULL) { $mpic[] = "mp_lname='$mp_lname'"; $this->data['mp_lname'] = $mp_lname; }
			$this->setMemberFullName();
			
			if(sizeof($mpic) > 0) {
				foreach($mpic as $val) { $mpic_x .= $val . ", "; }
				$mpic_x = substr($mpic_x,0,-2);
	        	$rs = mq("update " . DB_TBL_MEMBER_PROFILE . " set $mpic_x where mid='$mid'");
			}
			
		    $success = true;
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
	            	foreach($_SESSION as $arg => $var) { unset($_SESSION[$arg]); }
					$success = true;
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
		$mid = $this->data['mid'];
        $pswd = generatePassword(9);
		$hash = $this->createUserHash($this->data['m_email'], $pswd);
        $rsu = mq("update " . DB_TBL_MEMBERS . " set m_hash='$hash' where mid='$mid'");
        
		$member_array = $this->createUserArray();
		
		
		$member_array = $this->createUserArray();
    	$email = new Email('forgot-password',$member_array,$this->m_email,"Welcome to $companyName");
    	$success = $email->sendEmail();
        if($success) {
        	
		} else {
			
		}
        	
        $msg = composeEmail('forgot-password',$member_array);
        if($msg != "") { sendEmail($this->data['email'],$msg,"$companyName Forgotten Password");  }
        $_SESSION['_mtype'] = "W";
        $_SESSION['_msg'] = "sentreminder";
		log_me('FGS');
	}
	public function deleteMember() {
		log_me('DLM');
		$mid = $this->data['mid'];
		$rs = mq("update " . DB_TBL_MEMBERS . " set m_active='0' where mid='$mid'");
		$_SESSION['_mtype'] = "W";
		$_SESSION['_msg'] = "deletedmember";
	}
    public function changePassword($newPassword) {
    	$hash = $this->createUser($this->data['m_type'],$this->data['m_email']);
		if($this->data['mid'] > 0) {
			$rsu = mq("update " . DB_TBL_MEMBERS . " set m_hash='$hash' where mid='" . $this->data['mid'] . "'");
		}
    }
	public function removeUser() {
		$mid = $this->data['mid'];
		log_me('RMM','Removing member with ID: ' . $mid);
		if($mid > 0) {
			$rs = mq("delete from " . DB_TBL_MEMBERS . " where mid='$mid'");
			$rs = mq("delete from " . DB_TBL_MEMBER_PROFILE . " where mid='$mid'");
		}
	}
	
	/* STATIC PUBLIC FUNCTIONS */
	public static function isLoggedin() {
		if($_SESSION['mid'] == "" || $_SESSION['loggedin'] != '1') { return false; }
		return true;
	}
	public static function logout() {
		log_me('LGO');
		foreach($_SESSION as $arg => $var) { unset($_SESSION[$arg]); }
        session_destroy();
	}
	public static function checkLoggedIn() {
	    if($_SESSION['mid'] == "" || $_SESSION['loggedin'] != true) {
		    $_SESSION['_msg'] = "mustbeloggedin";
		    $_SESSION['_mtype'] = "W";
		    ?>
		    NOT ALLOWED HERE
		    <script type="text/javascript">location.href = "login";</script>
		    <?php
		    die;
	    }
	}
	public static function isAdminLoggedIn() {
		if($_SESSION['mid'] == "" || $_SESSION['loggedin'] != '1' || $_SESSION['m_type'] != "A") { return false; }
		return true;
	}
	
	/* PROECTED FUNCTIONS */
	protected function createUser($post_array,$m_type) {
		$success = false;
		$mid = $this->data['mid'];
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
			        $rs = mq("insert into " . DB_TBL_MEMBER_PROFILE . " (mid,mp_fname,mp_lname) values  ('$mid','$mp_fname','$mp_lname')");
					
					$this->data['mid'] = $mid;
			        $this->data['m_username'] = $m_username;
			        $this->data['m_type'] = $m_type;
			        $this->data['m_email'] = $m_email;
			        $this->data['m_active'] = '1';
			        $this->data['m_lastlogin'] = $datetime;
			        $this->data['m_protected'] = 0;
			        $this->data['m_password'] = $m_password;
			        $this->data['mp_fname'] = $mp_fname;
			        $this->data['mp_lname'] = $mp_lname;
					$this->setMemberFullName();
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
	public function isCorrectPassword($pswd,$_hash) {
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
	private function setMemberFullName() {
		$this->data['mp_fullname'] = $this->data['mp_fname'] . " " . $this->data['mp_lname'];
		$_SESSION['userFullName_' . $mid] = $this->data['mp_fullname'];
	}
}
?>