<?php if(Member::isLoggedin()) { ?><h4 class='alert alert_warning'>Please <a href="scripts/action.php?action=logout">logout</a> to view this page.</h4><?php } else { ?>
	
<h1>Login</h1>
<p>This is an example login page for members.</p>
<form method="post" action="scripts/action.php?action=login&to=account" autocomplete="off" id="login_frm" style="margin-bottom: 20px;" onsubmit="return submitForm('login_frm')">
	<fieldset>
		<label for="username">Username</label>
		<input type="text" name="username" id="username" />
	</fieldset>
	
	<fieldset>
		<label for="username">Password</label>
		<input type="password" name="password" id="password" />
	</fieldset>
	
	<fieldset>
		<input type="submit" value="Login"/>
	</fieldset>
</form>

<p><a href="register">Not yet a member?</a></p>

<p><a href="javascript:;" onclick="$('#forget_password').toggle('slow');">Forgot password?</a></p>


<article id="forget_password" class="dnone">
	<form method="post" action="scripts/action.php?action=forgotpswd&to=login" id="forgot_frm" onsubmit="return submitForm('forgot_frm')">
		<label for="fgt_email">Your Email Address</label>
		<input type="email" name="fgt_email" id="fgt_email" value="" />
		<input type="submit" value="Resend"  />
	</form>
</article>
<?php } ?>