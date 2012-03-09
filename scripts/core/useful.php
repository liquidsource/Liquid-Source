<?php
function getExtension($str) {
    $i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}
function curPageURL() {
     $pageURL = 'http';
     if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
     $pageURL .= "://";
     if ($_SERVER["SERVER_PORT"] != "80") { $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
     } else { $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; }
     return $pageURL;
}
function removeURLS($str,$replacement="[removed]") { 
    $str = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', $replacement, $str);
    $str = preg_replace('/\b(www)\.[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', $replacement, $str);
    return $str;
}
function URLtoHyperlink($url) { return preg_replace("#http://([A-z0-9./-]+)#", '<a href="$1">$0</a>', $url); }
function highlight($sString, $aWords) {
    if (!is_array ($aWords) || empty ($aWords) || !is_string ($sString)) { return false; }
    $sWords = implode ('|', $aWords);
    return preg_replace ('@\b('.$sWords.')\b@si', '<strong style="background-color:yellow">$1</strong>', $sString);
}
function genRandomString($length=NULL) {
    if(empty($length)) $length = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $string = '';    
    for ($p = 0; $p < $length; $p++) { $string .= $characters[mt_rand(0, strlen($characters))]; }
    return $string;
}
function generatePassword($length=9, $strength=0) {
    $vowels = 'aeuy';
    $consonants = 'bdghjmnpqrstvz';
    if ($strength >= 1) { $consonants .= 'BDGHJLMNPQRSTVWXZ'; }
    if ($strength >= 2) { $vowels .= "AEUY"; }
    if ($strength >= 4) { $consonants .= '23456789'; }
    if ($strength >= 8 ) { $vowels .= '@#$%'; }
    $password = '';
    $alt = time() % 2;
    for ($i = 0; $i < $length; $i++) {
        if ($alt == 1) {
            $password .= $consonants[(rand() % strlen($consonants))];
            $alt = 0;
        } else {
            $password .= $vowels[(rand() % strlen($vowels))];
            $alt = 1;
        }
    }
    return $password;
}
function timeAgo($time) {
    $delta = time() - $time;
    if ($delta < 60) {
        return 'less than a minute ago';
    } else if ($delta < 120) {
        return 'about a minute ago.';
    } else if ($delta < (45 * 60)) {
        return floor($delta / 60) . ' minutes ago';
    } else if ($delta < (90 * 60)) {
        return 'about an hour ago';
    } else if ($delta < (24 * 60 * 60)) {
        return 'about ' . floor($delta / 3600) . ' hours ago';
    } else if ($delta < (48 * 60 * 60)) {
        return '1 day ago.';
    } else if ($delta / 86400 < 300) {
        return floor($delta / 86400) . ' days ago';
    } else  if ($delta / 86400 < 365) {
        return "About a year ago";
    } else {
        return "About " . round($delta / 86400 / 365) . " years ago";
    }
}
function makeDisplayDate($datey) { return date("D jS M Y",strtotime($datey)); }
function isIE6() { $using_ie6 = (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.') !== FALSE); return $using_ie6; }
function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) { $ip=$_SERVER['HTTP_CLIENT_IP']; }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { $ip=$_SERVER['HTTP_X_FORWARDED_FOR']; }
    else { $ip=$_SERVER['REMOTE_ADDR']; }
    return $ip;
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
		if (strlen($filename)==36 && filemtime($folder.$filename)<$olddate) {
			unlink($folder.$filename);
    	}
	}
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

?>
