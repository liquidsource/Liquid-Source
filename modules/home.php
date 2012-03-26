<h1>Home</h1>
<p>Welcome to Liquid Source - the PHP project initializer. If you're reading this you've succesfully installed and are ready to roll on your next PHP based project.</p>
<p>Liquid Source is a 'Front Controller Pattern' based system,  meaning that all content on the site is driven through the 'index.php' file in the root folder. The 'index.php' file sets
	up base variables, the classes and includes all the files that are needed for the system to run.</p>
<p>Although Liquid Source has an intuitive GUI backend to control certain aspects of the site, the front end is coded and designed by <strong><em>you</em></strong>; you won't find
	an array of designs, themes or menu styles to choose from (aka this is not Wordpress, Joomla etc).</p>

<h2>General File Hierarchy</h2>
<p>Below is a rough outline of the main folder structure. We have not shown all folders as some are not needed for a general understanding of the system.</p>

<table>
<tr><td><pre>- index.php</pre></td><td></td></tr>
<tr><td><pre>|- admin</pre><td><em class="noinfo">Deals with the backend admin interface</em></td></tr>
<tr><td><pre>|- css</pre></td><td></td></tr>
<tr><td><pre>&nbsp;&nbsp;|- core</pre></td><td><em class="noinfo">Core less and css files for the site</em></td></tr>
<tr><td><pre>&nbsp;&nbsp;|- packages</pre></td><td><em class="noinfo">Holds the css for optional pre-built packages</em></td></tr>
<tr><td><pre>|- dev</pre></td><td><em class="noinfo">Holds any development work, such as photoshop files, specifications etc</em></td></tr>
<tr><td><pre>|- images</pre></td><td><em class="noinfo">For the images</em></td></tr>
<tr><td><pre>|- incs</pre></td><td><em class="noinfo">Core include files, including headers, menu and the footer</em></td></tr>
<tr><td><pre>|- js</pre></td><td></td></tr>
<tr><td><pre>&nbsp;&nbsp;|- core</pre></td><td><em class="noinfo">Core javascript files</em></td></tr>
<tr><td><pre>&nbsp;&nbsp;|- libs</pre</td><td><em class="noinfo">Holds the javascript for optional pre-build libraries</em></td></tr>
<tr><td><pre>|- modules</pre></td><td><em class="noinfo">Holds all the sites modules</em></td></tr>
<tr><td><pre>|- scripts</pre></td><td><em class="noinfo"></em></td></tr>
<tr><td><pre>&nbsp;&nbsp;|- classes</pre></td><td><em class="noinfo">All core site classes</em></td></tr>
<tr><td><pre>&nbsp;&nbsp;|- config</pre></td><td><em class="noinfo">The site config file that is used by the system. You would have changed this already</em></td></tr>
<tr><td><pre>&nbsp;&nbsp;|- core</pre></td><td><em class="noinfo">A collection of Liquid Source core php function files</em></td></tr>
<tr><td><pre>|- setup</pre></td><td><em class="noinfo">After setup this folder can be deleted. Used to setup the site.</em></td></tr>
<tr><td><pre>|- uploads</pre></td><td><em class="noinfo">A folder to hold all media uploads. You may have to enable write permissions on this folder.</em></td></tr>
<tr><td><pre>&nbsp;&nbsp;|- js_css_cache</pre></td><td><em class="noinfo">This holds all the js and css compiled code.</em></td></tr>
</table>


</em>

<h2>The Module System</h2>
<p>Liquid source can be driven through two seperate page types; 'Text' and 'Bespoke'. Bespoke pages are held in the</p>
<pre>modules/</pre>
<p>folder and are specifically designed pages that are included into the index.php.</p>
<p>Text pages are textual pages that have a title and content (and optional meta data). All text pages are driven through index.php, which in turn loads up</p>
<pre>modules/text.php</pre>
<p>to get the relevant text page content. In the base setup 'Login' and 'Register' are examples of bespoke pages, and 'About Us' is an example text page.</p>

<p><strong>But how does the system know what to load up?</strong></p>
<p>In the backend admin you can create pages and define them as bespoke or text. Just remember to set the page slug as the relevant url 'module slug'. If a page is not specified in the backend
	the system will still try loading up the page, looking in the 'modules' folder for a bespoke page.</p>

<h2>CSS and LESS</h2>
<p>Liquid Source utilises the amazing work of Alexis Sellier and uses the LESS CSS framework. If you are unfamliar with LESS we suggest you check out the documentation on the LESS page that can be found
	<a href="http://lesscss.org/">here</a>.</p>
<p>With Liquid Source we use a LESS PHP driven Pre-processor to dynamically build the needed css files and spit them out as one file for the browser to use. All .less files can be found in the</p>
<ul><li><pre>css/core/</pre></li></ul>
<p>folder. The main files that you will want to edit to start with will be</p>
<ul>
	<li><pre>css/core/less_incs/core_base.less</pre></li>
	<li><pre>css/core/master.less</pre></li>
</ul>

<h2>Page Head and Structure</h2>
<p>One of your first stops in the Backend Admin will be <em>'Admin > Site Options'</em> (located near the bottom of the menu). Here you can define a large portion of the meta tag information and
	site options. This information is passed into every page and the page head section is built up bespokely for that page.</p>
<p>For people who require more complex meta tagging your port of call will be the file located at</p>
<ul><li><pre>scripts/functions/page_structure.php</pre></li></ul>
<p>This file sorts out the css, javascript includes, page meta and the module loading mechanism.</p>


<h2>Classes</h2>
<p>Liquid Source comes with an array of classes ready for you to use. Installed in the base package are the following classes:</p>
<ul style="margin-left:20px">
	<li><pre style='display:inline'>Category</pre> - deals with categories for various other classes.</li>
	<li><pre style='display:inline'>Email</pre> - class to build and send out emails.</li>
	<li><pre style='display:inline'>Feed</pre> - can build and write xml feeds, including sitemaps and rss feeds.</li>
	<li><pre style='display:inline'>Links</pre> - deals with links and lists of links.</li>
	<li><pre style='display:inline'>Liquid Source</pre> - The main root class that deals with a whole heap of functionality.</li>
	<li><pre style='display:inline'>Media</pre> - holds information on any media that is used on the site (such as uploaded images).</li>
	<li><pre style='display:inline'>Member</pre> - used to register, login, logout, sanitze and security check members.</li>
	<li><pre style='display:inline'>Post</pre> - for displaying and echoing out posts.</li>
	<li><pre style='display:inline'>Page</pre> - for displaying and echoing out pages.</li>
	<li><pre style='display:inline'>Template</pre> - for building email templates than can be used to send base emails.</li>
	<li><pre style='display:inline'>Menu</pre> - for storing and displaying menu structures for the site.</li>
</ul>
<p>As well as additional core classes. Documentation on how to use the classes is included in your core documentation.</p>

<h2>Pre-Installed Packages and Modules</h2>
<p>One the major advantages to Liquid Source is its initial group of core packages that come with the system, allowing the developer to save time and effort in figuring out and including
	base elements again and again. All the packages can be seen from the <a href="elements/">Elements Page</a>, and more information is included in the core documentation. A list of included items is below:</p>
	
<strong>Javascript Libraries</strong>
<ul style="margin-left:20px">
	<li>Jquery (v1.7.1 <a href="http://www.jquery.com/">here</a>)</li>
	<li>Modernizr (v2.5.3 - <a href="http://www.modernizr.com/">here</a>)</li>
	<li>Respond (<a href="https://github.com/scottjehl/Respond">here</a>)</li>
</ul>

<strong>GUI Elements</strong>
<ul style="margin-left:20px">
	<li>Jquery UI (v1.8.16 - <a href="http://jqueryui.com/">here</a>)</li>
	<li>Tooltips (<a href="http://osvaldas.info/blog/elegant-css-and-jquery-tooltip-responsive-mobile-friendly">here</a>)</li>
	<li>Shadow Box (v3.0.3 - <a href="http://www.shadowbox-js.com/">here</a>)</li>
	<li>Flex Slider (v1.8 - <a href="http://www.woothemes.com/flexslider/">here</a>)</li>
	<li>BX Slider (v3.0 - <a href="http://bxslider.com/">here</a>)</li>
	<li>Google Maps (v3.0 - <a href="http://code.google.com/apis/maps/documentation/javascript/">here</a>)</li>
	<li>Datatables (v1.8.2 - <a href="http://datatables.net/">here</a>)</li>
</ul>
<strong>Social Networks</strong>
<ul style="margin-left:20px">
	<li>Twitter tweet buttons</li>
	<li>Facebook like button</li>
	<li>LinkedIn like button</li>
	<li>Google Plus One button</li>
	<li>PInterest button</li>
</ul>
<strong>Commenting</strong>
<ul style="margin-left:20px">
	<li>Disqus (<a href="http://www.disqus.com">here</a>)</li>
	<li>Livefyre (<a href="http://www.livefyre.com">here</a>)</li>
</ul>
<strong>Form Protection</strong>
<ul style="margin-left:20px">
	<li>Liquid Source properitary bot protection</li>
</ul>

<h2>Other Gumf</h2>
<p>Liquid Source is built in HTML 5 with CSS3, with a respoinsive design in mind. For backwards compatability for these features, and a whole heap of useful features
	we use Modernizr v2.5.3.</p>
<p>The reset.css, .htaccess and web.config are based on code from HTML Boilerplate. More info <a href="http://html5boilerplate.com/">here</a>.</p>
<p>All emails sent out via the email class are processed through a HTML Email boilerplate class, based on knowledge from <a href="http://htmlemailboilerplate.com/">here</a>.</p>
<p>For IE6, we use belatedpng for a transparent png fix. There is also a conditional IE6 stylesheet in the 'css/core' folder.</p>
<p>The amazing timthumb.php script is included for generation of image thumbnails (location in 'scripts/functions' folder). More info <a href="http://code.google.com/p/timthumb/">here</a>.</p>
<p>To aid with responsive layouts we also use Respond.js.</p>
<p>For processing the LESS files into CSS via PHP we use lessphp v0.3.3 which can be found <a href="http://leafo.net/lessphp/docs/">here</a>.</p>
<p>For minifying the JS before outputting to the browser we use JSMin v1.1.1 by Ryan Grove. More info <a href="https://github.com/rgrove/jsmin-php/">here</a>.</p>

