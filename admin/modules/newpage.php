<?php
$pgid = "";
$disabled = "";
$pg_publisheddate = "";
$pagetype = "";

if(isset($_GET['pgid'])) $pgid = $_GET['pgid'];

/* Plugin option */
$plugin_code = "admin.view.page.newpage.top"; include(INCLUDE_PLUGIN_ROOT . "core.php");

if($pgid != "") {
   	$page = new Page('',$pgid);
	if($page->isdefault) $disabled = " disabled ";
	if($page->pg_posttype == "published") {
		$pg_publisheddate = date("d-m-Y H:i",strtotime($page->pg_publisheddate));
	}
	$pagetype = $page->pg_type;
} else {
	$page = new Page();
}

/* Plugin option */
$plugin_code = "admin.view.page.newpage.top_i"; include(INCLUDE_PLUGIN_ROOT . "core.php");
?>

<form method="post" action="scripts/action.php?action=newpage&to=newpage&pgid=<?php echo $pgid; ?>" id="admin_page_frm" onsubmit="return submitForm('admin_page_frm');">
<input type="hidden" id="pg_type" name="pg_type" value="<?php echo $pagetype; ?>" />

<?php
/* Plugin option */
$plugin_code = "admin.view.page.newpage.form.top"; include(INCLUDE_PLUGIN_ROOT . "core.php");
?>

<article class="module width_full">
    <header><h3>New / Edit Page</h3></header>
    <div class="module_content">
        <fieldset>
            <label>Page Title</label>
            <input type="text" name="pg_meta_title" id="pg_meta_title" value="<?php echo $page->pg_meta_title; ?>" required />
            <?php
            /* Plugin option */
			$plugin_code = "admin.view.page.newpage.field.meta_title"; include(INCLUDE_PLUGIN_ROOT . "core.php");	        
            ?>
        </fieldset>
        <fieldset>
            <label>Page Slug</label>
            <input type="text" name="pg_slug" id="pg_slug" value="<?php echo $page->pg_slug; ?>" <?php echo $disabled; ?> />
            <?php
            /* Plugin option */
			$plugin_code = "admin.view.page.newpage.field.slug"; include(INCLUDE_PLUGIN_ROOT . "core.php");	        
            ?>
        </fieldset>
        <input type="hidden" name="init_slug" id="init_slug" value="<?php echo $page->pg_slug; ?>" />
        
        <fieldset>
            <label>Meta Description</label>
            <input type="text" name="pg_meta_description" id="pg_meta_description" value="<?php echo $page->pg_meta_description; ?>">
            <?php
            /* Plugin option */
			$plugin_code = "admin.view.page.newpage.field.meta_description"; include(INCLUDE_PLUGIN_ROOT . "core.php");	        
            ?>
            <div class="clear" style="padding:1px;">&nbsp;</div>
            <label style='width:300px'>Meta Keywords (comma seperated)</label>
            <input type="text" name="pg_meta_keywords" id="pg_meta_keywords" value="<?php echo $page->pg_meta_keywords; ?>">
            <?php
            /* Plugin option */
			$plugin_code = "admin.view.page.newpage.field.meta_keywords"; include(INCLUDE_PLUGIN_ROOT . "core.php");	        
            ?>
        </fieldset>
        <div class="clear"></div>
        
        <fieldset>
        	<label>Page Type</label>
            <div class="clear" style="padding:1px;font-size:1px;line-height:1px">&nbsp;</div>
            <label style="width:150px">Text</label>
            <span class="pg_type"><input type="radio" name="pg_type" id="pg_type" value="tx" onclick="mtypeSelected('tx');" <?php if($page->pg_type == "tx") echo "checked=\"checked\""; ?> required />&nbsp;</span>
            <div class="clear" style="padding:1px;font-size:1px;line-height:1px">&nbsp;</div>
            <label style="width:150px">Bespoke</label>
            <span class="pg_type"><input type="radio" name="pg_type" id="pg_type" value="bs" onclick="mtypeSelected('bs');" <?php if($page->pg_type == "bs") echo "checked=\"checked\""; ?> required />&nbsp;</span>
        </fieldset>
        
        
        <fieldset id="options_tx" style="display:none">
            <label>Content</label>
            <div class="clear"></div>
            <textarea rows="30" name="pg_content" id="pg_content" style="margin-bottom: 20px;width:95%;height:400px"><?php echo $page->pg_content; ?></textarea>
            <?php
            /* Plugin option */
			$plugin_code = "admin.view.page.newpage.field.content"; include(INCLUDE_PLUGIN_ROOT . "core.php");	        
            ?>
                
            <div class="clear"></div>
            <label>Meta Data</label>
            <div class="clear"></div>
            <?php
            $i=0;
            $meta_data_arr = $page->metaData();
			if(sizeof($meta_data_arr) > 0) {
				foreach($meta_data_arr as $arg => $val) {
				$i++;
				?>
	                <label style="width:100px">Argument <?php echo $i ;?></label><input style="width:350px;" type="text" name="md_arg_<?php echo $i; ?>" id="md_arg_<?php echo $i; ?>" value="<?php echo $arg; ?>" />
	                <div class="clear"></div>
	                <label style="width:100px">Value <?php echo $i ;?></label><input style="width:350px;" type="text" name="md_val_<?php echo $i; ?>" id="md_val_<?php echo $i; ?>" value="<?php echo $val; ?>" />
	                <div class="clear"> <br /> </div>
                <?php
            	}
            }
			$i++;
            ?>
            <div class="clear"> <br /> </div>
            <label>New Meta Data</label>
            <div class="clear"></div>
            <label style="width:100px">Argument</label><input style="width:350px;" type="text" name="md_arg_<?php echo $i; ?>" id="md_arg_<?php echo $i; ?>" value="" />
            <div class="clear"></div>
            <label style="width:100px">Value</label><input style="width:350px;" type="text" name="md_val_<?php echo $i; ?>" id="md_val_<?php echo $i; ?>" value="" />
        </fieldset>
        
        <fieldset id="options_bs" style="display:none">
        	<h4 class="alert_warning" style="margin-top:0px">Bepsoke pages do not have any options. They are hard coded pages within the system.</h4>
        </fieldset>
    </div>
    <footer>
        <div class="submit_link">
        	<strong>Schedule Publish At:</strong> &nbsp;
            <input type="text" class="timepicker" id="pg_publisheddate" name="pg_publisheddate" style="width:150px" value="<?php echo $pg_publisheddate; ?>" />
            <input type="button" value="Schedule" class="alt_btn" onclick="ls_admin_saveForm('pg','published','admin_page_frm')" />
            &nbsp; &nbsp; &nbsp; or
            
        	<strong>Save Options:</strong> &nbsp; &nbsp;
        	<?php if($page->pg_posttype == "") { ?>
        		<input type="button" value="Save as Draft" onclick="ls_admin_saveForm('pg','draft','admin_page_frm')" /> &nbsp;
            	<input type="button" value="Publish" class="alt_btn" onclick="ls_admin_saveForm('pg','published','admin_page_frm')" />
            <?php } ?>
            <?php if($page->pg_posttype == "draft") { ?>
        		<input type="button" value="Update Draft" onclick="ls_admin_saveForm('pg','draft','admin_page_frm')" /> &nbsp;
            	<input type="button" value="Publish" class="alt_btn" onclick="ls_admin_saveForm('pg','published','admin_page_frm')" />
            <?php } ?>
            <?php if($page->pg_posttype == "published") { ?>
            	<input type="button" value="Update Published" class="alt_btn" onclick="ls_admin_saveForm('pg','published','admin_page_frm')" />
            <?php } ?>
            <input type="hidden" name="pg_posttype" id="pg_posttype" value="<?php echo $page->pg_posttype; ?>" />
        </div>
    </footer>
</article>
</form>

<?php
/* Plugin option */
$plugin_code = "admin.view.page.newpage.form.bottom"; include(INCLUDE_PLUGIN_ROOT . "core.php");
?>

<article class="module width_full">
	<header><h3>Page Information</h3></header>
    <div class="module_content">
    	<fieldset>
    		<label style="width:150px">Page ID</label>
		    	<div style="margin-top:6px"><?php echo $pgid; ?></div>
		    	<div class="clear small">&nbsp;</div>
    		<label style="width:150px">Page Slug</label>
		    	<div style="margin-top:6px"><?php echo $page->pg_slug; ?></div>
		    	<div class="clear small">&nbsp;</div>
	    	<label style="width:150px">Type</label>
		    	<div style="margin-top:6px">Page</div>
		    	<div class="clear small">&nbsp;</div>
	    	<label style="width:150px">State</label>
		    	<div style="margin-top:6px"><?php echo $page->pg_posttype; ?></div>
		    	<div class="clear small">&nbsp;</div>
	    	<?php if($page->pg_posttype == "published") { ?>
	    	<label style="width:150px">Live on Site</label>
		    	<div style="margin-top:6px">
		    	<?php
		    	if(strtotime($page->pg_publisheddate) <= time()) { echo "Live &nbsp; &nbsp; (Since - <i>" . date("D jS M Y @ H:i",strtotime($page->pg_publisheddate)) . "</i>)"; }
				else { echo "Scheduled for the future &nbsp; &nbsp; (Live on  - <i>" . date("D jS M Y @ H:i",strtotime($page->pg_publisheddate)) . "</i>)"; }
		    	?>
		    	</div>
		    	<div class="clear small">&nbsp;</div>
	    	<?php } ?>
    	</fieldset>
    </div>
</article>

<script>
tinyMCE.init({
        mode : "textareas",
        theme : "advanced"
});
var oldView = "";
function mtypeSelected(typee) {
	if(oldView != typee) { $('#options_' + oldView).hide('slow'); }
    $('#options_' + typee).show('slow'); 
    oldView = typee;	
}
<?php if($page->pg_type != "") { ?>mtypeSelected('<?php echo strtolower($page->pg_type); ?>');<?php } ?>
</script>