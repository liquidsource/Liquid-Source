<p>This is an example bespoke page.</p>
<hr />
<?php if(Member::isLoggedin()) { ?><h4 class='alert alert_warning'>Please <a href="scripts/action.php?action=logout">logout</a> to view this page.</h4><?php } else { ?>

<h1>Register</h1>
<p>This is an example registration page for new members to sign up.</p>
	<form method="post" action="scripts/action.php?action=ex_register" name="reg_frm" id="reg_frm" onsubmit="return submitForm('reg_frm')">
	
	<h4>Logon Details</h4>
	    <fieldset>
		    <label>Username:</label>
		    <input type="text" name="r_username" id="r_username" value="" />
	    </fieldset>  
	    <fieldset>
		    <label>Password:</label>
		    <input type="password" name="r_password" id="r_password" />
		</fieldset>
	    <fieldset>
		    <label>Confirm Password:</label>
		    <input type="password" name="r_password_t" id="r_password_t" />
		</fieldset>
	    <fieldset>
		    <label>Email:</label>
		    <input type="email" name="r_email" id="r_email" value="" />
		</fieldset>
	    <fieldset>
		    <label>Confirm Email:</label>
		    <input type="email" name="r_email_t" id="r_email_t" />
		</fieldset>
	    <fieldset>
	    	
	<h4>Personal Details</h4>
	    <fieldset>
		    <label>First Name:</label>
		    <input type="text" name="r_fname" id="r_fname" value="" />
		</fieldset>
	    <fieldset>
		    <label>Last Name:</label>
		    <input type="text" name="r_lname" id="r_lname" value="" />
		</fieldset>
	    <fieldset>
	    	<input type="submit" value="Submit" />
	    </fieldset>
	    
	</form>
<?php } ?>