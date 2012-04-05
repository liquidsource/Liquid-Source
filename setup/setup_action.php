<?php
$settingup = true;
global $dbconnexfailed;
include('setup_functions.php');
include('../scripts/system.php');
?>
<!doctype html>
    <html lang="en-gb" class="no-js mod_home">
    <head>
        <meta charset="utf-8" />
        <title>Home</title>
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        
        <meta name="description" content="Liquid Source Setup" />
        
        <meta name="language" content="en-gb" />
		<meta name="Copyright" content="Copyright Liquid Source 2012. All Rights Reserved." />
		
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        
        <meta name="robots" content="noindex,nofollow" />

		<link rel="shortcut icon" href="favicon.ico" />
		<link rel="apple-touch-icon" href="apple-touch-icon-precomposed.png" />
	    <link rel="stylesheet" href="../css/core/reset.css" />
	    <link rel="stylesheet" href="setup.css" />
	    <!--[if lt IE 7 ]>
	    <link rel="stylesheet" href="css/core/ie6.css" />
	    <![endif]-->
	    
	    <script src="../js/libs/modernizr-2.0.6.min.js"></script>
	    <script src="../js/libs/respond.min.js"></script>
	    <script src="../js/libs/latest_jquery.min.js"></script>
	    <script src="../js/core/forms.js"></script>
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
	        <h1 style="margin-bottom:20px">Setup Actions ...</h1>
	        <?php
	        setup_database($_POST);
			?>
			
	        <h1 style="margin-top:40px">Setup Complete</h1>
	        <p>Liquid Source is now setup and ready to use. We suggest you go to the admin and tweak the current setup of the site.</p>
	        <p>
	        	<label for="username"><strong>Username:</strong></label><i><?php echo $_POST['admin_username']; ?></i><br />
		        <label for="password"><strong>Password:</strong></label><i><?php echo $_POST['admin_password']; ?></i>
	        </p>
			<fieldset style="margin-top:20px">
				<input type="button" value="Go to the homepage" onclick="location.href='<?php echo BASE_HREF; ?>index.php'" />
			</fieldset>
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