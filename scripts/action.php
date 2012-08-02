<?php
include("system.php");

$action = "";
if(isset($_GET['action'])) $action = strtolower($_GET['action']);

if(isset($_GET['to'])) $to = $_GET['to'];
$datetime = date("Y-m-d H:i:s");

switch ($action) {
	
    case "login":
		$username = mres($_POST['username']);
        $password = mres($_POST['password']);
		
        $mid = Member::checkLogin($username,$password,'U');
		if($mid > 0) {
	        $member = new Member($mid);
			$member->login();
			$to = "home/";
		} else {
			$to = "ex_login/";
		}
        break;
    case "logout":
       	Member::logout();
        $to = "home/";
        break;
    case "register":
		$aBot = checkFormBot(array('timecheck' => '2'));
		if(!$aBot) {
			$failed = false;
			foreach($_POST as $arg => $val) { $$arg = mres($val); }
			
	        if($r_username == "") { $failed = true; $_SESSION['_msg'] = "r_unameblank"; }
	        if($r_password == "") { $failed = true; $_SESSION['_msg'] = "r_pswdblank"; }
	        if($r_email == "") { $failed = true; $_SESSION['_msg'] = "r_emailblank"; }
	        if($r_password != $r_password_t) { $failed = true; $_SESSION['_msg'] = "r_passwordmatch"; }
	        if($r_email != $r_email_t) { $failed = true; $_SESSION['_msg'] = "r_emailmatch"; }
	        
			if(!$failed) {
				$member = new Member();
				$post_array = array('m_username' => $r_username, 'm_password' => $r_password, 'm_email' => $r_email, 'm_fname' => $r_fname, 'm_lname' => $r_lname);
		        $mid = $member->updateUser($post_array,'U');
		        if($mid > 0) {
		        	$member->login();
		        	$member->sendUserRegistrationEmail();
		        	$_SESSION['_mtype'] = "S";
					$to = "home/";
		        } else {
					$to = "ex_register/";
					foreach($_POST as $arg => $var) { $_SESSION['r_' . $arg] = $val; }
		        }
			}
		} else {
        	$_SESSION['_msg'] = "r_botcheck";
        	$failed = true;
		}
		if($failed) {
		    $_SESSION['_mtype'] = "E";
			$to = "ex_register/";
		}
        break;
    case "forgotpswd":
        $email = $_POST['fgt_email'];
        $aBot = checkFormBot(array('timecheck' => '2'));
		if(!$aBot) {
			$mid = Member::getMidFromEmail($email);
			if($mid > 0) {
				$member = new Member($mid);
				$member->sendForgotPassword();
				$_SESSION['_msg'] = "forgotpswdsent";
				$_SESSION['_mtype'] = "S";
				$to = "ex_login/";
			} else {
				$_SESSION['_msg'] = "emailnotexist";
				$_SESSION['_mtype'] = "E";
				$to = "ex_login/";
			}
		}
        break;
    case "contactus":
		$aBot = checkFormBot(array('timecheck' => '2'));
		if(!$aBot) {
	        $name = $_POST['cu_name'];
	        $email = $_POST['cu_email'];
	        $message = $_POST['cu_msg'];
	        
	        $msg = "Contact page message sent:<br />From: $name<br />Email: $email<br />Their message:<br />$message";
	        
			$email = new Email();
			$email->sendRawEmail(EM_CONTACT_ADDRESS, COMPANY_NAME . " Contact Page", $msg);
	        $_SESSION['_mtype'] = "S";
	        $_SESSION['_msg'] = "contactsent";
			
			$to = "x_contactus/";
		}
        break;
}
if(substr($to,-1,1) != "/") $to .= "/";
header("Location: ../$to");
?>
