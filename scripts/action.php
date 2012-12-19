<?php
include("system.php");

$action = "";
if(isset($_GET['action'])) $action = strtolower($_GET['action']);

if(isset($_GET['to'])) $to = $_GET['to'];
$datetime = date("Y-m-d H:i:s");

switch ($action) {
    case "login":
		log_me('ALI');
		$username = mres($_POST['l_email']);
        $password = mres($_POST['l_password']);
		
        $mid = Member::checkLogin($username,$password,'U');
		if($mid > 0) {
			log_me('LGS');
			$member = new Member($mid);
			$jsem = $member->m_u_type;
			$member->login();
			$to = "account/";
		} else {
			log_me('LGF');
		}
        break;
    case "logout":
		log_me('LGO');
       	Member::logout();
        $to = "home/";
        break;
    case "register":
		log_me('RGA');
		$aBot = checkFormBot(array('timecheck' => '2'));
		if(!$aBot) {
			$failed = false;
			foreach($_POST as $arg => $val) { $$arg = mres($val); }
			
			/* Basic PHP checks for registration compliance. Adapt as necessary */
	        if($m_password == "") { $failed = true; $_SESSION['_msg'] = "r_pswdblank"; }
	        if($m_email == "") { $failed = true; $_SESSION['_msg'] = "r_emailblank"; }
	        if($m_password != $m_password_t) { $failed = true; $_SESSION['_msg'] = "r_passwordmatch"; }
	        if($m_email != $m_email_t) { $failed = true; $_SESSION['_msg'] = "r_emailmatch"; }
	        
			/* Set the member type. If it isn't set assume its a job seeker registration */
			$jsem = "js";
			if(isset($regtype)) { $jsem = $regtype; }
			
			if(!$failed) {
				log_me('RGS');
				if($jsem == "js") { $member = new Candidate(); }
				else { $member = new Employer(); }
				
				$post_array = $_POST;
				$post_array['m_username'] = $m_email;
				$post_array['m_u_type'] = $jsem;
		        $mid = $member->updateUser($post_array,'U');
		        if($mid > 0) {
		        	$member->login();
		        	$member->sendUserRegistrationEmail();
		        	$_SESSION['_mtype'] = "S";
					$to = "account/";
		        } else {
		    		$_SESSION['_mtype'] = "E";
					$to = $jsem . "_register/";
					foreach($_POST as $arg => $var) { $_SESSION['r_' . $arg] = $val; }
		        }
			}
		} else {
			log_me('RGB');
        	$_SESSION['_msg'] = "r_botcheck";
        	$failed = true;
		}
		if($failed) {
			log_me('RGF');
		    $_SESSION['_mtype'] = "E";
			$to = $jsem . "_register/";
		}
        break;
    case "forgotpswd":
		log_me('FGP');
        $email = $_POST['fgt_email'];
        $aBot = checkFormBot(array('timecheck' => '2'));
		if(!$aBot) {
			$jsem = "js";
			if(isset($_POST['regtype'])) { $jsem = $_POST['regtype']; }
			
			$mid = Member::getMidFromEmail($email);
			if($mid > 0) {
				log_me('FGS');
				$member = new Member($mid);
				$member->sendForgotPassword();
				$_SESSION['_msg'] = "forgotpswdsent";
				$_SESSION['_mtype'] = "S";
				$to = $jsem . "_login/";
			} else {
				log_me('FGF');
				$_SESSION['_msg'] = "emailnotexist";
				$_SESSION['_mtype'] = "E";
				$to = $jsem . "_login/";
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
			
			$to = "contactus/";
		}
        break;
	case "jobsearch":
		$arr = array();
		$jobsearch = new JobSearch('',$_POST);
		$scid = $jobsearch->scid;
		$_SESSION['scid'] = $scid;
		break;
	
	
	case "addjobtobasket":
		$jid = $_GET['jid'];
		$job = new Job($jid);
		$to = $job->link;
		$member = new Candidate(Member::getMid());
		$member->saveJob($_GET['jid']);
		break;
	case "emailfriend";
		$email = "";
		if(isset($_POST['a_email'])) $email = $_POST['a_email'];
		$jid = $_GET['jid'];
		$job = new Job($jid);
		$job->emailFriend($email);
		if(empty($to)) $to = $job->link;
		break;
		
		
	// Logged In Calls
	case "updateprofile":
		log_me('UPP');
		if(Member::isLoggedin() && Recruitment::current_user_type() == "js") {
			$candidate = new Candidate(Member::getMid());
			$post_array = array('m_fname' => $_POST['m_fname'], 'm_lname' => $_POST['m_lname']);
			$candidate->updateUser($post_array,'U');
		   	if(isset($_FILES['m_cv'])) {
				$file = $_FILES['m_cv'];
				$cvid = $candidate->updateCV($file);
			}
			
			if(!empty($_POST['m_email'])) {
				$post_array = array('m_username' => $_POST['m_email']);
				$candidate->updateUser($post_array,'U');
				
				if($candidate->m_email != $candidate->m_username) {
					$post_array = array('m_email' => $_POST['m_email']);
					$candidate->updateUser($post_array,'U');
				}
			}
			
			if(!empty($_POST['m_old_password']) && !empty($_POST['m_password'])) {
				$candidate->changePassword($_POST['m_password'],$_POST['m_old_password']);
			}
			$to = "account/js_editprofile/";
		}
		if(Member::isLoggedin() && Recruitment::current_user_type() == "em") {
			$employer = new Employer(Member::getMid());
			$post_array = array('m_fname' => $_POST['m_fname'], 'm_lname' => $_POST['m_lname']);
			$employer->updateUser($post_array,'U');
			
			if(!empty($_POST['m_email'])) {
				$post_array = array('m_username' => $_POST['m_email']);
				$employer->updateUser($post_array,'U');
				
				if($employer->m_email != $employer->m_username) {
					$post_array = array('m_email' => $_POST['m_email']);
					$employer->updateUser($post_array,'U');
				}
			}
			
			if(!empty($_POST['m_old_password']) && !empty($_POST['m_password'])) {
				$employer->changePassword($_POST['m_password'],$_POST['m_old_password']);
			}
			$to = "account/em_editprofile/";
		}
		break;
	case "deletesavedjob":
		if(Member::isLoggedin() && Recruitment::current_user_type() == "js") {
			if(isset($_GET['sjid'])) {
				$candidate = new Candidate(Member::getMid());
				$candidate->deleteSavedJob($_GET['sjid']);
			}
		}
		$to = "account/js_savedjobs/";
		break;
	case "newjob":
		if(Member::isLoggedin() && Recruitment::current_user_type() == "em") {
			$employer = new Employer(Member::getMid());
			$post_array = $_POST;
			$post_array['j_active'] = 0;
			if(isset($_GET['jid']) && $_GET['jid'] > 0) {
				$jid = $_GET['jid'];
				$job = new Job($jid);
				if($employer->canUpdateJob($jid)) { $jid = $job->updateJob($post_array); }
			} else {
				$job = new Job();
				$jid = $job->updateJob($post_array);
			}
		}
		$to = "index.php?module=account&submodule=em_addajob&jid=$jid";
		break;
	case "savejobresult":
		if(isset($_SESSION['scid'])) { $scid = $_SESSION['scid']; }
		if(isset($_POST['scid'])) { $scid = $_POST['scid']; }
		if(isset($_GET['scid'])) { $scid = $_GET['scid']; }
		if(isset($scid)) {
			$jobsearch = new JobSearch($scid);
			$jobsearch->save();
		}
		$to = "jobresults/";
		break;
	case "updatejobalert":
		$arr = $_POST;
		$jaid = "";
		if(isset($_GET['jaid']) && $_GET['jaid'] > 0) {
			$jaid = $_GET['jaid'];
		}
		$job_alert = new JobAlert($jaid);
		$job_alert->updateJobAlert($arr);
		$to = "account/js_editjobalert/" . $job_alert->jaid;
		break;
	case "deletejobalert":
		if(isset($_GET['jaid'])) {
			$jaid = $_GET['jaid'];
			$job_alert = new JobAlert($jaid);
			$job_alert->deleteJobAlert();
			$to = "account/js_savedsearches";
		}
		break;
	case "updatejobsearch":
		$arr = $_POST;
		$scid = $_GET['scid'];
		$search = new JobSearch($scid);
		$search->updateJobSearch($arr);
		$to = "?module=account&submodule=js_editsavedsearch&scid=" . $scid;
		break;
	case "deletejobsearch":
		$arr = $_POST;
		$scid = $_GET['scid'];
		$search = new JobSearch($scid);
		$search->deleteSavedSearch();
		$to = "/account/js_savedsearches/";
		break;
	
	case "apply":
		$mid = 0;
		$jid = "";
		if(isset($_GET['jid'])) {
			$jid = $_GET['jid'];
			$arr = array('a_coverletter' => $_POST['a_cl']);
			if(Member::isLoggedin('U')) {
				if(Recruitment::current_user_type() == "em") {
					$_SESSION['_msg'] = "employernotallowed";
					$_SESSION['_mtype'] = "E";
				} else {
					$mid = Member::getMid();
					$candidate = new Candidate($mid);
					$cv = $candidate->getCurrentCV();
					if($cv) $cvid = $cv->cvid;
				}
			} else {
				$candidate = new Candidate();
				$password = generatePassword(8);
				$post_array = array('m_username' => $_POST['a_email'], 'm_password' => $password, 'm_email' => $_POST['a_email'], 'm_fname' => $_POST['a_name']);
		        $mid = $candidate->updateUser($post_array,'U');
				$candidate->password = $password;
		        if($mid > 0) {
		        	$candidate->login();
		        	$candidate->sendUserRegistrationEmail();
		        }
			}
			if($mid > 0) {
				if(isset($_FILES['a_cv'])) {
					$file = $_FILES['a_cv'];
					$cvid = $candidate->updateCV($file);
				}
				$cvarr = array('cvid' => $cvid);
				$arr = array_merge($arr,$cvarr);
				
				$candidate->apply($jid,$arr);
			}
			$job = new Job($jid);
			$to = $job->link;
		}
		break;
}
if(empty($to)) $to = "home/";
if(substr($to,-1,1) != "/") $to .= "/";
header("Location: ../$to");
?>