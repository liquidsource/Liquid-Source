<article class="module width_full" id="media_listing">
<header><h3 class="tabs_involved">Edit Site Options</h3></header>
<div class="tab_container">
<form method="post" action="scripts/action.php?action=updateSiteOptions">
<?php
$rs = mq("select distinct so_group from " . DB_TBL_SITE_OPTIONS . " where so_group <> '' and so_type='define' order by so_group");
while($rw = mfa($rs)) {
	echo "<fieldset style='margin: 20px;'><h1 style='margin: 0px 10px 10px'>" . stripslashes($rw['so_group']) . "</h1>";
	$so_group = mres($rw['so_group']);
	$rsi = mq("select * from " . DB_TBL_SITE_OPTIONS . " where so_group='$so_group'");
	while($rwi = mfa($rsi)) {
		$val = $rwi['so_val'];
		$id = $rwi['so_arg'];
		echo "<label style='width:250px'>" . $rwi['so_arg'] . "</label>";
		if($rwi['so_field_type'] == "bool") {
			$true_checked = "";
			$false_checked = "";
			if(strtolower($val) == 'true') {
				$true_checked = "checked";
			} else {
				$false_checked = "checked";
			}
			echo "
			<input type='radio' name='$id' id='$id' value='true' $true_checked /> True &nbsp; &nbsp;
			<input type='radio' name='$id' id='$id' value='false' $false_checked /> False
			";
		} else {
			echo "<input type=\"text\" name=\"$id\" id=\"$id\" value=\"$val\"  style='width:400px;margin-left:0px' />";
		}
			
		echo "<div class=\"clear\"></div>";
	}
	echo "
	<div class=\"submit_link\">
        <input type=\"submit\" value=\"Update\" class=\"alt_btn\">
    </div>
	</fieldset>";
} 
?>
</form>
</div>
</article>