<p>This is an example bespoke page.</p>
<hr />
<?php if(Member::isLoggedin()) { ?><h4 class='alert alert_warning'>Please <a href="scripts/action.php?action=logout">logout</a> to view this page.</h4><?php } else { ?>

<h1>Register</h1>
<p>This is an example registration page for new members to sign up.</p>
	<form method="post" action="scripts/action.php?action=ex_register" name="reg_frm" id="reg_frm" onsubmit="return submitForm('reg_frm')">
	
	<h4>Logon Details</h4>
	    <fieldset>
		    <label>Username:</label>
	    	<?php echo showMemberInput('m_username'); ?>
	    </fieldset>  
	    <fieldset>
		    <label>Password:</label>
	    	<?php echo showMemberInput('m_password','m_password','','password'); ?>
		</fieldset>
	    <fieldset>
		    <label>Confirm Password:</label>
	    	<?php echo showMemberInput('m_password_t','m_password_t','','password'); ?>
		</fieldset>
	    <fieldset>
		    <label>Email:</label>
	    	<?php echo showMemberInput('m_email'); ?>
		</fieldset>
	    <fieldset>
		    <label>Confirm Email:</label>
	    	<?php echo showMemberInput('m_email_t','m_email_t','','email'); ?>
		</fieldset>
	    <fieldset>
	    	
	<h4>Personal Details</h4>
	    <fieldset>
		    <label>First Name:</label>
	    	<?php echo showMemberInput('m_fname'); ?>
		</fieldset>
	    <fieldset>
		    <label>Last Name:</label>
	    	<?php echo showMemberInput('m_fname'); ?>
		</fieldset>
	    <fieldset>
	    	<input type="submit" value="Submit" />
	    </fieldset>
	    
	</form>
<?php } ?>