<?php
switch ($plugin_code) {
	// ADMIN HEAD JAVASCRIPT
	case "admin.head.javascript":
		?>
		<script>
		function addTranslation(pid) {
			iso = $('#country').val();
			if(iso != "") { 
				location.href = "index.php?module=newpage&pgid=" + pid + "&iso=" + iso;
			} else {
				iso = $('#country').addClass('input_error');
			}
		}
		</script>
		<?php
		break;
		
	// LIQUID_SOURCE CLASS EXTRAS
	case "class.liquid_source.pages.wc":
		if($iso == "") { $iso = Country::getDefaultCountry()->iso; }
		$wc .= " and pg_iso='$iso' ";
		break;
		
	// PAGE CLASS EXTRAS - SAVING / UPDATING ETC
	case "class.page.construct.wc":
		$iso = $_POST['iso'];
		if($iso) {
			if($pg_slug != NULL) {
				$defiso = Country::getDefaultCountry()->iso;
				$rs = mq("select pgid from " . DB_TBL_PAGES . " where pg_slug='$pg_slug' and pg_iso='$defiso' and pg_posttype != 'inherit'");
				if(mnr($rs) > 0) {
					$rw = mfa($rs);
					$pgid = $rw['pgid'];
				}
			}
			if($pgid != NULL) {
				echo "yey"; 
				$translation = new Translation('page',$pgid,$iso);
				$trans_pgid = $translation->iso_pgid;
				$wc = "pgid='$trans_pgid' ";
			}
			$pluginWClause = true;
		}
		break;
	case "class.page.construct.setup":
		$this->data['iso'] = $rw['pg_iso'];
		$this->data['iso_parent'] = $rw['pg_iso_parent'];
		$country = new Country($rw['pg_iso']);
		$this->data['country'] = $country;
		break;
	case "class.page.update.extra":
		$rsu = mq("update " . DB_TBL_PAGES . " set pg_iso='" . $this->data['iso'] . "', pg_iso_parent='' where pgid='$pgid_n'");
		break;
	case "class.page.update.old.return":
		if($parent_pgid != "") { $pgid = $parent_pgid; }
		break;
	case "class.page.update.new.setiso":
		if($iso == "") { $iso = Country::getDefaultCountry()->iso; }
		break;
	case "class.page.update.new.return":
		$rsu = mq("update " . DB_TBL_PAGES . " set pg_iso='$iso', pg_iso_parent='$parent_pgid' where pgid='$pgid'");
		if($parent_pgid != "") { $pgid = $parent_pgid; }
		break;
		
	// ADMIN MENU
	case "admin.view.menu":
		?>
		<h3 id="admin">Multi-language</h3>
		<ul>
		    <li class="icn_categories"><a href="?module=_multilang/pg_editcountries">Active Countries</a></li>
		</ul>
		<hr />
		<?php
		break;
		
	// ADMIN ACTION
	case "admin.action":
		if($action == "activatecountries") {
			$rs = mq("update " . DB_TBL_COUNTRIES . " set isActive='0' where isDefault='0'");
			foreach($_POST['country_select'] as $iso) {
				$rsu = mq("update " . DB_TBL_COUNTRIES . "  set isActive='1' where iso='$iso'");
			}
			$to = "_multilang/pg_editcountries";
		}
		break;
		
		
	// ADMIN EDIT PAGES VIEW
	case "admin.view.page.editpages.plugin_arr":
		$plugin_arr = array('iso' => 'gb');
		break;
		
	// ADMIN NEW PAGE VIEW
	case "admin.view.page.newpage.top":
		$iso = $_GET['iso'];
		$isTranslation = false;
		if($iso != "") {
			$country = new Country($iso);
			$isTranslation = true;
		}
		if($isTranslation) {
			$translation = new Translation('page',$pgid,$iso);
			$pgid = $translation->iso_pgid;
			if($pgid != "") { $foundTranslation = true; }
			$parent_pgid = $_GET['pgid'];
		}
		break;
	case "admin.view.page.newpage.top_i":
		if($isTranslation) {
			$orig_page = new Page('',$_GET['pgid']); 
			$pagetype = $orig_page->type;
		}
		break;
	case "admin.view.page.newpage.form.top":
		?>
		<input type="hidden" name="iso" id="iso" value="<?php echo $iso; ?>" />
		<input type="hidden" name="parent_pgid" id="parent_pgid" value="<?php echo $parent_pgid; ?>" />
		
		<article class="module width_full">
		    <header><h3>Translation</h3> <img src="images/openclose.png" onclick="$('.new_page_trans_info').toggle('slow')" /></header>
		    <div class="module_content new_page_trans_info">
				<?php if($isTranslation) { ?>
		    	<p>This is a translation of the page: <a href="index.php?module=newpage&pgid=<?php echo $orig_page->id; ?>"><?php echo $orig_page->title; ?></a></p>
		    	<p>From the default language: <strong><?php echo Country::getDefaultCountry()->printable_name; ?> (<?php echo Country::getDefaultCountry()->iso; ?>)</strong></p>
		    	<p>Into the language: <strong><?php echo $country->printable_name . " ($iso)"; ?></strong></p>
		    	<p><a href="javascript:;" onclick="$('.trans_details').toggle('slow')">Toggle original text</a></p>
				<?php } else { ?>
				<p>This is a master page</p>
				<?php } ?>
		    </div>
		</article>
		<?php
		break;
	case "admin.view.page.newpage.form.bottom":
		if(!$isTranslation) { ?>
		<article class="module width_full">
			<header><h3>Add a Translation</h3> <img src="images/openclose.png" onclick="$('#new_page_translation').toggle('slow')" /></header>
		    <div class="module_content" id="new_page_translation">
		    	<?php if($pgid != "") { ?>
		    	<fieldset>
		    		<label style="width:150px">Country</label>
		    		<select name="country" id="country" style="width:400px">
						<option value="">Please choose</option>
		    			<?php
		    			echo Country::activeCountryOptions('',Country::getDefaultCountry()->iso);
						?>
		    		</select>
		    		<div class="clear"></div>
		    		<label style="margin-top:10px"><input type="button" value="Go" class="alt_btn" onclick="addTranslation('<?php echo $pgid; ?>');" /></label>
		    	</fieldset>
		    	<?php } else { ?>
		    	The page needs to be saved before any translations can be added.
		    	<?php } ?>
		    </div>
		</article>
		<?php }
		break;
	case "admin.view.page.newpage.field.content":
		if($isTranslation) { ?>
        	<div class="trans_details dnone">
        	<div class="clear"></div>
        	<div style="padding:5px 10px"><strong><?php echo Country::showDefaultFlag('16px'); ?> Original:</strong>
        		<p><?php echo $orig_page->content; ?></p>
        	</div>
        	</div>
        <?php }
		break;
	case "admin.view.page.newpage.field.meta_title":
		if($isTranslation) { ?>
		<div class="trans_details dnone">
    	<div class="clear"></div>
    	<div style="padding:5px 10px"><strong><?php echo Country::showDefaultFlag('16px'); ?> Original:</strong> <?php echo $orig_page->title; ?></div>
    	</div>
        <?php }
		break;
	case "admin.view.page.newpage.field.slug":
		if($isTranslation) { ?>
		<div class="trans_details dnone">
    	<div class="clear"></div>
    	<div style="padding:5px 10px"><strong><?php echo Country::showDefaultFlag('16px'); ?> Original:</strong> <?php echo $orig_page->slug; ?></div>
    	</div>
        <?php }
		break;
	case "admin.view.page.newpage.field.meta_keywords":
		if($isTranslation) { ?>
		<div class="trans_details dnone">
    	<div class="clear"></div>
    	<div style="padding:5px 10px"><strong><?php echo Country::showDefaultFlag('16px'); ?> Original:</strong> <?php echo $orig_page->meta_keywords; ?></div>
    	</div>
        <?php }
		break;
	case "admin.view.page.newpage.field.meta_description":
		if($isTranslation) { ?>
		<div class="trans_details dnone">
    	<div class="clear"></div>
    	<div style="padding:5px 10px"><strong><?php echo Country::showDefaultFlag('16px'); ?> Original:</strong> <?php echo $orig_page->meta_description; ?></div>
    	</div>
        <?php }
		break;
		
	// PAGE STRUCTURE FOR CHOOSING MODULE TO LOAD
	case "page_structure.module":
		if(!$inAdmin) {
			echo "hello";
			$defCountry = Country::getDefaultCountry();
			echo "gdfd";
			if($page->iso != $defCountry && $page->iso != "") {
				$page_parent = new Page('',$page->iso_parent);
				$module = $page_parent->slug;
			}
		}
		break;
}
?>