<p>This is an example bespoke page.</p>
<hr />
<h1 class="main_h1">Contact Us</h1>
<p>This is an example contact page..</p>
<form method="post" action="scripts/action.php?action=contactus&to=contactus" id="contact_frm" name="contact_frm" onSubmit="return submitForm('contact_frm');">
    <fieldset>
        <label>Name:</label>
        <input type="text" name="cu_namey" id="cu_namey" />
    </fieldset>
    <fieldset>
        <label>Email:</label>
        <input type="email" name="cu_emaily" id="cu_emaily" />
    </fieldset>

    <fieldset>
        <label>Message:</label><br />
        <textarea name="cu_msg" id="cu_msg"></textarea>
    </fieldset>
    <input type="submit" value="Submit" />
</form>
