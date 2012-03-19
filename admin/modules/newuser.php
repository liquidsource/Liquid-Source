<?php
$mid = $_GET['mid'];
if($mid != "") {
    $member = new Member($mid);
	if($member->protected) $disabled = " disabled ";
}
?>
<article class="module width_full" id="newuser">
<header><h3 class="tabs_involved">New / Edit Members</h3></header>

<form method="post" action="scripts/action.php?action=newuser&to=newuser&mid=<?php echo $mid; ?>" id="admin_newuser_frm" onsubmit="return submitForm('admin_newuser_frm');">
<input type="hidden" name="cur_mid" id="cur_mid" value="<?php echo $mid; ?>" />
<div class="tab_container">
    <div class="module_content">
        <fieldset>
            <label style="width:150px">Site User</label>
            <span class="m_type"><input type="radio" name="m_type" id="m_type" value="U" <?php if($member->type == "U") echo "checked=\"checked\""; ?>  />&nbsp;</span>
            <div class="clear" style="padding:1px;">&nbsp;</div>
            <label style="width:150px">Admin</label>
            <span class="m_type"><input type="radio" name="m_type" id="m_type" value="A" <?php if($member->type == "A") echo "checked=\"checked\""; ?>  />&nbsp;</span>
        </fieldset>
        
        <fieldset>
            <label style="width:150px">Email</label>
            <input type="email" name="m_email" id="m_email" value="<?php echo $member->email; ?>" style="width:300px" required>
            <div class="clear" style="padding:1px;">&nbsp;</div>
            <label style="width:150px">Username</label>
            <input type="text" name="m_username" id="m_username" value="<?php echo $member->username; ?>" style="width:300px" <?php echo $disabled; ?> required>
            <div class="clear" style="padding:1px;">&nbsp;</div>
            <label style="width:150px">Password</label>
            <input type="text" name="m_password" id="m_password" value="" style="width:300px">
        </fieldset>
        
        <fieldset>
        	<?php
        	$mem_arr = Member::getMemberProfileArray();
			foreach($mem_arr as $arg => $val) {
			?>
            <div class="clear" style="padding:1px;">&nbsp;</div>
            <label style="width:150px"><?php echo $val[1]; ?></label>
            <input type="text" name="mp_<?php echo $val[0]; ?>" id="mp_<?php echo $val[0]; ?>" value="<?php echo $member->$val[0]; ?>" style="width:300px" />
            <?php } ?>
        </fieldset>
    </div>
    <footer>
        <div class="submit_link">
            <input type="submit" value="Update / Add" class="alt_btn">
        </div>
    </footer>
</div>
</form>
</article>