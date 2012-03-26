<?php
$settingup = true;
global $dbconnexfailed, $failedWriteable;
include('setup_functions.php');
include('../scripts/system.php');
if($dbconnexfailed || $_GET['error'] == "dbconnection") { $failedDB = true; }
?>
<!doctype html>
    <html lang="en-gb" class="no-js mod_home">
    <head>
        <meta charset="utf-8" />
        <title>Setup Liquid Source</title>
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        
        <meta name="description" content="Liquid Source Setup" />
        
        <meta name="language" content="en-gb" />
		<meta name="Copyright" content="Copyright Liquid Source 2012. All Rights Reserved." />
		
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        
        <meta name="robots" content="noindex,nofollow" />

		<link rel="shortcut icon" href="../favicon.ico" />
		<link rel="apple-touch-icon" href="../apple-touch-icon-precomposed.png" />
	    <link rel="stylesheet" href="../css/core/reset.css" />
	    <link rel="stylesheet" href="setup.css" />
	    <!--[if lt IE 7 ]>
	    <link rel="stylesheet" href="css/core/ie6.css" />
	    <![endif]-->
	    
	    <script src="../js/libs/modernizr-2.0.6.min.js"></script>
	    <script src="../js/libs/respond.min.js"></script>
	    <script src="../js/libs/latest_jquery.min.js"></script>
	    <script src="../js/core/ls_forms.js"></script>
    </head>
<!--[if lt IE 7 ]> <body class="ie6"> <![endif]--> 
<!--[if IE 7 ]>    <body class="ie7"> <![endif]--> 
<!--[if IE 8 ]>    <body class="ie8"> <![endif]--> 
<!--[if IE 9 ]>    <body class="ie9"> <![endif]--> 
<!--[if (gt IE 9)|!(IE)]><!--> <body> <!--<![endif]-->
<header>
<div id="top_header">
	<div class="wrap group">
	    <img src="../images/logo.png" alt="top logo" id="top_logo" class="f_left" />
	</div>
</div>
</header>
<section id="main" role="main">
    <div class="wrap group">
        <div class='box' style="width:600px">
	        <h1>Setup</h1>
	        <?php if($failedDB) { ?>
		        <p><strong>Oh no!</strong></p>
		        <p>It doesn't look as though you've completed your core site config file, or the database connection details are incorrect.</p>
		        <p>We need you to provide the database details in this file to setup Liquid Source. There's
		        	more information and a walkthrough in the <a href="../../docs/readme.html">ReadMe documentation</a>.</p>
		        <p> ... <a href="setup.php">ok I've done that, lets try again</a> ...</p>
	        <?php } elseif($failedWriteable) { ?>
	        	<p><strong>Oh no!</strong></p>
	        	<p>You need to allow Liquid Source write access to the following folders to complete setup:</p>
	        	<code>/uploads/</code>
	        	<p>One or some of these folders aren't writeable.</p>
	        	<p> ... <a href="setup.php">ok I've done that, lets try again</a> ...</p>
	        <?php } else { ?>
		        <form method="post" action="setup_action.php" onsubmit="return submitForm('ls_setup_form');" name="ls_setup_form" id="ls_setup_form" />
		        	<input type="hidden" name="setup_from" id="setup_from" value="ls_setup_real" />
			        <p>Welcome to the Liquid Source setup. You may wish to peruse the <a href="../../docs/readme.html">ReadMe documentation</a>, otherwise just fill in the form below and we'll
			        	setup the base site for you.</p>
			        	
			        <h2 style="margin:30px 0px 10px">Required Information</h2>
			        <fieldset>
			        	<label for="admin_username">Admin username</label>
			        	<input type="text" name="admin_username" id="admin_username" required /><br />
			        	<i class='sub_info'>Usernames are case sensitive and can only contain alphanumeric characters (A-Z, 0-9) or an underscore.</i>
			        	
			        </fieldset>
			        <fieldset><label for="admin_password">Admin password</label><input type="password" name="admin_password" id="admin_password" required /></fieldset>
			        <fieldset>
			        	<label for="admin_password_r">Re-type password</label><input type="password" name="admin_password_r" id="admin_password_r" required /><br />
			        	<i class='sub_info'>Password are also case sensitive and can only contain alphanumeric characters (A-Z, 0-9) or an underscore.</i>
			        </fieldset>
			        <hr />
			        <fieldset><label for="your_email">Your email</label><input type="email" name="your_email" id="your_email" required /></fieldset>
			        <hr />
			        <input type="submit" value="Ok lets go" class="bluebtn action" style="margin-top:30px" />
		        </form>
	        <?php } ?>
        </div>
    </div>
</section>
<footer>
    <div class="wrap group">
        <ul class="group">
            <li class="noleftpad">Copyright &copy; 2012</li>
            <li class="endlist"><a href="http://www.liquid-source.com">Built by Liquid Source</a></li>
        </ul>
    </div>
</footer>
<!--[if lt IE 7 ]>
<script src="js/libs/dd_belatedpng.js"></script>
<script>DD_belatedPNG.fix('img, .png_bg');</script>
<![endif]-->
</body>
</html>
	