<?php
/**
 * FILE: base.php
 * OBJECTIVE: Core functions for Liquid Source 
 */


/**
 * FORM BOT PROTECTIONS FUNCTIONS
 */
function checkFormBot($arr) {
	if($_GET['g_timeloaded'] != $_POST['p_timeloaded']) return true;
	if($_POST['aformfield'] != "") return true;
	
	extract($arr);
	if($timecheck > 0) { if(time() - $_GET['p_timeloaded'] < $timecheck) return true; }
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
function strToSlug($str,$module=NULL,$uid=NULL){
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    $str = preg_replace('/-+/', "-", $str);
	
	if(!empty($module)) {
		switch ($module) {
			case "page":
				$rs = mq("select pg_slug from " . DB_TBL_PAGES . " where pg_slug='$str' and pgid <> '$uid' and pg_parent='0'");
				if(mnr($rs) > 0) {
					return strToSlug($str . rand(0,100),$module,$uid);
				}
				break;
			case "post":
				$rs = mq("select p_slug from " . DB_TBL_POSTS . " where p_slug='$str' and pid <> '$uid' and p_parent='0'");
				if(mnr($rs) > 0) {
					return strToSlug($str . rand(0,100),$module,$uid);
				}
				break;
		}
	}
    return $str;
}
function log_me($sc,$info=NULL) {
	$mid = getMid();
	$ip = getRealIpAddr();
	$ua = $_SERVER['HTTP_USER_AGENT'];
	$rs = mq("insert into " . DB_TBL_SITE_LOG . " (sl_ip,sl_useragent,mid,sl_sc,sl_info) values ('$ip','$ua','$mid','$sc','$info')");
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
function delete_old_md5s($folder) {
	$olddate=time() - 60;
	$dircontent = scandir($folder);
	foreach($dircontent as $filename) {
		if (strlen($filename)==35 && filemtime($folder.$filename)<$olddate) {
			unlink($folder.$filename);
    	}
	}
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
 * DATABASE HELPER FUNCTIONS
 */
function getFieldValue($sql,$arg='n') {
	$rs = mq($sql);
	if(mnr($rs) > 0) {
		if(mnr($rs) > 1) {
			while($rw=mfa($rs)) {
				$ret_arr[] = $rw[$arg];
			}
			return $ret_arr;
		} else {
			$rw = mfa($rs);
			return $rw[$arg];
		}
	}
	return "";
}
?>