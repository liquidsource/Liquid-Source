<article class="module width_full" id="media_listing">
<header><h3 class="tabs_involved">Edit Member Profiles</h3></header>
<div class="tab_container">
	<form method="post" action="scripts/action.php?action=updatememberprofile&to=editmemberprofile">
	<div style="padding:0px 10px">
	<p>Members automatically have the following data attributes that are stored for them:</p>
	<ul>
		<li>Username</li>
		<li>Hash of their password</li>
		<li>Date created</li>
		<li>Type (Admin / User)</li>
		<li>Active (1/0)</li>
		<li>User Level</li>
		<li>Last login</li>
	</ul>
	
	<p>From this screen you can edit / amend other attributes:</p>
	<table class="dataTable">
		<thead><tr><th>Member attribute</th><th>Short code</th><th>Input type</th><th>Additional info</th><th></th></tr></thead>
		<tbody>
		<?php
		$it_types = array('text','email','password','select','textarea','bool','special');
		$rs = mq("select * from " . DB_TBL_MEMBER_PROFILE_MASTER . " order by mpaid");
		while($rw = mfa($rs)) {
			$mpaid = $rw['mpaid'];
			$it = $rw['mpa_inputtype'];
			
			$special = "";
			if($it == "select") {
				$rs_o = mq("select s_val from " . DB_TBL_SELECTBOX . " where s_type='" . $rw['mpa_sc'] . "' order by sid");
				while($rw_o = mfa($rs_o)) {
					$special .= $rw_o['s_val'] . "; ";
				}
			}
					
			echo "<tr>
				<td><input type=\"text\" name=\"mpa_english_$mpaid\" id=\"mpa_english_$mpaid\" value=\"" . stripslashes($rw['mpa_english']) . "\" /></td>
				<td><input type=\"text\" name=\"mpa_sc_$mpaid\" id=\"mpa_sc_$mpaid\" value=\"" . $rw['mpa_sc'] . "\" /></td>
				<td>
					<select name=\"mpa_inputtype_$mpaid\" id=\"mpa_inputtype_$mpaid\">";
					
					foreach($it_types as $ittype) {
						echo "<option value=\"$ittype\"";
							if($it == $ittype) echo " selected ";
							echo ">$ittype</option>";
					}
			
			echo "	</select>
				</td>
				<td>
					<textarea name=\"mpa_options_$mpaid\" id=\"mpa_options_$mpaid\" style=\"width:300px;height:40px\">$special</textarea>
				</td>
				<td>
			 		<a href=\"scripts/action.php?action=deletememberprofile&mpaid=$mpaid&to=editmemberprofile\"><img src=\"images/icn_trash.png\" title=\"Trash\"></a>
			 	</td>
			</tr>";
		}
		?>
		</tbody>
	</table>
	</div>
	<p style="clear:both"></p>
    <br />
    <footer>
	    <div class="submit_link">
	    	<input type="submit" value="Update" class="alt_btn" />
	    </div>
	</footer>
	</form>
</div><!-- end of .tab_container -->
</article><!-- end of content manager article -->


<form method="post" action="scripts/action.php?action=newmemberprofile">
<article class="module width_full">
<header><h3 class="tabs_involved">New Member Profile Attribute</h3></header>
<div class="tab_container">
    <table class="tablesorter" cellspacing="0">
    <thead>
    	<tr>
    		<th>Member attribute</th>
    		<th>Short code</th>
    		<th>Input type</th>
    	</tr>
    </thead>
    <tbody>
        <tr>
            <td><input type="text" name="mpa_english" id="mpa_english" value="" /></td>
            <td><input type="text" name="mpa_sc" id="mpa_sc" value="" /></td>
            <td>
            	<select name="mpa_inputtype" id="mpa_inputtype">
					<?php
					foreach($it_types as $ittype) {
						echo "<option value=\"$ittype\">$ittype</option>";
					}
					?>
				</select>
			</td>
        </tr>
        <tr>
            <td><input type="submit" value="Add" class="alt_btn" /></td>
        </tr>
    </tbody> 
    </table>
</div>

</article>
</form>


<style>
input[type=text] { width: 150px}
</style>