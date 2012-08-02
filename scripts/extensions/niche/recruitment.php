<?php
/* Define database tables */
if(!defined("DB_TBL_JOBS")) define('DB_TBL_JOBS', TABLE_PREFIX . 'r_jobs');
if(!defined("DB_TBL_COUNTRIES")) define('DB_TBL_COUNTRIES', TABLE_PREFIX . 'r_countries');
if(!defined("DB_TBL_JOB_PROPERTIES_MASTER")) define('DB_TBL_JOB_PROPERTIES_MASTER', TABLE_PREFIX . 'r_job_properties_master');
if(!defined("DB_TBL_JOB_PROPERTIES")) define('DB_TBL_JOB_PROPERTIES', TABLE_PREFIX . 'r_job_properties');
if(!defined("DB_TBL_COMPANIES")) define('DB_TBL_COMPANIES', TABLE_PREFIX . 'r_companies');
if(!defined("DB_TBL_APPLICATIONS")) define('DB_TBL_APPLICATIONS', TABLE_PREFIX . 'r_applications');
if(!defined("DB_TBL_SELECTBOX")) define('DB_TBL_SELECTBOX', TABLE_PREFIX . 'r_selectbox');
if(!defined("DB_TBL_SAVED_JOBS")) define('DB_TBL_SAVED_JOBS', TABLE_PREFIX . 'r_saved_jobs');
if(!defined("DB_TBL_MEMBER_CV")) define('DB_TBL_MEMBER_CV', TABLE_PREFIX . 'r_member_cv');
if(!defined("DB_TBL_COMPANIES")) define('DB_TBL_COMPANIES', TABLE_PREFIX . 'r_companies');
if(!defined("DB_TBL_GEO_CACHE")) define('DB_TBL_GEO_CACHE', TABLE_PREFIX . 'r_geo_cache');
if(!defined("DB_TBL_JOB_ALERT")) define('DB_TBL_JOB_ALERT', TABLE_PREFIX . 'r_job_alert');
if(!defined("DB_TBL_JOB_SEARCH")) define('DB_TBL_JOB_SEARCH', TABLE_PREFIX . 'r_job_search');


/* Recruitment Niche Classes */
include(INCLUDE_NICHE_ROOT . "recruitment/classes/job.php");
include(INCLUDE_NICHE_ROOT . "recruitment/classes/cv.php");
include(INCLUDE_NICHE_ROOT . "recruitment/classes/company.php");
include(INCLUDE_NICHE_ROOT . "recruitment/classes/job_member.php");
include(INCLUDE_NICHE_ROOT . "recruitment/classes/application.php");
include(INCLUDE_NICHE_ROOT . "recruitment/classes/job_alert.php");
include(INCLUDE_NICHE_ROOT . "recruitment/classes/job_search.php");
include(INCLUDE_NICHE_ROOT . "recruitment/classes/country.php");


/* Global arrays that are used to hold current possible job properties */
$GLOBALS['job_all_fields'] = array();
$GLOBALS['job_all_properties'] = array();
createJobArrays();


function createJobArrays() {
	$job_cols = array();
	$rs = mq("SELECT COLUMN_NAME as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . DB_TBL_JOBS . "'");
	while($rw = mfa($rs)) { $job_cols[] = $rw['c']; }
	$job_properties = array();
	$rs = mq("SELECT jpid, jp_inputname FROM " . DB_TBL_JOB_PROPERTIES_MASTER);
	while($rw = mfa($rs)) {
		$job_properties[$rw['jpid']] = $rw['jp_inputname'];
	}

	
	$GLOBALS['job_all_fields'] = $job_cols;
	$GLOBALS['job_all_properties'] = $job_properties;
}

function getJobs($arr) {
	return Job::getJobs($arr);
}
function getCompanies($arr) {
	return Company::getCompanies($arr);
}
function checkIfStrIsDate($str) {
	if(is_string($str)) {
		$formats = array("d.m.Y", "d/m/Y", "Ymd", "Y-m-d", "d-m-Y");
		foreach ($formats as $format) {
			$date = DateTime::createFromFormat($format, $str);
			if ($date == false || !(date_format($date,$format) == $str) ) { }
			else { return date("Y-m-d H:i:s",strtotime($str)); }
	   	}
	}
	return $str;
}
function getJPID($in) {
	$arr = $GLOBALS['job_all_properties'];
	while ($arr_in = current($arr)) {
	    if ($arr_in == $in) {
	        return key($arr);
	    }
	    next($arr);
	}
	return null;
}
?>