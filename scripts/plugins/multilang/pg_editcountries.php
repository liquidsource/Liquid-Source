<form method="post" action="scripts/action.php?action=activatecountries">
<article class="module width_full">
<header>
<h3 class="tabs_involved">Edit Active Countries</h3>
</header>
<div class="module_content">
<fieldset style="margin-left: 10px">
	<h4>Currently Active</h4>
	<?php
	$countries = Country::getActiveCountriesArray();
	foreach($countries as $country) {
		$checked = "";
		$disabled = "";
		if($country->isActive == "1") $checked = "checked";
		if($country->isDefault) $disabled = "disabled";
		echo "<input type=\"checkbox\" name=\"country_select[]\" id=\"country_select\" value=\"{$country->iso}\" $checked $disabled > ";
		echo $country->printable_name;
		if($country->isDefault) echo " <i>(default)</i>";
		echo "<br />";
	}
	?>
	
	<h4>Common</h4>
	<?php
	$countries = Country::getCommonCountries('1',0);
	foreach($countries as $country) {
		$checked = "";
		if($country->isActive == "1") $checked = "checked";
		echo "<input type=\"checkbox\" name=\"country_select[]\" id=\"country_select\" value=\"{$country->iso}\" $checked > ";
		echo $country->printable_name . "<br />";
	}
	?>
	
	<h4>Un Common</h4>
	<?php
	$countries = Country::getCommonCountries('0',0);
	foreach($countries as $country) {
		$checked = "";
		if($country->isActive == "1") $checked = "checked";
		echo "<input type=\"checkbox\" name=\"country_select[]\" id=\"country_select\" value=\"{$country->iso}\" $checked > ";
		echo $country->printable_name . "<br />";
	}
	?>
</fieldset>
</div>
<footer>
    <div class="submit_link">
	<input type="submit" value="Alter" class="alt_btn" />
	</div>
</footer>
</article>
</form>