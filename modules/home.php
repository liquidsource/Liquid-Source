<h1>Welcome to Liquid Source</h1>
<p>Welcome to Liquid Source - the Recruitment PHP project initializer. If you're reading this you've succesfully installed and are ready to roll on your next job board based PHP project.</p>
<p>Liquid Source is a 'Front Controller Pattern' based system,  meaning that all the content on the site is driven through the 'index.php' file in the root folder. The 'index.php' file sets
	up base variables, the classes and includes all the files that are needed for the system to run.</p>
<p>Although Liquid Source has an intuitive GUI backend to control certain aspects of the site, the front end is coded and designed by <strong><em>you</em></strong>; you won't find
	an array of designs, themes or menu styles to choose from.</p>
<p>As part of the install of Liquid Source we have pre-populated some core job categories, a range of jobs, a company and some blog posts. This will allow you to use the system straight away and test
	various features.</p>
<p>Liquid Source comes pre-installed with a whole host of useful recruitment classes, functions and functionality.</p>
<p><a href="moreinfo/">Read more</a> for a more detailed explanation of the liquid source system, and Liquid Sources core pre-packaged functionality.</p>

<hr />

<h2>Quick Start Guide</h2>
<p>Cant wait ... sure, here's a quick start guide for getting all projects off the ground quickly and easily. This is only what we recommend, but feel free to play around and use Liquid Source in anyway you want.</p>
<h5>1. Delete the setup files</h5>
<p>You can safely delete the '<code>setup/</code>' folder and all of it's contents. This folder and its files were used for the setup of Liquid Source on this system, and are now defunct.</p>
<?php if(!file_exists('setup/')) { echo "<label class='error'>Done!</label>"; } ?>

<h5>2. Set the site base options</h5>
<p>Go into the Backend Admin, and set the 'Site Options' variables as needed. You can of course come back to this page anytime you need if you do not know all the required variables at the present time.</p>
<p>After this set up the Member Input Fields and Job Input Fields as required. These fields determine what info you want to capture and collect for members and jobs alike. Again, you can of course come back to this at any time to add new attributes.</p>

<h5>3. Edit the initial core css attributes</h5>
<p>Load up and edit the main values in '<code>css/core/less_incs/core_base.less</code>'. After setting the core values load up the <a href="elements/">Elements Page</a> to see how the core less values alter various page elements.</p>
<strong>Forms</strong>
<p>Liquid Source comes pre-packaged with a default look and feel for forms. However your project may differ from what we've chosen (and probably does). Load up and edit '<code>css/core/forms.less</code>' to alter the form look and feel. Once
	again load up the <a href="elements/">Elements Page</a> to see and verfiy the changes.</p>

<h5>4. Play</h5>
<p>Play around with the backend and front end pre-installed widgets / pages to see how the site functionality works, and to get to grips with the process flow Liquid Source uses.</p>

<h5>5. Start programming the site ....</h5>
<p>You're basically ready to rock and roll.</p>
<p>Why  not start adding new pages (both in the backend and physically creating the files), edit the '<code>css/core/master.less</code>' css file, or tweak the '<code>incs/header.php</code>' and '<code>incs/footer.php</code>' files.</p>
