<?php
include("../../scripts/system.php");
include("../scripts/ad_functions.php");

$action = $_GET['action'];
$to = $_GET['to'];

if(Member::isAdminLoggedIn()) {
    switch ($action) {
        case "logout":
            Member::logout();
            header("Location: ../../index.php");
            die;
            break;
        case "newpage":
			$page = new Page('',$_GET['pgid']);
			$pgid = $page->updatePage($_POST);
        	$to = "newpage&pgid=$pgid";
            break;
        case "deletepage":
			$page = new Page('',$_GET['pgid']);
			$page->deletePage();
            $to = "editpages";
            break;
        case "newpost":
			$post = new Post('',$_GET['pid']);
			$pid = $post->updatePost($_POST);
        	$to = "newpost&pid=$pid";
            break;
        case "deletepost":
			$post = new Post('',$_GET['pid']);
			$post->deletePost();
            $to = "editpost";
            break;
        case "newtemplate":
			$template = new Template('',$_GET['pid']);
			$pid = $template->updateTemplate($_POST);
        	$to = "newtemplate&pid=$pid";
            break;
        case "deletetemplate":
			$template = new Template('',$_GET['pid']);
			$template->deleteTemplate();
            $to = "edittemplates";
            break;
        case "newlink":
            $link = new Link($_GET['lid']);
			$lid = $link->updateLink($_POST);
        	$to = "newlink&lid=$lid";
            break;
        case "deletelink":
			$link = new Link($_GET['lid']);
			$link->deleteLink();
            $to = "editlinks";
            break;
        case "newmedia":
            $media = new Media($_GET['mdid']);
			$mdid = $media->updateMedia($_POST,$_FILES,'../../');
        	$to = "newmedia&mdid=$mdid";
            break;
        case "deletemedia":
			$media = new Media($_GET['mdid']);
			$media->deleteMedia();
            $to = "editmedia";
            break;
        case "newcategory":
			$c_type = $_GET['c_type'];
            $category = new Category($_GET['cid']);
			$cid = $category->updateCategory($_POST,$c_type);
        	$to = $c_type . "categories&cid=$cid";
            break;
        case "deletecategory":
			$c_type = $_GET['c_type'];
            $category = new Category($_GET['cid']);
			$category->deleteCategory();
            $to = $c_type . "categories";
            break;
        case "newuser":
            $member = new Member($_GET['mid']);
			$mid = $member->updateUser($_POST);
			$to = "newuser&mid=$mid";
            break;
        case "deleteuser":
            $member = new Member($_GET['mid']);
			$member->deleteMember();
            $to = "editusers";
            break;
		case "updateSiteOptions":
			foreach($_POST as $arg => $val) {
				$rsu = mq("update " . DB_TBL_SITE_OPTIONS . " set so_val='$val' where so_arg='$arg'");
			}
			$rsi = mq("update " . DB_TBL_SITE_OPTIONS . " set so_val='true' where so_arg='FORCE_RECREATE'");
			$_SESSION['_mtype'] = "S";
			$_SESSION['_msg'] = "newsiteoptions";
			$to = "editsiteoptions";
			break;
    }
    header("Location: ../index.php?module=$to");
	die;
} else {
    if($action == "login") {
        $m_username = mres($_POST['m_username']);
        $password = $_POST['m_password'];
		
		$member = new Member();
		$member->checkLogin($m_username,$password,'A','../index.php?module=login','../index.php?module=ad_home');
	}
}
header("Location: ../index.php?module=login");
?>
