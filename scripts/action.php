<?php
include("system.php");

$action = strtolower($_GET['action']);
$to = $_GET['to'];
$datetime = date("Y-m-d H:i:s");

switch ($action) {
    case "login":
		$username = mres($_POST['username']);
        $password = mres($_POST['password']);
		
        $member = new Member();
		$success = $member->checkLogin($username,$password,'U','../login/','../account/');
		
		if(!$success) {
	        $_SESSION['_mtype'] = "E";
	        $_SESSION['_msg'] = "loginfailed";
			$to = "login";
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
	        
			$member = new Member();
			$post_array = array('m_username' => $r_username, 'm_password' => $r_password, 'm_email' => $r_email, 'mp_fname' => $r_fname, 'mp_lname' => $r_lname);
	        $mid = $member->updateUser($post_array,'U');
	        if($mid > 0) {
	        	//$member->sendUserRegistrationEmail();
				$member->setCurrentUserIntoSession();
	        	$_SESSION['_mtype'] = "S";
				$to = "home";
	        } else {
	        	$_SESSION['_mtype'] = "E";
				$to = "register";
				foreach($_POST as $arg => $var) { $_SESSION['r_' . $arg] = $val; }
	        }
		} else {
        	$failed = true; $_SESSION['_msg'] = "r_botcheck";
		}
        break;
    case "forgotpswd":
        $email = $_POST['fgt_email'];
        $aBot = checkFormBot(array('timecheck' => '2'));
		if(!$aBot) { sendForgotPassword($email); }
        break;
    case "contactus":
		$aBot = checkFormBot(array('timecheck' => '2'));
		if(!$aBot) {
	        $name = $_POST['cu_namey'];
	        $email = $_POST['cu_email'];
	        $message = $_POST['cu_msg'];
	        
	        $msg = "Contact page message sent:<br />From: $name<br />Email: $email<br />Their message:<br />$message";
	        
			$email = new Email();
			$email->sendRawEmail(EM_CONTACT_ADDRESS, "$companyName Contact Page", $msg);
	        $_SESSION['_mtype'] = "S";
	        $_SESSION['_msg'] = "contactsent";
		}
        break;
}
if(substr($to,-1,1) != "/") $to .= "/";
header("Location: ../$to");
?>
