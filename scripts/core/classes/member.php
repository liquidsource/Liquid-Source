<?php
/**
 * Member class, controls adding, editing and controlling access to member variables
 */

class Member {
    protected $data=array(), $m_hash, $m_password;
	
	/********************/
	/* PUBLIC FUNCTIONS */
	/********************/
	public function __construct($mid=NULL) {
		if($mid != "") {
			$rs = mq("select * from " . DB_TBL_MEMBERS . " where mid='$mid'");
			if(mnr($rs) > 0) {
				$rw = mfa($rs);
				foreach($rw as $arg=>$val) {
					$this->data[$arg] = stripslashes($val);
				}
				$rsi = mq("select * from " . DB_TBL_MEMBER_PROFILE . " p inner join " . DB_TBL_MEMBER_PROFILE_MASTER . " a on p.mpa_sc = a.mpa_sc where mid='$mid'");
				while($rwi = mfa($rsi)) {
					$this->data[$rwi['mpa_sc']] = stripslashes($rwi['mp_val']);
				}
			}
		} else {
			$this->data['mid'] = NULL;
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
        if($arg == "mid") { return; }
		
		if($this->data['mid'] > 0) {
	        $val = mres($val);
			$rs = mq("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_MEMBERS . "' AND COLUMN_NAME = '$arg'");
			if(mnr($rs) > 0) {
				$rsu = mq("update " . DB_TBL_MEMBERS . " set $arg='$val' where mid='" . $this->data['mid'] . "'");
			} else {
				$rs = mq("SELECT mpaid FROM " . DB_TBL_MEMBER_PROFILE_MASTER . " WHERE mpa_sc = '$arg'");
				if(mnr($rs) > 0) {
					$mpid = $this->userHasProfileArgument($this->data['mid'],$arg);
					if($mpid > 0) {
						$rsi = mq("update " . DB_TBL_MEMBER_PROFILE . " set mp_val='$val' where mpid='$mpid'");
					} else {
						$rsu = mq("insert into " . DB_TBL_MEMBER_PROFILE . " (mid,mpa_sc,mp_val) values ('" . $this->data['mid'] . "','$arg','$val')");
					}
				}
			}
			$this->data[$arg] = $val;
		}
    }
	
	/**************************/
	/* PUBLIC UPDATE FUNCTION */
	/**************************/
	public function updateUser($post_array,$m_type='U') {
		$mid = "";
		if(isset($this->data['mid'])) $mid = $this->data['mid'];
		foreach($post_array as $arg => $val) { $$arg = mres($val); }
		
		if($mid == "") {
			// Inserting a new Member
			$failed = false;
			if(!isset($m_username) || !isset($m_password)) { $failed = true; }
			if(!$failed) {
				// check username isn't taken
			    $rs = mq("SELECT mid, m_active FROM " . DB_TBL_MEMBERS . " WHERE m_username='$m_username' LIMIT 1");
			    if(mnr($rs) > 0) {
			        $rw = mfa($rs);
			        if($rw['m_active'] == "0") {
			        	$d_member = new Member($rw['mid']);
			        	$d_member->removeUser();
			        } else {
			            $_SESSION['_msg'] = "r_emailtaken";
			            $failed = true;
			        }
			    }
				
				// insert a new member, checks have been done and are ok
				if(!$failed) {
					$hash = $this->createUserHash($m_username,$m_password);
					if(!isset($m_level)) $m_level = 5;
			        $rs = mq("insert into " . DB_TBL_MEMBERS . " (m_username,m_type,m_level,m_hash,m_active,m_lastlogin) values ('$m_username','$m_type','$m_level','$hash','1','" . DB_SAFE_DATETIME . "')");
			        $mid = miid();
					
					$this->data['mid'] = $mid;
			        $this->data['m_username'] = $m_username;
			        $this->data['m_type'] = $m_type;
			        $this->data['m_level'] = $m_level;
			        $this->data['m_active'] = '1';
			        $this->data['m_lastlogin'] = DB_SAFE_DATETIME;
			        
					foreach($post_array as $arg => $val) {
						$this->$arg = $val;
					}
					
					$_SESSION['_mtype'] = "S";
					$_SESSION['_msg'] = "newmember";
					
					//set 'sendRegEmail to true' in the passed array ($post_array) to also send a welcome email
					if(isset($semdRegEmail) && $sendRegEmail) {
						$this->sendUserRegistrationEmail();
					}
				}
			}
			if($failed) { $_SESSION['_mtype'] = "E"; }
		} else {
			// Update User
			if(isset($m_username) && $m_username != "") {
				unset($post_array['m_username']);
		        $rw = mgr("select count(mid) as n from " . DB_TBL_MEMBERS . " where m_username='$m_username' and mid <> '$mid'");
		        if($rw['n'] == 0) { $post_array['m_username'] = $m_username; } else {
		        	$_SESSION['_msg'] = "r_emailtaken";
		        }
			}
			
			if(isset($m_password) && $m_password != "") {
	            $hash = $this->createUserHash($this->data['m_username'],$m_password);
				$post_array['m_hash'] = $hash;
	        }
			
			foreach($post_array as $arg => $val) { $this->$arg = $val; }
			
			$_SESSION['_mtype'] = "S";
			$_SESSION['_msg'] = "updatedmember";
		}
		return $mid;
	}
	
	public function getMemberProfileArray() {
		$ret = array();
		$rsi = mq("select * from " . DB_TBL_MEMBER_PROFILE_MASTER);
		while($rwi = mfa($rsi)) {
			$arg = $rwi['mpa_sc'];
			$val = "";
			if(isset($this->data[$rwi['mpa_sc']])) $val = $this->data[$rwi['mpa_sc']];
			$ret[$rwi['mpa_english']][1] = $arg;
			$ret[$rwi['mpa_english']][0] = $val;
		}
		return $ret;
	}
	
	/**************************/
	/* MEMBER LOGIN FUNCTIONS */
	/**************************/
	public function login() {
		foreach($_SESSION as $arg => $var) { unset($_SESSION[$arg]); }
		$rsu = mq("update " . DB_TBL_MEMBERS . " set m_lastlogin='" . DB_SAFE_DATETIME . "' where mid='" . $this->data['mid'] . "'");
		$_SESSION['mid'] = $this->data['mid'];
        $_SESSION['loggedin'] = true;
        $_SESSION['m_type'] = $this->data['m_type'];
	}
	public function sendUserRegistrationEmail() {
		$pswd = "";
		if(isset($this->data['m_password'])) $pswd = $this->data['m_password'];
		
		$arr = array('r_username' => $this->data['m_username'], 'r_email' => $this->data['m_email'], 'r_password' => $pswd);
    	$email = new Email('registration',$arr,$this->data['m_username'],"Welcome to " . COMPANY_NAME);
    	$success = $email->sendEmail();
        if($success) {
            $_SESSION['_msg'] = "welcome";
            $_SESSION['_mtype'] = "S";
        } else {
            $_SESSION['_msg'] = "errorsendingemail";
            $_SESSION['_mtype'] = "W";
        }
	}
	
	/*****************************/
	/* MEMBER PASSWORD FUNCTIONS */
	/*****************************/
	public function sendForgotPassword() {
        $pswd = generatePassword(9);
		$this->data['m_password'] = $pswd;
		
		$hash = $this->createUserHash($this->data['m_username'], $pswd);
        $rsu = mq("update " . DB_TBL_MEMBERS . " set m_hash='$hash' where mid='" . $this->data['mid'] . "'");
        
    	$email = new Email('forgot-password',$this->data,$this->data['m_username'],COMPANY_NAME . " Forgotten Password");
    	$success = $email->sendEmail();
		return $success;
	}
    public function changePassword($newPassword,$oldPassword=NULL) {
		if($this->data['mid'] > 0) {
			$change = true;
			if(isset($oldPassword)) {
				$change = $this->checkPassword($oldPassword);
			}
			
			if($change) {
	    		$hash = $this->createUserHash($this->data['m_username'], $newPassword);
				$rsu = mq("update " . DB_TBL_MEMBERS . " set m_hash='$hash' where mid='" . $this->data['mid'] . "'");
				return true;
			}
		}
		return false;
    }
	public function checkPassword($password) {
		return Member::isCorrectPassword($password,$this->data['m_hash']);
	}
	
	/*****************************/
	/* USER ALTERATION FUNCTIONS */
	/*****************************/
	public function deleteMember() {
		$rs = mq("update " . DB_TBL_MEMBERS . " set m_active='0' where mid='" . $this->data['mid'] . "'");
		$_SESSION['_mtype'] = "W";
		$_SESSION['_msg'] = "deletedmember";
	}
	public function removeMember() {
		$mid = $this->data['mid'];
		if($mid > 0) {
			$rs = mq("delete from " . DB_TBL_MEMBERS . " where mid='$mid'");
			$rs = mq("delete from " . DB_TBL_MEMBER_PROFILE . " where mid='$mid'");
		}
	}
	

	/*********************/
	/* STATIC FUNCTIONS */
	/*********************/
	public static function getMid() {
		if(isset($_SESSION['mid'])) return $_SESSION['mid'];
		return ;
	}
	public static function getMidFromEmail($email) {
		$rs = mq("select mid from " . DB_TBL_MEMBER_PROFILE . " where mp_val='$email' and mpa_sc='m_email'");
		if(mnr($rs) > 0) {
			$rw = mfa($rs);
			return $rw['mid'];
		}
		return false;
	}
	public static function isLoggedin($m_type='U') {
		if(!isset($_SESSION['mid'])) return false;
		if(!isset($_SESSION['loggedin'])) return false;
		if(!isset($_SESSION['m_type'])) return false;
		if($_SESSION['mid'] == "" || $_SESSION['loggedin'] != '1' || $_SESSION['m_type'] != $m_type) { return false; }
		return true;
	}
	public static function logout() {
		foreach($_SESSION as $arg => $var) { unset($_SESSION[$arg]); }
        session_destroy();
	}
	public static function checkLogin($u,$p,$m_type="U") {
		$_SESSION['tried'] = 0;
		if(!isset($_SESSION['tried'])) { $_SESSION['tried'] = 0; }
		if($_SESSION['tried'] < 9) {
	        $rs = mq("SELECT m_hash, mid from " . DB_TBL_MEMBERS . " where m_username='$u' and m_type='$m_type'");
	        if(mnr($rs) == 1) {
	            $rw = mfa($rs);
	            if(Member::isCorrectPassword($p,$rw['m_hash'])) {
					return $rw['mid'];
					$_SESSION['tried'] = 0;
	            }
			}
	    }
		$_SESSION['mid'] = "";
		$_SESSION['loggedin'] = false;
		$_SESSION['m_type'] = "";
	    $_SESSION['tried'] = $_SESSION['tried'] + 1;
	    $_SESSION['_msg'] = "loginfailed";
	    $_SESSION['_mtype'] = "E";
		return false;
	}
	

	/*********************/
	/* PRIVATE FUNCTIONS */
	/*********************/
	private static function isCorrectPassword($pswd,$_hash) {
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
	private function userHasProfileArgument($mid,$arg) {
		$rs = mq("select mpid from " . DB_TBL_MEMBER_PROFILE . " where mpa_sc='$arg' and mid='$mid'");
		if(mnr($rs) > 0) {
			$rw = mfa($rs);
			return $rw['mpid'];
		}
		return 0;
	}
}

function showMemberInput($in,$idname=NULL,$val=NULL,$it=NULL) {
	$ret = "";
	
	// Special case for job categories
	if($in == "category_multiple" || $in == "category_single") {
		$cm_extra = ""; $cm_brakets = "";
		if($in == "category_multiple") { $cm_extra = " multiple "; $cm_brakets = "[]"; }
		
		if($idname == NULL) { $idname = 'cid'; }
		$ret .= "<select id=\"$idname\" name=\"$idname" . $cm_brakets . "\" $cm_extra>";
        $arr = getCategories(array('type' => 'job'));
		foreach($arr as $category) {
			$ret .= showCategoryOption($category,'',$val);
		}
        $ret .= "</select>";
		return $ret;
	}
	
	$foundinput = false;
	$rs = mq("SELECT COLUMN_NAME as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_MEMBERS . "' and COLUMN_NAME='$in'");
	if(mnr($rs) > 0) {
		$rw = mfa($rs);
		if($idname == NULL) { $idname = $rw['c']; }
		$ret = "<input type=\"text\" id=\"$idname\" name=\"$idname\" value=\"$val\" />";
		$foundinput = true;
	}
	
	
	// All other job properties
	if(!$foundinput) {
		$rs = mq("select * from " . DB_TBL_MEMBER_PROFILE_MASTER . " where mpa_sc='$in'");
		if(mnr($rs) > 0) {
			$rw = mfa($rs);
			if($idname == NULL) { $idname = $rw['mpa_sc']; }
			if($it == NULL) $it = $rw['mpa_inputtype'];
			switch ($it) {
				case "text":
					$ret = "<input type=\"text\" id=\"$idname\" name=\"$idname\" value=\"$val\" />";
					break;
				case "email":
					$ret = "<input type=\"email\" id=\"$idname\" name=\"$idname\" value=\"$val\" />";
					break;
				case "password":
					$ret = "<input type=\"password\" id=\"$idname\" name=\"$idname\" value=\"$val\" />";
					break;
				case "select":
					$ret = "<select name=\"$idname\" id=\"$idname\">";
					$rss = mq("select * from " . DB_TBL_SELECTBOX . " where s_type='" . $rw['mpa_sc'] . "' order by s_val");
					while($rws = mfa($rss)) {
						$ret .= "<option value=\"" . $rws['s_val'] . "\"";
						if($val != NULL) {
							 if($rws['s_val'] == $val) $ret .= " selected ";
						} else {
							if($rws['s_default'] == "1") $ret .= " selected";
						}
						$ret .= ">" . $rws['s_val'] . "</option>";
					}
					$ret .= "</select>";
					break;
				case "textarea":
					$ret = "<textarea id=\"$idname\" name=\"$idname\">$val</textarea>";
					break;
				case "bool":
					$checked = false;
					$ret = "Yes <input type=\"radio\" value=\"1\" name=\"$idname\" id=\"$idname\" ";
					if($val == "1") { $ret .= " checked=\"checked\" "; $checked = true; }
					$ret .= "> &nbsp; &nbsp; ";
					$ret .= "No <input type=\"radio\" value=\"0\" name=\"$idname\" id=\"$idname\" ";
					if(!$checked) $ret .= " checked=\"checked\" ";
					$ret .= ">";
					break;
				case "special":
					break;
			}
			$foundinput = true;
		}
	}

	if(!$foundinput) {
		if($it == NULL) $it = "text";
		$ret = "<input type=\"$it\" id=\"$idname\" name=\"$idname\" value=\"$val\" />";
	}
	return $ret;
}
?>