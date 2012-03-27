<div class="wrap">
	<ul id="nav" class="group">
		<li><a href="home/">Home</a></li>
		<li><a href="moreinfo/">More Info</a></li>
		<li><a href="elements/">Elements</a></li>
		<li><a href="ex_login/">Login</a></li>
		<li><a href="ex_register/">Register</a></li>
		<li><a href="ex_contactus/">Contact us</a></li>
		<li><a href="aboutus/">About us</a></li>
		<?php if(Member::isLoggedin()) { ?><li><a href="scripts/action.php?action=logout">Logout</a></li><?php } ?>
		<li style='margin-left:20px;'><a href="admin/index.php">Backend Admin</a></li>
	</ul>
</div>