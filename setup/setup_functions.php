<?php
function setup_database($posty) {
	if($posty['setup_from'] == "ls_setup_real") {
		$create_sql = "
			CREATE TABLE `" . DB_TBL_ALERT_WARNINGS . "` (
			  `awid` int(11) NOT NULL AUTO_INCREMENT,
			  `aw_slug` varchar(50) DEFAULT NULL,
			  `aw_warning` varchar(1000) DEFAULT NULL,
			  PRIMARY KEY (`awid`),
			  KEY `aw_slug` (`aw_slug`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			
			CREATE TABLE `" . DB_TBL_CATEGORIES . "` (
			  `cid` int(11) NOT NULL AUTO_INCREMENT,
			  `c_name` varchar(500) DEFAULT NULL,
			  `c_parent` int(11) DEFAULT '0',
			  `c_type` varchar(100) DEFAULT NULL,
			  PRIMARY KEY (`cid`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			
			CREATE TABLE `" . DB_TBL_CATEGORY_LINK . "` (
			  `jcid` int(11) NOT NULL AUTO_INCREMENT,
			  `uid` int(11) NOT NULL,
			  `cid` int(11) NOT NULL,
			  `l_type` varchar(10) DEFAULT NULL,
			  PRIMARY KEY (`jcid`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			
			CREATE TABLE `" . DB_TBL_LINKS . "` (
			  `lid` int(11) NOT NULL AUTO_INCREMENT,
			  `l_link` varchar(1000) DEFAULT NULL,
			  `l_text` varchar(1000) DEFAULT NULL,
			  `l_active` int(11) NOT NULL,
			  PRIMARY KEY (`lid`),
			  KEY `l_active` (`l_active`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			
			CREATE TABLE `" . DB_TBL_LOG_SHORTCODES . "` (
			  `sc` char(3) NOT NULL,
			  `sc_des` varchar(100) NOT NULL,
			  PRIMARY KEY (`sc`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			
			CREATE TABLE `" . DB_TBL_MEDIA . "` (
			  `mdid` int(11) NOT NULL AUTO_INCREMENT,
			  `md_title` varchar(500) DEFAULT NULL,
			  `md_alttext` varchar(1000) DEFAULT NULL,
			  `md_text` longtext,
			  `md_folder` varchar(300) NOT NULL,
			  `md_filename` varchar(500) DEFAULT NULL,
			  `md_dateuploaded` datetime DEFAULT NULL,
			  `md_type` varchar(50) DEFAULT NULL,
			  `md_filesize` varchar(50) DEFAULT NULL,
			  `md_active` int(11) DEFAULT '1',
			  PRIMARY KEY (`mdid`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			
			CREATE TABLE `" . DB_TBL_MEMBERS . "` (
			  `mid` int(11) NOT NULL AUTO_INCREMENT,
			  `m_username` varchar(20) NOT NULL,
			  `m_hash` varchar(256) DEFAULT NULL,
			  `m_createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `m_type` char(2) NOT NULL,
			  `m_email` varchar(500) DEFAULT NULL,
			  `m_active` int(11) DEFAULT '1',
			  `m_lastlogin` datetime DEFAULT NULL,
			  `m_protected` int(11) DEFAULT '0',
			  PRIMARY KEY (`mid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			


			CREATE TABLE `" . DB_TBL_MEMBER_PROFILE . "` (
			  `mpid` int(11) NOT NULL AUTO_INCREMENT,
			  `mid` int(11) NOT NULL,
			  `mpa_sc` varchar(50) NOT NULL,
			  `mp_val` varchar(1000) NOT NULL,
			  PRIMARY KEY (`mpid`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


			CREATE TABLE `" . DB_TBL_MEMBER_PROFILE_ARGUMENTS . "` (
			  `mpaid` int(11) NOT NULL AUTO_INCREMENT,
			  `mpa_sc` varchar(50) NOT NULL,
			  `mpa_english` varchar(100) NOT NULL,
			  PRIMARY KEY (`mpaid`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
			
			
			CREATE TABLE `" . DB_TBL_META_DATA . "` (
			  `mdid` int(11) NOT NULL AUTO_INCREMENT,
			  `md_arg` varchar(300) DEFAULT NULL,
			  `md_val` longtext,
			  `uid` int(11) DEFAULT NULL,
			  `md_type` varchar(20) DEFAULT NULL,
			  `md_recogniser` varchar(30) NOT NULL,
			  PRIMARY KEY (`mdid`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
			
			CREATE TABLE `" . DB_TBL_PAGES. "` (
			  `pgid` int(11) NOT NULL AUTO_INCREMENT,
			  `pg_meta_title` varchar(300) DEFAULT NULL,
			  `pg_slug` varchar(300) DEFAULT NULL,
			  `pg_meta_description` varchar(1000) DEFAULT NULL,
			  `pg_meta_keywords` varchar(1000) DEFAULT NULL,
			  `isDefault` int(11) DEFAULT '0',
			  `isAdmin` int(11) DEFAULT NULL,
			  `pg_createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `pg_type` char(2) NOT NULL,
			  `pg_content` longtext NOT NULL,
			  `pg_active` int(1) NOT NULL,
			  `pg_parent` int(11) NOT NULL,
			  `pg_posttype` varchar(20) NOT NULL DEFAULT 'published',
			  `pg_origposttype` varchar(20) NOT NULL,
			  PRIMARY KEY (`pgid`),
			  KEY `mod_slug` (`pg_slug`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
			
			CREATE TABLE `" . DB_TBL_POSTS . "` (
			  `pid` int(11) NOT NULL AUTO_INCREMENT,
			  `p_title` varchar(300) DEFAULT NULL,
			  `p_slug` varchar(300) DEFAULT NULL,
			  `p_content` longtext,
			  `p_createdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  `p_active` int(11) DEFAULT '1',
			  `p_type` varchar(10) DEFAULT NULL,
			  `p_lang` varchar(3) DEFAULT 'en',
			  `p_parent` int(11) NOT NULL,
			  `p_posttype` varchar(20) NOT NULL DEFAULT 'published',
			  `p_origposttype` varchar(20) NOT NULL,
			  PRIMARY KEY (`pid`),
			  KEY `p_slug` (`p_slug`),
			  KEY `p_active` (`p_active`),
			  KEY `p_type` (`p_type`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			
			CREATE TABLE `" . DB_TBL_SITE_LOG . "` (
			  `slid` int(11) NOT NULL AUTO_INCREMENT,
			  `sl_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `sl_ip` varchar(15) NOT NULL,
			  `sl_useragent` varchar(150) NOT NULL,
			  `sl_sc` char(3) NOT NULL,
			  `sl_info` varchar(500) NOT NULL,
			  `mid` int(11) NOT NULL,
			  PRIMARY KEY (`slid`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			
			CREATE TABLE `" . DB_TBL_SITE_OPTIONS . "` (
			  `soid` int(11) NOT NULL AUTO_INCREMENT,
			  `so_arg` varchar(50) NOT NULL,
			  `so_val` varchar(300) NOT NULL,
			  `so_updatedate` datetime NOT NULL,
			  `so_type` varchar(30) NOT NULL DEFAULT 'define',
			  `so_userid` int(11) NOT NULL,
			  `so_group` varchar(30) NOT NULL,
			  `so_field_type` varchar(30) NOT NULL,
			  PRIMARY KEY (`soid`),
			  KEY `so_userid` (`so_userid`,`so_group`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
		";
		
		$insert_sql = "
			INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(1, 'newpage', 'Page added / amended');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(2, 'newpost', 'Post added / amended');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(3, 'newmedia', 'Media added / amended');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(4, 'newmember', 'Member added / amended');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(5, 'newtemplate', 'Template added / amended');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(6, 'newlink', 'Link added / amended');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(7, 'newcategory', 'Category added / amended');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(8, 'newsiteoptions', 'Site options amended');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(9, 'deletedmember', 'Member deleted');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(10, 'deletedcategory', 'Category deleted');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(11, 'deletedlink', 'Link deleted');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(12, 'deletedmedia', 'Media deleted');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(13, 'deletedpage', 'Page deleted');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(14, 'deletedpost', 'Post deleted');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(15, 'deletedtemplate', 'Template deleted');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(16, 'missingmember', 'The members username, email or password was missing and the user has not been added.');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(17, 'r_userexists', 'Username already exists. The user has not added');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(18, 'r_emailtaken', 'Email address is already being used by another member. The user has not been added.');
INSERT INTO `" . DB_TBL_ALERT_WARNINGS . "` VALUES(19, 'loginfail', 'Username / password incorrect);

INSERT INTO `" . DB_TBL_LOG_SHORTCODES . "` VALUES('ALI', 'Attempt to login');
INSERT INTO `" . DB_TBL_LOG_SHORTCODES . "` VALUES('DLM', 'Deleted member');
INSERT INTO `" . DB_TBL_LOG_SHORTCODES . "` VALUES('FGF', 'Forgot password failure');
INSERT INTO `" . DB_TBL_LOG_SHORTCODES . "` VALUES('FGP', 'Forgot password');
INSERT INTO `" . DB_TBL_LOG_SHORTCODES . "` VALUES('FGS', 'Forgot password success');
INSERT INTO `" . DB_TBL_LOG_SHORTCODES . "` VALUES('LGF', 'Login failure');
INSERT INTO `" . DB_TBL_LOG_SHORTCODES . "` VALUES('LGO', 'User logged out');
INSERT INTO `" . DB_TBL_LOG_SHORTCODES . "` VALUES('LGS', 'Login success');
INSERT INTO `" . DB_TBL_LOG_SHORTCODES . "` VALUES('RMM', 'Member permanently removed');

INSERT INTO `" . DB_TBL_PAGES . "` VALUES(1, 'Home', 'home', 'This is the generic module setup', 'keyword1,keyword2', 1, 0, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(2, 'New Post', 'newpost', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(3, 'New User', 'newuser', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(4, 'Edit Users', 'editusers', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(5, 'Post Categories', 'postcategories', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(6, 'Edit Posts', 'editposts', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(7, 'New Page', 'newpage', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(8, 'Edit Pages', 'editpages', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(9, 'New Media', 'newmedia', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(10, 'Edit Media', 'editmedia', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(11, 'Media Categories', 'mediacategories', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(12, 'New Email Template', 'newtemplate', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(13, 'Edit Email Templates', 'edittemplates', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(14, 'Edit Links', 'editlinks', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(15, 'Link Categories', 'linkcategories', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(16, 'New Link', 'newlink', NULL, NULL, 0, 1, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(17, 'Site options', 'editsiteoptions', NULL, NULL, 0, 1, '2012-03-05 16:37:04', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(18, 'About Us', 'aboutus', 'About us description', 'kw1,kw2,about', 0, 0, '2012-01-30 16:27:21', 'tx', '<p>This is the about us page. The text is derived from the backend Page screen.</p>', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(19, 'Register', 'register', 'Register description', 'kw1,kw2,register', 0, 0, '2012-02-27 11:16:21', 'bs', '', 1),'0','published','';
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(20, 'Login', 'login', 'Login description', 'kw1,kw2,login', 0, 0, '2012-02-27 12:14:04', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(21, 'Contact Us', 'contactus', 'des', 'key', 0, 0, '0000-00-00 00:00:00', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(22, 'Pages', 'pages', 'Pages description', 'kw1,kw2,pages', 0, 0, '2012-02-27 12:14:04', 'bs', '', 1,'0','published','');
INSERT INTO `" . DB_TBL_PAGES . "` VALUES(23, 'Posts', 'posts', 'Posts description', 'kw1,kw2,posts', 0, 0, '2012-02-27 12:14:04', 'bs', '', 1,'0','published','');

INSERT INTO `" . DB_TBL_POSTS . "` VALUES(1, 'Registration', 'registration', '<p>This is what gets sent out to new registrants</p>\r\n<p>[r_username]</p>\r\n<p>[r_email]</p>\r\n<p>[r_password]</p>\r\n<p>[r_fname]</p>\r\n<p>[r_lname]</p>\r\n<p>[tel]</p>', NULL, 1, 'template', 'en', '2012-02-28 13:10:53','0','published','');
INSERT INTO `" . DB_TBL_POSTS . "` VALUES(2, 'Forgot Password', 'forgot-password', '<p>This is what gets sent out to people who forget their password</p>\r\n<p>[password]</p>\r\n<p>[mp_fname]</p>\r\n<p>[mp_lname]</p>\r\n<p>[uname]</p>', NULL, 1, 'template', 'en', '0000-00-00 00:00:00','0','published','');

INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(1, 'GOOGLE_MAPS_API_KEY', 'AIzaSyCeFju_W41SnogvXDmFFHinp63yKs9DMME', '0000-00-00 00:00:00', 'define', 0, 'APIs', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(2, 'GOOGLE_ANALYTICS_CODE', 'UA-xxxxxxxx-x', '0000-00-00 00:00:00', 'define', 0, 'SEO', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(3, 'EM_CONTACT_ADDRESS', 'contact@thiscompany.com', '0000-00-00 00:00:00', 'define', 0, 'Email', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(4, 'EM_FEED_ADDRESS', 'webmaster@thiscompany.com', '0000-00-00 00:00:00', 'define', 0, 'Feeds', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(5, 'EM_WIDTH', '600px', '0000-00-00 00:00:00', 'define', 0, 'Email', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(6, 'EM_FROM_NAME', 'This Company', '0000-00-00 00:00:00', 'define', 0, 'Email', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(7, 'EM_FROM_EMAIL', 'no-reply@thiscompany.com', '0000-00-00 00:00:00', 'define', 0, 'Email', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(8, 'EM_HOME_PAGE', 'http://www.site.com/', '0000-00-00 00:00:00', 'define', 0, 'Email', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(9, 'EM_LOGOURL', 'http://www.site.com/logo.png', '0000-00-00 00:00:00', 'define', 0, 'Email', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(10, 'EM_HEADER_COLOR', '#0093cb', '0000-00-00 00:00:00', 'define', 0, 'Email', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(11, 'EM_LINK_COLOR', '#666', '0000-00-00 00:00:00', 'define', 0, 'Email', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(12, 'EM_LINK_HOVER_COLOR', '#333', '0000-00-00 00:00:00', 'define', 0, 'Email', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(13, 'HOMEPAGE', 'http://www.site.com/', '0000-00-00 00:00:00', 'define', 0, 'Core', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(14, 'BASE_HREF', '/template/', '0000-00-00 00:00:00', 'define', 0, 'Core', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(15, 'COMPANY_NAME', 'This Company', '0000-00-00 00:00:00', 'define', 0, 'Core', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(16, 'SITE_NAME', 'This Site', '0000-00-00 00:00:00', 'define', 0, 'SEO', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(17, 'SUB_TITLE', 'The coolest new site on the block', '0000-00-00 00:00:00', 'define', 0, 'SEO', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(18, 'PROJECT_NAME', 'Project Name', '0000-00-00 00:00:00', 'define', 0, 'SEO', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(19, 'PROJECT_ABOUT', 'What the project is about', '0000-00-00 00:00:00', 'define', 0, 'SEO', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(20, 'SITE_CATEGORY', '', '0000-00-00 00:00:00', 'define', 0, 'SEO', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(21, 'LOGO_URL', 'logo.png', '0000-00-00 00:00:00', 'define', 0, 'Core', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(22, 'FACEBOOK_APP_ID', '', '0000-00-00 00:00:00', 'define', 0, 'Social', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(23, 'FACEBOOK_PAGE_URL', '', '0000-00-00 00:00:00', 'define', 0, 'Social', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(24, 'GOOGLE_PLUS_PAGE_ID', '', '0000-00-00 00:00:00', 'define', 0, 'Social', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(25, 'TWITTER_NAME', '', '0000-00-00 00:00:00', 'define', 0, 'Social', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(26, 'USE_PINTEREST', 'true', '0000-00-00 00:00:00', 'define', 0, 'Social', 'bool');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(27, 'USE_LINKEDIN', 'true', '0000-00-00 00:00:00', 'define', 0, 'Social', 'bool');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(28, 'USE_TWITTER', 'true', '0000-00-00 00:00:00', 'define', 0, 'Social', 'bool');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(29, 'USE_GOOGLE_PLUS', 'true', '0000-00-00 00:00:00', 'define', 0, 'Social', 'bool');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(30, 'USE_FACEBOOK_LIKE', 'true', '0000-00-00 00:00:00', 'define', 0, 'Social', 'bool');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(31, 'GOOGLE_SITE_VERIFICATION_KEY', '', '0000-00-00 00:00:00', 'define', 0, 'SEO', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(32, 'MS_VALIDATE_KEY', '', '0000-00-00 00:00:00', 'define', 0, 'SEO', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(33, 'RSS_LOCATION', 'feeds/rss.xml', '0000-00-00 00:00:00', 'define', 0, 'Feeds', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(34, 'SITEMAP_LOCATION', 'sitemap.xml', '0000-00-00 00:00:00', 'define', 0, 'Feeds', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(35, 'USE_FORM_PARSER', 'true', '0000-00-00 00:00:00', 'define', 0, 'Modules', 'bool');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(36, 'USE_GOOGLE_MAPS', 'true', '0000-00-00 00:00:00', 'define', 0, 'APIs', 'bool');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(37, 'USE_TABLE_PARSER', 'true', '0000-00-00 00:00:00', 'define', 0, 'Modules', 'bool');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(38, 'USE_TOOLTIPS', 'true', '0000-00-00 00:00:00', 'define', 0, 'Modules', 'bool');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(39, 'USE_SHADOWBOX', 'true', '0000-00-00 00:00:00', 'define', 0, 'Modules', 'bool');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(40, 'USE_FLEX_SLIDER', 'true', '0000-00-00 00:00:00', 'define', 0, 'Modules', 'bool');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(41, 'USE_BX_SLIDER', 'true', '0000-00-00 00:00:00', 'define', 0, 'Modules', 'bool');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(42, 'LIQUID_SOURCE_VERSION', '1.0.0', '0000-00-00 00:00:00', 'define', 0, '', '');
INSERT INTO `" . DB_TBL_SITE_OPTIONS . "` VALUES(43, 'FORCE_RECREATE', 'true', '0000-00-00 00:00:00', 'define', 0, '', 'bool');

INSERT INTO `" . DB_TBL_MEMBER_PROFILE_ARGUMENTS . "` VALUES(1, 'fname', 'Member first name');
INSERT INTO `" . DB_TBL_MEMBER_PROFILE_ARGUMENTS . "` VALUES(2, 'lname', 'Member last name');
		";
		
		$sqls = explode(';',$create_sql);
		foreach($sqls as $sql) {
			$rs = mq($sql);
		}
		echo "<p><h4 class='alert alert_success'>Tables created succesfully</h4></p>";
		
		$sqls = explode(';',$insert_sql);
		foreach($sqls as $sql) {
			$rs = mq($sql);
		}
		echo "<p><h4 class='alert alert_success'>Base tables populated succesfully</h4></p>";
		
		$mem_password = $posty['admin_password_r'];
		$mem_username = $posty['admin_username'];
		$mem_email = $posty['your_email'];
		
		$member = new Member();
		$post_array = array('m_username' => $mem_username, 'm_password' => $mem_password, 'm_email' => $mem_email, 'mp_fname' => "Base", 'mp_lname' => "Admin");
		$mem_hash = $member->updateUser($post_array,'A');
		
		echo "<p><h4 class='alert alert_success'>Admin user created succesfully</h4></p>";
	} else {
		echo "Fatal error";
		die;
	}
}
?>
