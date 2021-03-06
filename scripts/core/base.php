<?php
/**
 * FILE: base.php
 * OBJECTIVE: Core functions for Liquid Source 
 */


/**
 * FORM BOT PROTECTIONS FUNCTIONS
 */
function checkFormBot($arr) {
	$nohtml = "";
	if($_POST['aformfield'] != "") return true;
	
	extract($arr);
	if($timecheck > 0) {
		if(isset($_POST['p_timeloaded'])) {
			if(time() - $_POST['p_timeloaded'] < $timecheck) return true;
		} else {
			return true;
		}
	}
	if($nohtml != "") { if(strlen($nohtml) != striptags(strlen($nohtml))) { return true; } }
	
	return checkBotFromUserAgent();
}
function checkBotFromUserAgent() {
	$bot_list = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi","looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory",
    "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot","crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
    "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz","Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
    "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","Spider","msnbot","AdsBot","bitlybot","Twitterbot","bingbot","YandexBot","LinkedInBot",
    "spider","Birubot","TweetmemeBot", "Exabot", "Showyoubot", "SkimWordsBot", "MLBot","SemrushBot"
    );
    foreach($bot_list as $bot) {
        if(strpos($_SERVER['HTTP_USER_AGENT'],$bot) > 0 || strpos($_SERVER['HTTP_USER_AGENT'],$bot) === 0) { return true; }
    }
    return false;
}

/**
 * MISCELANIOUS HELPER FUNCTIONS
 */
function lr_urlencode($str) { return str_replace("%2F","",str_replace("+", "_", urlencode($str))); } 
function projectParsers($newf) {
	if(USE_FORM_PARSER) {
		$newf = str_replace("</form>",
							"
							<input type=\"hidden\" id=\"p_timeloaded\" name=\"p_timeloaded\" value=\"" . time() . "\" />
							<input type=\"text\" id=\"aformfield\" name=\"aformfield\" value=\"\" class=\"dnone\" />
							</form>
							",
							$newf);
	}
	return $newf;
}
function showAlertMessage() {
	$mtype = "";
	$msg = "";
	if(isset($_SESSION['_mtype'])) { $mtype = $_SESSION['_mtype']; }
	if(isset($_SESSION['_msg'])) { $msg = $_SESSION['_msg']; }
	if($msg != "" && $mtype != "") {
		return getAlertMessage($mtype,$msg);
	}
}
function getAlertMessage($mtype,$msg_e) {
	$msg = "";
    if($msg_e != "") {
        if($mtype == "W") { $msg = "<h4 class=\"alert_warning alert\">"; }
        if($mtype == "E") { $msg = "<h4 class=\"alert_error alert\">"; }
        if($mtype == "S") { $msg = "<h4 class=\"alert_success alert\">"; }
        $rw = mgr("select aw_warning from " . DB_TBL_ALERT_WARNINGS . " where aw_slug='" . $msg_e . "'");
        if($rw != "") {  $msg .= stripslashes($rw['aw_warning']); }
        $msg .= "</h4>";
		
	    $_SESSION['_mtype'] = "";
	    $_SESSION['_msg'] = "";
    }
    return $msg;
}
function strToSlug($str,$module=NULL,$uid=NULL,$i=NULL){
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-_]/', '-', $str);
    $str = preg_replace('/-+/', "-", $str);
	
	if(!empty($module)) {
		switch ($module) {
			case "page":
				$str_tocheck = $str;
				if($i != NULL) $str_tocheck = $str . "_" . $i;
				$rs = mq("select pg_slug from " . DB_TBL_PAGES . " where pg_slug='$str_tocheck' and pgid <> '$uid' and pg_parent='0'");
				if(mnr($rs) > 0) {
					if($i == NULL) $i=0;
					$i++;
					return strToSlug($str,$module,$uid,$i);
				}
				$str = $str_tocheck;
				break;
			case "post":
				$str_tocheck = $str;
				if($i != NULL) $str_tocheck = $str . "_" . $i;
				$rs = mq("select p_slug from " . DB_TBL_POSTS . " where p_slug='$str_tocheck' and pid <> '$uid' and p_parent='0'");
				if(mnr($rs) > 0) {
					if($i == NULL) $i=0;
					$i++;
					return strToSlug($str,$module,$uid,$i);
				}
				$str = $str_tocheck;
				break;
		}
	}
    return $str;
}
function php_error_reporting($tf = true) {
	if($tf) {
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
	}
}
function log_me($sc,$info=NULL) {
	if(SITE_LOGGING) {
		$mid = Member::getMid();
		$ip = getRealIpAddr();
		$ua = $_SERVER['HTTP_USER_AGENT'];
		$rs = mq("insert into " . DB_TBL_SITE_LOG . " (sl_ip,sl_useragent,mid,sl_sc,sl_info) values ('$ip','$ua','$mid','$sc','$info')");
	}
}
function checkLicenceKey() {
	$error = true;
	if(!defined('LICENCE_KEY_HOST_KEY')) {
		$host_key = lc_getHostKey();
		$rs = mq("insert into " . DB_TBL_SITE_OPTIONS . " (so_arg,so_val,so_updatedate,so_type,so_userid,so_group,so_field_type,so_help_text) values ('LICENCE_KEY_HOST_KEY','$host_key','" . DB_SAFE_DATETIME . "','define','0','Licence','','')");
		define('LICENCE_KEY_HOST_KEY',$host_key);
	}
	$hashofkey = hash('sha256', LICENCE_KEY_HOST_KEY . LICENCE_KEY);
	if($hashofkey == "74b43d389c826aa539ce933bbcdbabbbf687725d606c0d480be9996b47ba5e2b") {
		$error = false;
	}
	if(!$error) return true;
	echo "Error in licence key activation";
	die;
}
function lc_getHostKey() {
	return "Going to server and getting key";
}

/**
 * TABLE HELPER FUNCTIONS
 */
function getPages($arr) { return LS::pages($arr); }
function getPosts($arr) { return LS::posts($arr); }
function getTemplates($arr) { return LS::templates($arr); }
function getLinks($arr) { return LS::links($arr); }
function getCategories($arr) { return LS::categories($arr); }
function getMembers($arr) { return LS::members($arr); }
function getMedia($arr) { return LS::media($arr); }

/**
 * CATEGORY HELPER FUNCTIONS
 */
function getCategoryArray($uid,$l_type='post') {
	$ret_arr = array();
	$rs = mq("select distinct cid from " . DB_TBL_CATEGORY_LINK . " where uid='$uid' and l_type='$l_type'");
	while($rw = mfa($rs)) {
		$ret_arr[] = new Category($rw['cid']);
	}
	return $ret_arr;
}
function getCategoryNames($uid,$l_type='post',$seperator=',') {
	$ret = "";
	$cats = getCategoryArray($uid,$l_type);
	foreach($cats as $cat) {
		$ret .= $cat->c_name . $seperator . " ";
	}
	if(strlen($ret) > 0) {
		$seplen = (strlen($seperator) + 1) * -1; 
		return substr($ret,0,$seplen);
	}
	return ;
		
}
function inCategory($catids,$uid,$l_type='post') {
	$cat_arr = explode(",",$catids);
	foreach($cat_arr as $catid) {
		$catid = trim($catid);
		if($catid != "") {
			$rs = mq("select jcid from " . DB_TBL_CATEGORY_LINK . " where uid='$uid' and l_type='$_ltype' and cid='$catid'");
			if(mnr($rs) > 0) {
				return true;
			}
		}
	}
	return false;
}
function updateCategoryLink($cids,$uid,$l_type) {
	if(!is_array($cids)) { $cids[] = $cids; }
	$xsql = "";
	foreach($cids as $cid) {
		$rs = mq("select * from " . DB_TBL_CATEGORY_LINK . " where cid='$cid' and uid='$uid' and l_type='$l_type'");
	    if(mnr($rs) == 0) {
	        $rsi = mq("insert into " . DB_TBL_CATEGORY_LINK . " (uid,cid,l_type) values ('$uid','$cid','$l_type')");
	    }
		$xsql .= " cid != '$cid' and ";
	}
	
	$rs = mq("select * from " . DB_TBL_CATEGORY_LINK . " where ($xsql 1=1) and l_type='$l_type' and uid='$uid'");
	while($rw = mfa($rs)) {
		$rsd = mq("delete from " . DB_TBL_CATEGORY_LINK . " where jcid='" . $rw['jcid'] . "'");
	}
}
function showCategoryOption($category,$lvl=1,$cur_category=NULL,$hide=false,$selectparent=false) {
	$catid = "";
	if(!is_array($cur_category) && isset($cur_category)) {
		$chosen_uid = $cur_category->c_parent;
		$catid = $cur_category->cid;
	}
	
	
	$cid = $category->cid;
	$ret = "";
	
	$dots = "";
	for($i=0;$i<$lvl;$i++) { $dots .= "-  "; }
	$ret .= "<option value=\"$cid\"";
	if($catid == $cid && $hide) {
		$ret .= " disabled ";
	}
	
	if(is_array($cur_category)) {
		foreach($cur_category as $cat) {
			$chuid = $cat->cid;
			if($cid == $chuid) { $ret .= " selected "; }
		}
	} else {
		if($selectparent) {
			$parent = $cur_category->c_parent;
			if($parent == $category->cid) {
				$ret .= " selected ";
			}
		} else {
			if(isset($cur_category) && $cid == $cur_category->cid) { $ret .= " selected "; }
		}
	}
	$ret .= ">" . $dots . $category->c_name . "</option>";
    if($category->c_children) {
    	$lvl++;
    	foreach($category->c_children as $child_category) {
    		$ret .= showCategoryOption($child_category,$lvl,$cur_category,$hide,$selectparent);
    	}
	}
	return $ret;
}


/**
 * META DATA HELPER FUNCTIONS
 */
function insertMetaData($arg,$val,$uid,$typee,$ident) {
	if($arg != "") {
		$arg = mres($arg);
		$val = mres($val);
		$rw = mgr("select mdid from " . DB_TBL_META_DATA . " where md_recogniser='$ident' and uid='$uid' and md_type='$typee'");
		if($rw != "") {
			$mdid = $rw['mdid'];
			$rsi = mq("update " . DB_TBL_META_DATA . " set md_arg='$arg', md_val='$val' where mdid='$mdid'");
		} else {
			$rsi = mq("insert into " . DB_TBL_META_DATA . " (md_arg,md_val,uid,md_type,md_recogniser) values ('$arg','$val','$uid','$typee','$ident')");
		}
	} else {
		if($val == "") {
			$rw = mgr("select mdid from " . DB_TBL_META_DATA . " where md_recogniser='$ident' and uid='$uid' and md_type='$typee'");
			if($rw != "") {
				$rsd = mq("delete from  " . DB_TBL_META_DATA . " where mdid='" . $rw['mdid'] . "'");
			}
		}
	}
}
function getMetaData($uid,$typee) {
	$i=0;
	$ret_arr = array();
	$rs = mq("select md_val,md_arg from " . DB_TBL_META_DATA . " where uid='$uid' and md_type='$typee' order by md_recogniser");
	while($rw = mfa($rs)) {
		$ret_arr[stripslashes($rw['md_arg'])] = stripslashes($rw['md_val']);
		$i++;
	}
	return $ret_arr;
}


/**
 * PAGE STRUCTURE FUNCTIONS
 */
 
/* Called at the top of every page. Gets the current module or default one if a module is not passed */
function getModule($module) {
    if(empty($module)) {
        $rs = mq("select pg_slug from " . DB_TBL_PAGES . " where isDefault='1' and isAdmin='0' order by pgid asc");
        if(mnr($rs) > 0) {
        	$rw = mfa($rs);
        	$module = $rw['pg_slug']; 
		} else { echo "Error 101. No defualt module chosen."; die; }
    }
	
	if(Member::isLoggedin('A')) return $module;
	$page = new Page($module);
	if($page->pgid == NULL) return "404";
	return $module;
}

/* The main body of the page is passed through this function. It takes a module as a parameter and ensures that the file exists. */
function getModuleData($module) {
	global $inAdmin;
	$page = new Page($module);
	$m_type = "bs";
	if($page->pgid != "") {
		$m_type = $page->pg_type;
	}
	/* Plugin option */
	$plugin_code = "page_structure.module";
	include(INCLUDE_PLUGIN_ROOT . "core.php");
	
	if($m_type == "bs") {
		$fname = "modules/$module.php";
		if($inAdmin) {
			if(!file_exists($fname)) {
				$fname = "../scripts/extensions/niche/" . NICHE_SITE . "/" . $module . ".php";
				if(!file_exists($fname)) {
					$fname = "../scripts/extensions/plugins/" . $module . ".php";
				}	
			}
		}
		if(file_exists($fname)) { $incFile = $fname; }
		else { echo "<p>Error finding module <i>" . $module . ".php</i><p>"; }
	} else {
		$incFile = "modules/text.php";
	}
	
	if(!empty($incFile)) {
		ob_start();
		include($incFile);
		$f = ob_get_clean();
		$newf = projectParsers($f);
		echo $newf;
	}
}

/* A function to get the meta information from the page you are currently on */
function getMetaInfo($module,$ret) {
	if(defined($module . "_" . $ret)) return constant($module . "_" . $ret);
	
	$to_return = "";
	switch($module) {
		case "job":
			$jid = ""; if(isset($_GET['jid'])) $jid = $_GET['jid'];
			if($jid != "") {
				$job = new Job($jid);
				switch ($ret) {
					case "title":
					case "description":
						$to_return = $job->j_title . " in " . $job->getPublicAddress();
						break;
					case "keywords":
						$to_return = $job->j_title . ", " . $job->getPublicAddress() . ", " . $job->j_title . " jobs, job, job listing, job posting, recruitment";
						break;
				}
			}
			break;
		case "jobresults":
			$to_return = getBaseMetaInfo($module,$ret);
			break;
		default:
			$to_return = getBaseMetaInfo($module,$ret);
			break;
	}
	
	if(!defined($module . "_" . $ret)) define($module . "_" . $ret,$to_return);
	return $to_return;
}

/* Used to add in which parsers are used to the javascript file, so javascript functions can call on the variables */
function getBaseMetaInfo($module,$ret) {
	$rs = mq("select pg_meta_$ret as txt from " . DB_TBL_PAGES . " where pg_slug='$module'");
    if(mnr($rs)>0) {
        $rw = mfa($rs); 
    	return stripslashes($rw['txt']);
    } else {
        if($module == "home") { } else { return getBaseMetaInfo("home",$ret); }
    }
}
function getParserJavascript() {
	$ret = "";
	$rs = mq("select * from " . DB_TBL_SITE_OPTIONS . " where (so_group='Modules' or so_group='APIs') and so_field_type='bool'");
	while($rw = mfa($rs)) {
		$ret .= "var " . strtolower($rw['so_arg']) . " = " . $rw['so_val'] . ";";
	}
	return $ret;
}
/* This checks if any css (or less) files have changed and creates a new minified css file if needed */
function createMergedCSS() {
	$css_name_a = md5_of_dir('css/core/');
	$css_name_b = md5_of_dir('css/core/less_incs/');
	$css_name_c = md5_of_dir('css/packages/');
	$css_name = md5($css_name_a . $css_name_b . $css_name_c);
	if(!file_exists("uploads/js_css_cache/" . $css_name . ".css" ) || FORCE_RECREATE == "1") {
		delete_old_md5s_css("uploads/js_css_cache/");
		
		require 'scripts/core/packages/lessphp/lessc.inc.php';
			$less = new lessc('css/core/print.less');
			file_put_contents('uploads/js_css_cache/print.css', $less->parse());
		
		$str = file_get_contents("css/core/reset.css");
		
		foreach(glob('css/core/*.less') as $cssfile) {
			if(substr($cssfile,9,4) != "mod_" && substr($cssfile,9,1) != "_" &&  substr($cssfile,9,10) != "print.less") {
				$newlocation = 'uploads/js_css_cache/_' . substr($cssfile,9,-5) . '.css';
				$less = new lessc($cssfile);
				file_put_contents($newlocation, $less->parse());
				$str .= file_get_contents($newlocation);
			}
		}
		
		if(USE_TABLE_PARSER) { $str .= file_get_contents("css/packages/dataTables.css"); }
		if(USE_TOOLTIPS) { $str .= file_get_contents("css/packages/tooltips.css"); }
		if(USE_SHADOWBOX) { $str .= file_get_contents("css/packages/shadowbox.css"); }
		if(USE_FLEX_SLIDER) { $str .= file_get_contents("css/packages/flexslider.css"); }
		
		$fh = fopen("uploads/js_css_cache/$css_name.css","w");
		fwrite($fh,$str);
	}
	return $css_name;
}
/* This checks if any js files have changed and creates a new minified js file if needed */
function createMergedJS() {
	$js_name_a = md5_of_dir('js/core/');
	$js_name_b = md5_of_dir('js/libs/');
	$js_name = md5($js_name_a . $js_name_b);
	if(!file_exists("uploads/js_css_cache/" . $js_name . ".js") || FORCE_RECREATE == "1") {
		delete_old_md5s_js("uploads/js_css_cache/");
		$str = getParserJavascript();
		
		foreach(glob('js/core/*.js') as $jsfile) {
			if(substr($jsfile,8,4) != "mod_") {
				$str .= file_get_contents($jsfile);
			}
		}
		
		if(USE_AJAXINCLUDE) { $str .= file_get_contents("js/libs/ajaxInclude.js"); }
		if(USE_TOOLTIPS) { $str .= file_get_contents("js/libs/tooltips.js"); }
		if(USE_SHADOWBOX) { $str .= file_get_contents("js/libs/shadowbox.js"); }
		if(USE_FLEX_SLIDER) { $str .= file_get_contents("js/libs/jquery.flexslider-min.js"); }
		
		include_once("scripts/core/classes/js_minify.php");
		$js = JSMin::minify($str);
		
		$fh = fopen("uploads/js_css_cache/$js_name.js","w");
		fwrite($fh,$js);
		
		$rs = mq("update " . DB_TBL_SITE_OPTIONS . " set so_val='false' where so_arg='FORCE_RECREATE'");
	}
	return $js_name;
}

function md5_of_dir($folder) {
	$dircontent = scandir($folder);
	$ret='';
	foreach($dircontent as $filename) {
		if ($filename != '.' && $filename != '..') {
			if (filemtime($folder.$filename) === false) return false;
			$ret.=date("YmdHis", filemtime($folder.$filename)).$filename;
		}
	}
	return md5($ret);
}
function delete_old_md5s_js($folder) {
	$olddate=time() - 60;
	$dircontent = scandir($folder);
	foreach($dircontent as $filename) {
		if (strlen($filename)==35 && filemtime($folder.$filename)<$olddate) {
			unlink($folder.$filename);
    	}
	}
}
function delete_old_md5s_css($folder) {
	$olddate=time() - 60;
	$dircontent = scandir($folder);
	foreach($dircontent as $filename) {
		if (strlen($filename)==36 && filemtime($folder.$filename)<$olddate) {
			unlink($folder.$filename);
    	}
	}
}

?>