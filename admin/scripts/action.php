<?php
include("../../scripts/system.php");
include("../scripts/ad_functions.php");

$to = "";
$action = $_GET['action'];
if(isset($_GET['to'])) $to = $_GET['to'];
if(Member::isLoggedin('A')) {
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
        	/* Plugin option */
			$plugin_code = "admin.action.page.new"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
        case "deletepage":
			$page = new Page('',$_GET['pgid']);
			$page->deletePage();
            $to = "editpages";
        	/* Plugin option */
			$plugin_code = "admin.action.page.delete"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
		case "restorepage":
			$page = new Page('',$_GET['pgid']);
			$page->restorePage();
            $to = "editpages";
        	/* Plugin option */
			$plugin_code = "admin.action.page.restore"; include(INCLUDE_PLUGIN_ROOT . "core.php");
			break;
		case "removepage":
			$page = new Page('',$_GET['pgid']);
			$page->removePage();
            $to = "editpages";
        	/* Plugin option */
			$plugin_code = "admin.action.page.remove"; include(INCLUDE_PLUGIN_ROOT . "core.php");
			break;
        case "newpost":
			$post = new Post($_GET['pid']);
			$pid = $post->updatePost($_POST);
        	$to = "newpost&pid=$pid";
        	/* Plugin option */
			$plugin_code = "admin.action.post.new"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
        case "deletepost":
			$post = new Post($_GET['pid']);
			$post->deletePost();
            $to = "editposts";
        	/* Plugin option */
			$plugin_code = "admin.action.post.delete"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
        case "newtemplate":
			$template = new Template($_GET['pid']);
			$pid = $template->updateTemplate($_POST);
        	$to = "newtemplate&pid=$pid";
        	/* Plugin option */
			$plugin_code = "admin.action.template.new"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
        case "deletetemplate":
			$template = new Template($_GET['pid']);
			$template->deleteTemplate();
            $to = "edittemplates";
        	/* Plugin option */
			$plugin_code = "admin.action.template.delete"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
        case "newlink":
            $link = new Link($_GET['lid']);
			$lid = $link->updateLink($_POST);
        	$to = "newlink&lid=$lid";
        	/* Plugin option */
			$plugin_code = "admin.action.link.new"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
        case "deletelink":
			$link = new Link($_GET['lid']);
			$link->deleteLink();
            $to = "editlinks";
        	/* Plugin option */
			$plugin_code = "admin.action.link.delete"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
        case "newmedia":
            $media = new Media($_GET['mdid']);
			$mdid = $media->updateMedia($_POST,$_FILES,'../../');
        	$to = "newmedia&mdid=$mdid";
        	/* Plugin option */
			$plugin_code = "admin.action.media.new"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
        case "deletemedia":
			$media = new Media($_GET['mdid']);
			$media->deleteMedia();
            $to = "editmedia";
        	/* Plugin option */
			$plugin_code = "admin.action.media.delete"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
        case "newcategory":
			$c_type = $_GET['c_type'];
            $category = new Category($_GET['cid']);
			$cid = $category->updateCategory($_POST,$c_type);
        	$to = $c_type . "categories&cid=$cid";
        	/* Plugin option */
			$plugin_code = "admin.action.category.new"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
        case "deletecategory":
			$c_type = $_GET['c_type'];
            $category = new Category($_GET['cid']);
			$category->deleteCategory();
            $to = $c_type . "categories";
        	/* Plugin option */
			$plugin_code = "admin.action.category.delete"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
        case "newuser":
            $member = new Member($_GET['mid']);
			$mid = $member->updateUser($_POST);
			$to = "newuser&mid=$mid";
        	/* Plugin option */
			$plugin_code = "admin.action.user.new"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
        case "deleteuser":
            $member = new Member($_GET['mid']);
			$member->deleteMember();
            $to = "editusers";
        	/* Plugin option */
			$plugin_code = "admin.action.user.delete"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            break;
		case "updateSiteOptions":
			foreach($_POST as $arg => $val) {
				$rsu = mq("update " . DB_TBL_SITE_OPTIONS . " set so_val='$val' where so_arg='$arg'");
			}
			$rsi = mq("update " . DB_TBL_SITE_OPTIONS . " set so_val='true' where so_arg='FORCE_RECREATE'");
			$_SESSION['_mtype'] = "S";
			$_SESSION['_msg'] = "newsiteoptions";
			$to = "editsiteoptions";
        	/* Plugin option */
			$plugin_code = "admin.action.siteoptions.update"; include(INCLUDE_PLUGIN_ROOT . "core.php");
			break;
		case "newmemberprofile":
			foreach($_POST as $arg => $val) { $$arg = mres($val); }
			$rs = mq("insert into " . DB_TBL_MEMBER_PROFILE_MASTER . " (mpa_english, mpa_sc, mpa_inputtype) values ('$mpa_english', '$mpa_sc', '$mpa_inputtype')");
			$to = "editmemberprofile";
			break;
		case "deletememberprofile":
			$mpaid = $_GET['mpaid'];
			$rw = mfa(mq("select mpa_sc from " . DB_TBL_MEMBER_PROFILE_MASTER . " where mpaid='$mpaid'"));
			$mpasc = $rw['mpa_sc'];
			$rs = mq("delete from " . DB_TBL_MEMBER_PROFILE_MASTER . " where mpaid='$mpaid'");
			$rs = mq("delete from " . DB_TBL_MEMBER_PROFILE . " where mpa_sc='$mpasc'");
			$to = "editmemberprofile";
			break;
		case "updatememberprofile":
			$rs = mq("select * from " . DB_TBL_MEMBER_PROFILE_MASTER);
			while($rw = mfa($rs)) {
				$mpaid = $rw['mpaid'];
				$mpae = mres($_POST['mpa_english_' . $mpaid]);
				$mpas = mres($_POST['mpa_sc_' . $mpaid]);
				$mpai = mres($_POST['mpa_inputtype_' . $mpaid]);
				$rsu = mq("update " . DB_TBL_MEMBER_PROFILE_MASTER . " set mpa_english='$mpae', mpa_sc='$mpas', mpa_inputtype='$mpai' where mpaid='$mpaid'");
				
				$os = $_POST['mpa_options_' . $mpaid];
				if($mpai == "select" && $os != "") {
					$rsd = mq("delete from " . DB_TBL_SELECTBOX . " where s_type='$mpas'");
					$sl_arr = explode(";",$os);
					foreach($sl_arr as $sl) {
						$sl = trim(mres($sl));
						if($sl != "") {
							$rsi = mq("insert into " . DB_TBL_SELECTBOX . " (s_type,s_val,s_default) values ('$mpas','$sl','0')");
						}
					}
				}
			}
			break;
		default:
			/* Plugin option */
			$plugin_code = "admin.action";
			include(INCLUDE_PLUGIN_ROOT . "core.php");
			break;
		
    }
    header("Location: ../index.php?module=$to");
	die;
} else {
    if($action == "login") {
        $m_username = mres($_POST['m_username']);
        $password = $_POST['m_password'];
		
		$mid = Member::checkLogin($m_username,$password,'A');
		if($mid > 0) {
			$member = new Member($mid);
			$member->login();
			header("Location: ../index.php?module=home");
			die;
		}
	}
}
header("Location: ../index.php?module=login");
?>
