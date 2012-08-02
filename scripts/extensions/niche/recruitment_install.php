<?php
function recruitment_install() {
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
	
	/* Liquid Pharmaceutical */
	$create_sql = "
	
	CREATE TABLE `" . DB_TBL_COUNTRIES . "` (
	  `iso` char(2) NOT NULL,
	  `lang_code` char(2) NOT NULL,
	  `name` varchar(200) NOT NULL,
	  `printable_name` varchar(200) NOT NULL,
	  `numcode` int(11) NOT NULL,
	  `isDefault` int(11) NOT NULL,
	  `isActive` int(11) NOT NULL,
	  `flagImg` varchar(100) NOT NULL,
	  `englishLanguage` varchar(200) NOT NULL,
	  `isCommon` int(11) NOT NULL DEFAULT '0',
	  UNIQUE KEY `numcode` (`numcode`),
	  KEY `iso` (`iso`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	CREATE TABLE  `" . DB_TBL_JOBS . "` (
	  `jid` int(11) NOT NULL AUTO_INCREMENT,
	  `j_ref` varchar(300) DEFAULT NULL,
	  `j_title` varchar(300) DEFAULT NULL,
	  `j_details` longtext,
	  `j_footer` longtext,
	  `j_postdate` datetime DEFAULT NULL,
	  `j_startdate` datetime DEFAULT NULL,
	  `j_expirydate` datetime DEFAULT NULL,
	  `j_featured` int(11) DEFAULT '0',
	  `cmid` int(11) DEFAULT '0',
	  `j_active` int(11) DEFAULT '1',
	  `j_deleted` int(11) DEFAULT '0',
	  PRIMARY KEY (`jid`),
	  KEY `j_active` (`j_active`),
	  KEY `j_expirydate` (`j_expirydate`),
	  KEY `j_deleted` (`j_deleted`),
	  KEY `j_title` (`j_title`),
	  KEY `cmid` (`cmid`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
	
	CREATE TABLE `" . DB_TBL_JOB_PROPERTIES_MASTER . "` (
	  `jpid` int(11) NOT NULL AUTO_INCREMENT,
	  `jp_name` varchar(300) DEFAULT NULL,
	  `jp_section` varchar(300) DEFAULT NULL,
	  `jp_inputtype` varchar(300) DEFAULT 'text',
	  `jp_inputname` varchar(300) DEFAULT NULL,
	  PRIMARY KEY (`jpid`),
	  KEY `jp_section` (`jp_section`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;
	
	
	CREATE TABLE `" . DB_TBL_JOB_PROPERTIES . "` (
	  `jpiid` int(11) NOT NULL AUTO_INCREMENT,
	  `jid` int(11) NOT NULL,
	  `jpid` int(11) NOT NULL,
	  `jp_value` varchar(300) DEFAULT NULL,
	  PRIMARY KEY (`jpiid`),
	  KEY `jid` (`jid`,`jpid`),
	  KEY `jpid` (`jpid`),
	  KEY `jp_value` (`jp_value`),
	  KEY `jid_2` (`jid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
	
	CREATE TABLE `" . DB_TBL_APPLICATIONS . "` (
	  `aid` int(11) NOT NULL AUTO_INCREMENT,
	  `mid` int(11) NOT NULL,
	  `jid` int(11) NOT NULL,
	  `cvid` int(11) NOT NULL,
	  `a_datetime` datetime DEFAULT NULL,
	  `a_coverletter` longtext,
	  `a_datesent` datetime NOT NULL,
	  PRIMARY KEY (`aid`),
	  KEY `mid` (`mid`,`jid`),
	  KEY `cvid` (`cvid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	
	CREATE TABLE `" . DB_TBL_SELECTBOX . "` (
	  `sid` int(11) NOT NULL AUTO_INCREMENT,
	  `s_type` varchar(30) DEFAULT NULL,
	  `s_val` varchar(300) DEFAULT NULL,
	  `s_default` int(11) DEFAULT '0',
	  PRIMARY KEY (`sid`),
	  KEY `sid` (`sid`,`s_type`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;
	
	CREATE TABLE IF NOT EXISTS `" . DB_TBL_SAVED_JOBS . "` (
	  `sjid` int(11) NOT NULL AUTO_INCREMENT,
	  `mid` int(11) NOT NULL,
	  `jid` int(11) NOT NULL,
	  PRIMARY KEY (`sjid`),
	  KEY `mid` (`mid`),
	  KEY `jid` (`jid`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	
	CREATE TABLE `" . DB_TBL_MEMBER_CV . "` (
	  `cvid` int(11) NOT NULL AUTO_INCREMENT,
	  `mid` int(11) NOT NULL,
	  `cv_filename` varchar(300) NOT NULL,
	  `cv_folder` varchar(300) NOT NULL,
	  `cv_dateuploaded` datetime DEFAULT NULL,
	  `cv_contents` longtext NOT NULL,
	  PRIMARY KEY (`cvid`),
	  KEY `mid` (`mid`),
	  FULLTEXT KEY `cv_contents` (`cv_contents`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
	
	CREATE TABLE `" . DB_TBL_COMPANIES . "` (
	  `cmid` int(11) NOT NULL AUTO_INCREMENT,
	  `cm_name` varchar(500) NOT NULL,
	  `cm_address` varchar(500) NOT NULL,
	  `cm_website` varchar(500) NOT NULL,
	  `cm_tel` varchar(40) NOT NULL,
	  `cm_description` longtext NOT NULL,
	  `cm_email` varchar(100) NOT NULL,
	  `cm_active` int(11) DEFAULT '1',
  	  `cm_createdate` datetime NOT NULL,
	  PRIMARY KEY (`cmid`),
	  KEY `cm_active` (`cm_active`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	
	CREATE TABLE IF NOT EXISTS `" . DB_TBL_GEO_CACHE . "` (
	  `geid` int(11) NOT NULL AUTO_INCREMENT,
	  `search` varchar(255) NOT NULL,
	  `lat` float NOT NULL,
	  `lon` float NOT NULL,
	  `default_radius` float NOT NULL,
	  PRIMARY KEY (`geid`),
	  KEY `search` (`search`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	
	CREATE TABLE  `" . DB_TBL_JOB_ALERT . "` (
	`jaid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`mid` INT NOT NULL ,
	`ja_name` VARCHAR( 200 ) NOT NULL ,
	`ja_frequency` CHAR( 1 ) NOT NULL ,
	`ja_lastrun` DATETIME NOT NULL ,
  	`ja_datecreated` datetime NOT NULL,
	INDEX (  `mid` )
	) ENGINE = INNODB;
	";
	
	$insert_sql = "
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('aq', '', 'ANTARCTICA', 'Antarctica', 0, 0, 0, 'aq.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('af', '', 'AFGHANISTAN', 'Afghanistan', 4, 0, 0, 'af.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('al', '', 'ALBANIA', 'Albania', 8, 0, 0, 'al.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('dz', '', 'ALGERIA', 'Algeria', 12, 0, 0, 'dz.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('as', '', 'AMERICAN SAMOA', 'American Samoa', 16, 0, 0, 'as.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ad', '', 'ANDORRA', 'Andorra', 20, 0, 0, 'ad.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ao', '', 'ANGOLA', 'Angola', 24, 0, 0, 'ao.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ag', '', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 28, 0, 0, 'ag.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('az', '', 'AZERBAIJAN', 'Azerbaijan', 31, 0, 0, 'az.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ar', '', 'ARGENTINA', 'Argentina', 32, 0, 0, 'ar.png', '','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('au', '', 'AUSTRALIA', 'Australia', 36, 0, 0, 'au.png', 'Australian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('at', '', 'AUSTRIA', 'Austria', 40, 0, 0, 'at.png', 'Austrian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bs', '', 'BAHAMAS', 'Bahamas', 44, 0, 0, 'bs.png', 'Bosnian',01');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bh', '', 'BAHRAIN', 'Bahrain', 48, 0, 0, 'bh.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bd', '', 'BANGLADESH', 'Bangladesh', 50, 0, 0, 'bd.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('am', '', 'ARMENIA', 'Armenia', 51, 0, 0, 'am.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bb', '', 'BARBADOS', 'Barbados', 52, 0, 0, 'bb.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('be', '', 'BELGIUM', 'Belgium', 56, 0, 0, 'be.png', 'Belgium','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bm', '', 'BERMUDA', 'Bermuda', 60, 0, 0, 'bm.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bt', '', 'BHUTAN', 'Bhutan', 64, 0, 0, 'bt.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bo', '', 'BOLIVIA', 'Bolivia', 68, 0, 0, 'bo.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ba', '', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 70, 0, 0, 'ba.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bw', '', 'BOTSWANA', 'Botswana', 72, 0, 0, 'bw.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('br', '', 'BRAZIL', 'Brazil', 76, 0, 0, 'br.png', 'Brazilian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bz', '', 'BELIZE', 'Belize', 84, 0, 0, 'bz.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sb', '', 'SOLOMON ISLANDS', 'Solomon Islands', 90, 0, 0, 'sb.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('vg', '', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 92, 0, 0, 'vg.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bn', '', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 96, 0, 0, 'bn.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bg', '', 'BULGARIA', 'Bulgaria', 100, 0, 0, 'bg.png', 'Bulgarian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mm', '', 'MYANMAR', 'Myanmar', 104, 0, 0, 'mm.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bi', '', 'BURUNDI', 'Burundi', 108, 0, 0, 'bi.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('by', '', 'BELARUS', 'Belarus', 112, 0, 0, 'by.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('kh', '', 'CAMBODIA', 'Cambodia', 116, 0, 0, 'kh.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('cm', '', 'CAMEROON', 'Cameroon', 120, 0, 0, 'cm.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ca', '', 'CANADA', 'Canada', 124, 0, 0, 'ca.png', 'Catalan','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('cv', '', 'CAPE VERDE', 'Cape Verde', 132, 0, 0, 'cv.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ky', '', 'CAYMAN ISLANDS', 'Cayman Islands', 136, 0, 0, 'ky.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('cf', '', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 140, 0, 0, 'cf.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('lk', '', 'SRI LANKA', 'Sri Lanka', 144, 0, 0, 'lk.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('td', '', 'CHAD', 'Chad', 148, 0, 0, 'td.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('cl', '', 'CHILE', 'Chile', 152, 0, 0, 'cl.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('cn', '', 'CHINA', 'China', 156, 0, 0, 'cn.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('tw', '', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 158, 0, 0, 'tw.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('co', '', 'COLOMBIA', 'Colombia', 170, 0, 0, 'co.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('km', '', 'COMOROS', 'Comoros', 174, 0, 0, 'km.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('cg', '', 'CONGO', 'Congo', 178, 0, 0, 'cg.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('cd', '', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 180, 0, 0, 'cd.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ck', '', 'COOK ISLANDS', 'Cook Islands', 184, 0, 0, 'ck.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('cr', '', 'COSTA RICA', 'Costa Rica', 188, 0, 0, 'cr.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('hr', '', 'CROATIA', 'Croatia', 191, 0, 0, 'hr.png', 'Croatian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('cu', '', 'CUBA', 'Cuba', 192, 0, 0, 'cu.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('cy', '', 'CYPRUS', 'Cyprus', 196, 0, 0, 'cy.png', 'Welsh','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('cz', '', 'CZECH REPUBLIC', 'Czech Republic', 203, 0, 0, 'cz.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bj', '', 'BENIN', 'Benin', 204, 0, 0, 'bj.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('dk', '', 'DENMARK', 'Denmark', 208, 0, 0, 'dk.png', 'Danish','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('dm', '', 'DOMINICA', 'Dominica', 212, 0, 0, 'dm.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('do', '', 'DOMINICAN REPUBLIC', 'Dominican Republic', 214, 0, 0, 'do.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ec', '', 'ECUADOR', 'Ecuador', 218, 0, 0, 'ec.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sv', '', 'EL SALVADOR', 'El Salvador', 222, 0, 0, 'sv.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gq', '', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 226, 0, 0, 'gq.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('et', '', 'ETHIOPIA', 'Ethiopia', 231, 0, 0, 'et.png', 'Estonian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('er', '', 'ERITREA', 'Eritrea', 232, 0, 0, 'er.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ee', '', 'ESTONIA', 'Estonia', 233, 0, 0, 'ee.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('fo', '', 'FAROE ISLANDS', 'Faroe Islands', 234, 0, 0, 'fo.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('fk', '', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 238, 0, 0, 'fk.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('fj', '', 'FIJI', 'Fiji', 242, 0, 0, 'fj.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('fi', '', 'FINLAND', 'Finland', 246, 0, 0, 'fi.png', 'Finnish','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('fr', 'fr', 'FRANCE', 'France', 250, 0, 0, 'fr.png', 'French','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gf', '', 'FRENCH GUIANA', 'French Guiana', 254, 0, 0, 'gf.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('pf', '', 'FRENCH POLYNESIA', 'French Polynesia', 258, 0, 0, 'pf.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('dj', '', 'DJIBOUTI', 'Djibouti', 262, 0, 0, 'dj.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ga', '', 'GABON', 'Gabon', 266, 0, 0, 'ga.png', 'Irish','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ge', '', 'GEORGIA', 'Georgia', 268, 0, 0, 'ge.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gm', '', 'GAMBIA', 'Gambia', 270, 0, 0, 'gm.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('de', '', 'GERMANY', 'Germany', 276, 0, 0, 'de.png', 'German','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gh', '', 'GHANA', 'Ghana', 288, 0, 0, 'gh.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gi', '', 'GIBRALTAR', 'Gibraltar', 292, 0, 0, 'gi.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ki', '', 'KIRIBATI', 'Kiribati', 296, 0, 0, 'ki.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gr', '', 'GREECE', 'Greece', 300, 0, 0, 'gr.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gl', '', 'GREENLAND', 'Greenland', 304, 0, 0, 'gl.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gd', '', 'GRENADA', 'Grenada', 308, 0, 0, 'gd.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gp', '', 'GUADELOUPE', 'Guadeloupe', 312, 0, 0, 'gp.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gu', '', 'GUAM', 'Guam', 316, 0, 0, 'gu.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gt', '', 'GUATEMALA', 'Guatemala', 320, 0, 0, 'gt.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gn', '', 'GUINEA', 'Guinea', 324, 0, 0, 'gn.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gy', '', 'GUYANA', 'Guyana', 328, 0, 0, 'gy.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ht', '', 'HAITI', 'Haiti', 332, 0, 0, 'ht.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('va', '', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 336, 0, 0, 'va.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('hn', '', 'HONDURAS', 'Honduras', 340, 0, 0, 'hn.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('hk', '', 'HONG KONG', 'Hong Kong', 344, 0, 0, 'hk.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('hu', '', 'HUNGARY', 'Hungary', 348, 0, 0, 'hu.png', 'Hungarian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('is', '', 'ICELAND', 'Iceland', 352, 0, 0, 'is.png', 'Icelandic','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('in', '', 'INDIA', 'India', 356, 0, 0, 'in.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('id', '', 'INDONESIA', 'Indonesia', 360, 0, 0, 'id.png', 'Indonesian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ir', '', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 364, 0, 0, 'ir.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('iq', '', 'IRAQ', 'Iraq', 368, 0, 0, 'iq.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ie', '', 'IRELAND', 'Ireland', 372, 0, 0, 'ie.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('il', '', 'ISRAEL', 'Israel', 376, 0, 0, 'il.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('it', '', 'ITALY', 'Italy', 380, 0, 0, 'it.png', 'Italian','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('jm', '', 'JAMAICA', 'Jamaica', 388, 0, 0, 'jm.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('jp', '', 'JAPAN', 'Japan', 392, 0, 0, 'jp.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('kz', '', 'KAZAKHSTAN', 'Kazakhstan', 398, 0, 0, 'kz.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('jo', '', 'JORDAN', 'Jordan', 400, 0, 0, 'jo.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ke', '', 'KENYA', 'Kenya', 404, 0, 0, 'ke.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('kr', '', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 410, 0, 0, 'kr.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('kw', '', 'KUWAIT', 'Kuwait', 414, 0, 0, 'kw.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('kg', '', 'KYRGYZSTAN', 'Kyrgyzstan', 417, 0, 0, 'kg.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('lb', '', 'LEBANON', 'Lebanon', 422, 0, 0, 'lb.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ls', '', 'LESOTHO', 'Lesotho', 426, 0, 0, 'ls.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('lv', '', 'LATVIA', 'Latvia', 428, 0, 0, 'lv.png', 'Latvian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('lr', '', 'LIBERIA', 'Liberia', 430, 0, 0, 'lr.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ly', '', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 434, 0, 0, 'ly.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('li', '', 'LIECHTENSTEIN', 'Liechtenstein', 438, 0, 0, 'li.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('lt', '', 'LITHUANIA', 'Lithuania', 440, 0, 0, 'lt.png', 'Lithuanian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('lu', '', 'LUXEMBOURG', 'Luxembourg', 442, 0, 0, 'lu.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mo', '', 'MACAO', 'Macao', 446, 0, 0, 'mo.png', 'Moldavian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mg', '', 'MADAGASCAR', 'Madagascar', 450, 0, 0, 'mg.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mw', '', 'MALAWI', 'Malawi', 454, 0, 0, 'mw.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('my', '', 'MALAYSIA', 'Malaysia', 458, 0, 0, 'my.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mv', '', 'MALDIVES', 'Maldivesis', 462, 0, 0, 'mv.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ml', '', 'MALI', 'Mali', 466, 0, 0, 'ml.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mt', '', 'MALTA', 'Malta', 470, 0, 0, 'mt.png', 'Maltese');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mq', '', 'MARTINIQUE', 'Martinique', 474, 0, 0, 'mq.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mr', '', 'MAURITANIA', 'Mauritania', 478, 0, 0, 'mr.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mu', '', 'MAURITIUS', 'Mauritius', 480, 0, 0, 'mu.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mx', '', 'MEXICO', 'Mexico', 484, 0, 0, 'mx.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mc', '', 'MONACO', 'Monaco', 492, 0, 0, 'mc.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mn', '', 'MONGOLIA', 'Mongolia', 496, 0, 0, 'mn.png', 'Mongolian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('md', '', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 498, 0, 0, 'md.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ms', '', 'MONTSERRAT', 'Montserrat', 500, 0, 0, 'ms.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ma', '', 'MOROCCO', 'Morocco', 504, 0, 0, 'ma.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mz', '', 'MOZAMBIQUE', 'Mozambique', 508, 0, 0, 'mz.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('om', '', 'OMAN', 'Oman', 512, 0, 0, 'om.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('na', '', 'NAMIBIA', 'Namibia', 516, 0, 0, 'na.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('nr', '', 'NAURU', 'Nauru', 520, 0, 0, 'nr.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('np', '', 'NEPAL', 'Nepal', 524, 0, 0, 'np.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('nl', '', 'NETHERLANDS', 'Netherlands', 528, 0, 0, 'nl.png', 'Dutch','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('an', '', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 530, 0, 0, 'an.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('aw', '', 'ARUBA', 'Aruba', 533, 0, 0, 'aw.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('nc', '', 'NEW CALEDONIA', 'New Caledonia', 540, 0, 0, 'nc.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('vu', '', 'VANUATU', 'Vanuatu', 548, 0, 0, 'vu.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('nz', '', 'NEW ZEALAND', 'New Zealand', 554, 0, 0, 'nz.png', '','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ni', '', 'NICARAGUA', 'Nicaragua', 558, 0, 0, 'ni.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ne', '', 'NIGER', 'Niger', 562, 0, 0, 'ne.png', 'Nepali','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ng', '', 'NIGERIA', 'Nigeria', 566, 0, 0, 'ng.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('nu', '', 'NIUE', 'Niue', 570, 0, 0, 'nu.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('nf', '', 'NORFOLK ISLAND', 'Norfolk Island', 574, 0, 0, 'nf.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('no', '', 'NORWAY', 'Norway', 578, 0, 0, 'no.png', 'Norway','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mp', '', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 580, 0, 0, 'mp.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('fm', '', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 583, 0, 0, 'fm.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mh', '', 'MARSHALL ISLANDS', 'Marshall Islands', 584, 0, 0, 'mh.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('pw', '', 'PALAU', 'Palau', 585, 0, 0, 'pw.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('pk', '', 'PAKISTAN', 'Pakistan', 586, 0, 0, 'pk.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('pa', '', 'PANAMA', 'Panama', 591, 0, 0, 'pa.png', 'Punjabi','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('pg', '', 'PAPUA NEW GUINEA', 'Papua New Guinea', 598, 0, 0, 'pg.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('py', '', 'PARAGUAY', 'Paraguay', 600, 0, 0, 'py.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('pe', '', 'PERU', 'Peru', 604, 0, 0, 'pe.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ph', '', 'PHILIPPINES', 'Philippines', 608, 0, 0, 'ph.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('pn', '', 'PITCAIRN', 'Pitcairn', 612, 0, 0, 'pn.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('pl', '', 'POLAND', 'Poland', 616, 0, 0, 'pl.png', 'Polish','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('pt', '', 'PORTUGAL', 'Portugal', 620, 0, 0, 'pt.png', 'Portugese','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gw', '', 'GUINEA-BISSAU', 'Guinea-Bissau', 624, 0, 0, 'gw.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('pr', '', 'PUERTO RICO', 'Puerto Rico', 630, 0, 0, 'pr.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('qa', '', 'QATAR', 'Qatar', 634, 0, 0, 'qa.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('re', '', 'REUNION', 'Reunion', 638, 0, 0, 're.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ro', '', 'ROMANIA', 'Romania', 642, 0, 0, 'ro.png', 'Romanian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ru', '', 'RUSSIAN FEDERATION', 'Russian Federation', 643, 0, 0, 'ru.png', 'Russian','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('rw', '', 'RWANDA', 'Rwanda', 646, 0, 0, 'rw.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sh', '', 'SAINT HELENA', 'Saint Helena', 654, 0, 0, 'sh.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('kn', '', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 659, 0, 0, 'kn.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ai', '', 'ANGUILLA', 'Anguilla', 660, 0, 0, 'ai.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('lc', '', 'SAINT LUCIA', 'Saint Lucia', 662, 0, 0, 'lc.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('pm', '', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 666, 0, 0, 'pm.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('vc', '', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 670, 0, 0, 'vc.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sm', '', 'SAN MARINO', 'San Marino', 674, 0, 0, 'sm.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('st', '', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 678, 0, 0, 'st.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sa', '', 'SAUDI ARABIA', 'Saudi Arabia', 682, 0, 0, 'sa.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sn', '', 'SENEGAL', 'Senegal', 686, 0, 0, 'sn.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sc', '', 'SEYCHELLES', 'Seychelles', 690, 0, 0, 'sc.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sl', '', 'SIERRA LEONE', 'Sierra Leone', 694, 0, 0, 'sl.png', 'Slovenian','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sg', '', 'SINGAPORE', 'Singapore', 702, 0, 0, 'sg.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sk', '', 'SLOVAKIA', 'Slovakia', 703, 0, 0, 'sk.png', 'Slovakian','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('vn', '', 'VIET NAM', 'Viet Nam', 704, 0, 0, 'vn.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('si', '', 'SLOVENIA', 'Slovenia', 705, 0, 0, 'si.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('so', '', 'SOMALIA', 'Somalia', 706, 0, 0, 'so.png', 'Somali','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('za', '', 'SOUTH AFRICA', 'South Africa', 710, 0, 0, 'za.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('zw', '', 'ZIMBABWE', 'Zimbabwe', 716, 0, 0, 'zw.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('es', '', 'SPAIN', 'Spain', 724, 0, 0, 'es.png', 'Spanish','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('eh', '', 'WESTERN SAHARA', 'Western Sahara', 732, 0, 0, 'eh.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sd', '', 'SUDAN', 'Sudan', 736, 0, 0, 'sd.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sr', '', 'SURINAME', 'Suriname', 740, 0, 0, 'sr.png', 'Serbian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sj', '', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 744, 0, 0, 'sj.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sz', '', 'SWAZILAND', 'Swaziland', 748, 0, 0, 'sz.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('se', '', 'SWEDEN', 'Sweden', 752, 0, 0, 'se.png', 'Swedish','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ch', '', 'SWITZERLAND (GERMAN)', 'Switzerland (German Speaking)', 756, 0, 0, 'ch.png', 'Swiss German','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('sy', '', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 760, 0, 0, 'sy.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('tj', '', 'TAJIKISTAN', 'Tajikistan', 762, 0, 0, 'tj.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('th', '', 'THAILAND', 'Thailand', 764, 0, 0, 'th.png', 'Thai','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('tg', '', 'TOGO', 'Togo', 768, 0, 0, 'tg.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('tk', '', 'TOKELAU', 'Tokelau', 772, 0, 0, 'tk.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('to', '', 'TONGA', 'Tonga', 776, 0, 0, 'to.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('tt', '', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 780, 0, 0, 'tt.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ae', '', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 784, 0, 0, 'ae.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('tn', '', 'TUNISIA', 'Tunisia', 788, 0, 0, 'tn.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('tr', '', 'TURKEY', 'Turkey', 792, 0, 0, 'tr.png', 'Turkish','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('tm', '', 'TURKMENISTAN', 'Turkmenistan', 795, 0, 0, 'tm.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('tc', '', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 796, 0, 0, 'tc.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('tv', '', 'TUVALU', 'Tuvalu', 798, 0, 0, 'tv.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ug', '', 'UGANDA', 'Uganda', 800, 0, 0, 'ug.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ua', '', 'UKRAINE', 'Ukraine', 804, 0, 0, 'ua.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('mk', '', 'MACEDONIA', 'Macedonia', 807, 0, 0, 'mk.png', 'Macedonian','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('eg', '', 'EGYPT', 'Egypt', 818, 0, 0, 'eg.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('gb', 'en', 'GREAT BRITAIN', 'Great Britain', 826, 1, 1, 'gb.png', 'English','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('tz', '', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 834, 0, 0, 'tz.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('us', '', 'UNITED STATES', 'United States', 840, 0, 0, 'us.png', '','1');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('vi', '', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 850, 0, 0, 'vi.png', 'Vietnamese','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('bf', '', 'BURKINA FASO', 'Burkina Faso', 854, 0, 0, 'bf.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('uy', '', 'URUGUAY', 'Uruguay', 858, 0, 0, 'uy.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('uz', '', 'UZBEKISTAN', 'Uzbekistan', 860, 0, 0, 'uz.png', 'Uzbek','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ve', '', 'VENEZUELA', 'Venezuela', 862, 0, 0, 've.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('wf', '', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 876, 0, 0, 'wf.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ws', '', 'SAMOA', 'Samoa', 882, 0, 0, 'ws.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('ye', '', 'YEMEN', 'Yemen', 887, 0, 0, 'ye.png', '','0');
INSERT INTO `" . DB_TBL_COUNTRIES . "` VALUES('zm', '', 'ZAMBIA', 'Zambia', 894, 0, 0, 'zm.png', '','0');

INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(1, 'Address 1', 'Address', 'text', 'address1');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(2, 'Address 2', 'Address', 'text', 'address2');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(3, 'Address 3', 'Address', 'text', 'address3');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(4, 'Town / City', 'Address', 'text', 'town');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(5, 'Province', 'Address', 'text', 'province');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(6, 'Country', 'Address', 'special', 'country');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(7, 'Postcode', 'Address', 'text', 'postcode');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(8, 'Postcode area', 'Address', 'text', 'pcode_area');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(9, 'Latitude', 'Address', 'text', 'lat');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(10, 'Longitude', 'Address', 'text', 'lon');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(11, 'Phone', 'Address', 'text', 'tel');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(12, 'Salary Type', 'Salary', 'select', 'salary_type');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(13, 'Salary', 'Salary', 'text', 'salary');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(14, 'Currency', 'Salary', 'select', 'currency');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(15, 'Benefits', 'Salary', 'text', 'benefits');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(16, 'Job Type', 'Other', 'select', 'jobtype');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(17, 'Contact Email', 'Job Contact', 'text', 'contact_email');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(18, 'Contact Name', 'Job Contact', 'text', 'contact_name');
INSERT INTO `" . DB_TBL_JOB_PROPERTIES_MASTER . "` VALUES(19, 'Application Email', 'Job Contact', 'text', 'application_email');

INSERT INTO `" . DB_TBL_MEMBER_PROFILE_ARGUMENTS . "` VALUES('', 'member_folder', 'Member folder');

INSERT INTO `" . DB_TBL_SELECTBOX . "` (`sid`, `s_type`, `s_val`, `s_default`) VALUES
(1, 'salary_type', 'per hour', 0),
(2, 'salary_type', 'per week', 0),
(3, 'salary_type', 'per month', 0),
(4, 'salary_type', 'per year', 1),
(5, 'jobtype', 'Permanent', 0),
(6, 'jobtype', 'Contract', 0),
(7, 'jobtype', 'Temporary', 0),
(8, 'jobtype', 'Part time', 0),
(9, 'jobtype', 'Any', 1),
(10, 'jobtype', 'Intern', 0),
(11, 'jobtype', 'Seasonal', 0),
(13, 'currency', 'AUD - Australian Dollar', 0),
(14, 'currency', 'CAD - Canadian Dollar', 0),
(15, 'currency', 'CHF - Swiss Franc', 0),
(16, 'currency', 'DKK - Danish Krone', 0),
(17, 'currency', 'EUR - Euro', 0),
(18, 'currency', 'GBP - British Pound', 1),
(19, 'currency', 'HKD - Hong Kong Dollar', 0),
(20, 'currency', 'HUF - Hungarian Forint', 0),
(21, 'currency', 'INR - Indian Rupee', 0),
(22, 'currency', 'JPY - Japanese Yen', 0),
(23, 'currency', 'MXN - Mexican Peso', 0),
(24, 'currency', 'MYR - Malaysian Ringgit', 0),
(25, 'currency', 'NOK - Norwegian Krone', 0),
(26, 'currency', 'NZD - New Zealand Dollar', 0),
(27, 'currency', 'PHP - Philippine Peso', 0),
(28, 'currency', 'RUB - Russian Ruble', 0),
(29, 'currency', 'SEK - Swedish Krona', 0),
(30, 'currency', 'SGD - Singapore Dollar', 0),
(31, 'currency', 'THB - Thai Baht', 0),
(32, 'currency', 'TRY - Turkish Lira', 0),
(33, 'currency', 'USD - US Dollar', 0),
(34, 'currency', 'ZAR - South African Rand', 0);

INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','newjob', 'New job added');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','updatedjob', 'Job updated');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','deletedjob', 'Job deleted');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','removedjob', 'Job removed');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','cvupdated', 'CV Updated');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','jobappliedok', 'Application successful');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','jobsaved', 'This job has been saved');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','newcompany', 'New company added');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','updatedcompany', 'Company updated');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','deletedcompany', 'Company deleted');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','newjobalert', 'Job alert deleted');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','updatedjobalert', 'Job alert updated');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','removedjobalert', 'Job alert deleted');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','welcome', 'Welcome');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','savejob_needtobeloggedin', 'You need to be logged in to save jobs');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','emailfriendsuccess', 'Email successful');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','r_passwordlength', 'The password is too short. Minimum 6 characters long');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES('','r_oldpassworderror', 'The old password is incorrect and the password has not been altered');

INSERT INTO `" . DB_TBL_POSTS . "` VALUES('', 'Application Form To Client', 'application-email', '<p>This is what gets sent out to the job poster when a user applies for a job', '2012-01-01 00:00:01', '2012-01-01 00:00:01', 1, 'template', 'en','0','published','');
INSERT INTO `" . DB_TBL_POSTS . "` VALUES('', 'Email Friend a Job', 'email-friend-a-job', '<p>This is what gets sent from the email a friend job option.<br />[j_title]<br />http://www.company.com/[j_link]<br />[j_ref]<br />[salary]', '2012-01-01 00:00:01', '2012-01-01 00:00:01', 1, 'template', 'en','0','published','');

insert into " . DB_TBL_OPTIONS . " (op_arg,op_val,op_section) values ('admin.view.menu','recruitment/hooks.php','niche_hook');
insert into " . DB_TBL_OPTIONS . " (op_arg,op_val,op_section) values ('admin.action.category.new','recruitment/hooks.php','niche_hook');
insert into " . DB_TBL_OPTIONS . " (op_arg,op_val,op_section) values ('admin.action','recruitment/hooks.php','niche_hook');
insert into " . DB_TBL_OPTIONS . " (op_arg,op_val,op_section) values ('admin.action.category.delete','recruitment/hooks.php','niche_hook');
";
	
	echo "<hr />";
	$sqls = explode(';',$create_sql);
	foreach($sqls as $sql) {
		$rs = mq($sql);
	}
	echo "<p><h4 class='alert alert_success'>Liquid Recruitment tables created succesfully</h4></p>";
	
	$sqls = explode(';',$insert_sql);
	foreach($sqls as $sql) {
		$rs = mq($sql);
	}
	echo "<p><h4 class='alert alert_success'>Liquid Recruitment tales populated succesfully</h4></p>";
}
?> 