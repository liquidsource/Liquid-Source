<?php
/* This echos out all the text upto and including the body tag. Sets up all JS and CSS that is needed at the top. */
function topPage($module) {
	$description = getMetaInfo($module,'description');
	$keywords = getMetaInfo($module,'keywords');
	$title = getMetaInfo($module,'title');
	
    echo "<!doctype html> 
    <html lang=\"" . strtolower(LANG) . "-" . strtoupper(ISO) . "\" class=\"no-js mod_$module\" itemscope itemtype=\"http://schema.org/\">
    <head>
        <meta charset=\"utf-8\" />
        <title>" . $title . "</title>
        
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\" />
        <meta http-equiv=\"content-type\" content=\"text/html;charset=UTF-8\" />
        
        <meta name=\"description\" content=\"" . $description . "\" />
        <meta name=\"keywords\" content=\"" . $keywords . "\" />
        
        <meta name=\"google-site-verification\" content=\"" . GOOGLE_SITE_VERIFICATION_KEY . "\" />
        <meta name=\"msvalidate.01\" content=\"" . MS_VALIDATE_KEY . "\" />
        
        <meta name=\"author\" content=\"" . SITE_AUTHOR . "\" />
        <meta name=\"language\" content=\"" . strtolower(LANG) . "-" . strtoupper(ISO) . "\" />
		<meta name=\"Copyright\" content=\"Copyright " . COMPANY_NAME . " " . date("Y") . ". All Rights Reserved.\" />
		
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0\" />
        
        <meta name=\"robots\" content=\"index,follow\" /> 
        <meta name=\"revisit-after\" content=\"28 days\" />
        
		<meta name=\"DC.title\" content=\"" . PROJECT_NAME . "\" />
		<meta name=\"DC.subject\" content=\"" . PROJECT_ABOUT . "\" />
        <meta name=\"DC.langugae\" content=\"en-gb\" />
		<meta name=\"DC.creator\" content=\"" . SITE_AUTHOR . "\" />
		
		<meta itemprop=\"name\" content=\"" . $title . "\" />
		<meta itemprop=\"description\" content=\"" . $description . "\" />
    ";
		
	if(BASE_HREF != "") echo "<base href=\"" . BASE_HREF . "\" />\n\n";
	
	echo "<script type=\"text/javascript\">document.documentElement.className = 'js mod_$module';</script>\n\r";
	
    stylesAndJsTop($module);
	openGraphInfo($module);
	
    echo "
    	</head>
		<!--[if lt IE 7 ]> <body class=\"ie6\"> <![endif]--> 
		<!--[if IE 7 ]>    <body class=\"ie7\"> <![endif]--> 
		<!--[if IE 8 ]>    <body class=\"ie8\"> <![endif]--> 
		<!--[if IE 9 ]>    <body class=\"ie9\"> <![endif]--> 
		<!--[if (gt IE 9)|!(IE)]><!--> <body> <!--<![endif]-->
	";
}

/* Styles and JS tags that are to be included before the body tag */
function stylesAndJsTop($module) {
	
	$css_name = createMergedCSS();
    echo "<link href=\"uploads/js_css_cache/$css_name.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />\n\r";
    echo "<link href=\"uploads/js_css_cache/print.css\" rel=\"stylesheet\" type=\"text/css\" media=\"print\" />\n\r";
	echo "<link rel=\"stylesheet\" href=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css\" type=\"text/css\" media=\"all\" />\n\r";
	
    if(USE_TABLE_PARSER) { echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.8.2/css/jquery.dataTables.css\" />\n\r";}
    
	echo "
	<link rel=\"shortcut icon\" href=\"favicon.ico\" />
	<link rel=\"apple-touch-icon\" href=\"apple-touch-icon-precomposed.png\" />
	";
	
	if(GOOGLE_PLUS_PAGE_ID != "") echo "<link href=\"https://plus.google.com/" . GOOGLE_PLUS_PAGE_ID . "/\" rel=\"publisher\" />\n\r";
	if(RSS_LOCATION != "") echo "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" href=\"" . RSS_LOCATION . "\" />\n\r";
	if(SITEMAP_LOCATION != "") echo "<link rel=\"sitemap\" type=\"application/xml\" title=\"Sitemap\" href=\"" . SITEMAP_LOCATION . "\" />\n\r";
	
	echo "
    <!--[if lt IE 7 ]>
    <link rel=\"stylesheet\" href=\"css/core/ie6.css\" />
    <![endif]-->
    
    <script src=\"js/libs/modernizr-latest.js\"></script>
    <script src=\"js/libs/respond.min.js\"></script>
   	";

}

/* Open Graph Information. See http://ogp.me/ for more info */
function openGraphInfo($module) {
	echo "
	<meta property=\"og:site_name\" content=\"" . SITE_NAME . "\" />
	<meta property=\"og:type\" content=\"" . SITE_CATEGORY . "\" />
	<meta property=\"og:title\" content=\"" . getMetaInfo($module,'title') . "\" />
	<meta property=\"og:url\" content=\"" . curPageURL() . "\" />
	<meta property=\"og:description\" content=\"" . getMetaInfo($module,'description') . "\" />
    ";
}

/* A function to get the meta information from the page you are currently on */
function getMetaInfo($module,$ret) {
	$rs = mq("select pg_meta_$ret as n from " . DB_TBL_PAGES . " where pg_slug='$module'");
    if(mnr($rs)>0) {
        $rw = mfa($rs); return $rw['n'];
    } else {
        if($module == "home") { } else { return getMetaInfo("home",$ret); }
    }
}

/* This echos out all the tags that are placed beneath the final html visual code. Ie most JS is placed here */
function bottomPage($module) {
	echo "
	<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js\"></script>
    <script>!window.jQuery && document.write(unescape('%3Cscript src=\"js/libs/latest_jquery.min.js\"%3E%3C/script%3E'))</script>
    <script src=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js\"></script>
    ";
	
	$js_name = createMergedJs();
    echo "<script src=\"uploads/js_css_cache/$js_name.js\"></script>\n\r";
	
	if(USE_BX_SLIDER) { echo "<script src=\"http://bxslider.com/sites/default/files/jquery.bxSlider.min.js\"></script>\n\r"; }
	if(USE_TABLE_PARSER) { echo "<script src=\"http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.8.2/jquery.dataTables.min.js\"></script>\n\r"; }
	if(USE_FORM_VALIDATION) { echo "<script src=\"http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js\"></script>\n\r"; }
	
	echo "
    <!--[if lt IE 7 ]>
    <script src=\"js/libs/dd_belatedpng.js\"></script>
    <script>DD_belatedPNG.fix('img, .png_bg');</script>
    <![endif]-->
    ";
	
	if(USE_GOOGLE_MAPS != "") { echo "<script src=\"http://maps.googleapis.com/maps/api/js?key=" . GOOGLE_MAPS_API_KEY . "&sensor=false\"></script>"; }
	
	if(GOOGLE_ANALYTICS_CODE != "") {
	    echo "
	    <script>
	       window._gaq = [['_setAccount','" . GOOGLE_ANALYTICS_CODE . "'],['_trackPageview'],['_trackPageLoadTime']];
	        Modernizr.load({
	          load: ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js'
	        });
	    </script>
	    ";
	}
	if(USE_GOOGLE_PLUS) echo "<script src=\"https://apis.google.com/js/plusone.js\">{lang: 'en-GB'}</script>\n\r";
	if(USE_LINKEDIN) { echo "<script src=\"http://platform.linkedin.com/in.js\" type=\"text/javascript\"></script>\n\r"; }	
	if(USE_TWITTER) { echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"//platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>\n\r"; }	
	if(USE_PINTEREST) { echo "<script type=\"text/javascript\" src=\"//assets.pinterest.com/js/pinit.js\"></script>"; }
	if(USE_FACEBOOK_LIKE) {
		$fbappid = "";
		if(FACEBOOK_APP_ID != "") { $fbappid = "&appId=" . FACEBOOK_APP_ID; }
		echo "
		<div id=\"fb-root\"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = \"//connect.facebook.net/en_GB/all.js#xfbml=1$fbappid\";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		";
	}
	
	echo "
		</body>
		</html>
	";
}
?>