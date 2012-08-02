<h1>Home</h1>
<p>Welcome to Liquid Source - the PHP project initializer. If you're reading this you've succesfully installed and are ready to roll on your next PHP based project.</p>
<p>Liquid Source is a 'Front Controller Pattern' based system,  meaning that all the content on the site is driven through the 'index.php' file in the root folder. The 'index.php' file sets
	up base variables, the classes and includes all the files that are needed for the system to run.</p>
<p>Although Liquid Source has an intuitive GUI backend to control certain aspects of the site, the front end is coded and designed by <strong><em>you</em></strong>; you won't find
	an array of designs, themes or menu styles to choose from (aka this is not Wordpress, Joomla etc).</p>

<h2>Quick Start Guide</h2>
<p>Heres a quick start guide for getting all projects off the ground quickly and easily. This is only what we recommend, but feel free to play around and use Liquid Source in anyway you want.</p>
<h5>1. Delete the setup files</h5>
<p>You can safely delete the '<code>setup/</code>' folder and all its contents.</p>
<?php
if(!file_exists('setup/')) { echo "<label class='error'>Done!</label>"; }
?>

<h5>2. Set the site base options</h5>
<p>Go into the Backend Admin, and set the 'Site Options' variables as needed. You can of course come back to this page anytime you need if you do not know all the required variables ath the present time.</p>

<h5>3. Edit the initial core css attributes</h5>
<p>Load up and edit the main values in '<code>css/core/less_incs/core_base.less</code>'. After setting the core values load up the <a href="elements/">Elements Page</a> and check to see the core values are correct.</p>
<strong>Forms</strong>
<p>Liquid Source comes pre-packaged with a default look and feel for forms. However your project may differ from what we've chosen. If so load up and edit '<code>css/core/forms.less</code>' and change as required.</p>

<h5>4. Start programming the site ....</h5>
<p>You're basically ready to rock and roll.</p>
<p>Why  not start adding new pages (both in the backend and physically creating the files), edit the '<code>css/core/master.less</code>' css file, or tweak the '<code>incs/header.php</code>' and '<code>incs/footer.php</code>' files.</p>

