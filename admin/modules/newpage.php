<?php
$pgid = $_GET['pgid'];
if($pgid != "") {
   	$page = new Page('',$pgid);
	if($page->isdefault) $disabled = " disabled ";
} else {
	$page = new Page();
}
?>
<form method="post" action="scripts/action.php?action=newpage&to=newpage&pgid=<?php echo $pgid; ?>" id="admin_page_frm" onsubmit="return submitForm('admin_page_frm');">
<article class="module width_full">
    <header><h3>New / Edit Page</h3></header>
    <div class="module_content">
        <fieldset>
            <label>Page Title</label>
            <input type="text" name="pg_meta_title" id="pg_meta_title" value="<?php echo $page->title; ?>" required />
        </fieldset>
        <fieldset>
            <label>Page Slug</label>
            <input type="text" name="pg_slug" id="pg_slug" value="<?php echo $page->slug; ?>" <?php echo $disabled; ?> />
        </fieldset>
        <input type="hidden" name="init_slug" id="init_slug" value="<?php echo $page->slug; ?>" />
        
        <fieldset>
            <label>Meta Description</label>
            <input type="text" name="pg_meta_description" id="pg_meta_description" value="<?php echo $page->meta_description; ?>">
            <div class="clear" style="padding:1px;">&nbsp;</div>
            <label style='width:300px'>Meta Keywords (comma seperated)</label>
            <input type="text" name="pg_meta_keywords" id="pg_meta_keywords" value="<?php echo $page->meta_keywords; ?>">
        </fieldset>
        <div class="clear"></div>
        
        <fieldset>
        	<label>Page Type</label>
            <div class="clear" style="padding:1px;font-size:1px;line-height:1px">&nbsp;</div>
            <label style="width:150px">Text</label>
            <span class="pg_type"><input type="radio" name="pg_type" id="pg_type" value="tx" onclick="mtypeSelected('tx');" <?php if($page->type == "tx") echo "checked=\"checked\""; ?> required />&nbsp;</span>
            <div class="clear" style="padding:1px;font-size:1px;line-height:1px">&nbsp;</div>
            <label style="width:150px">Bespoke</label>
            <span class="pg_type"><input type="radio" name="pg_type" id="pg_type" value="bs" onclick="mtypeSelected('bs');" <?php if($page->type == "bs") echo "checked=\"checked\""; ?> required />&nbsp;</span>
        </fieldset>
        
        
        <fieldset id="options_tx" style="display:none">
            <label>Content</label>
            <div class="clear"></div>
            <textarea rows="30" name="pg_content" id="pg_content" style="margin-bottom: 20px;width:95%;height:400px"><?php echo $page->content; ?></textarea>
                
                
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
	                <label style="width:100px">Argument <?php echo $i ;?></label><input style="width:350px;" type="text" name="md_arg_<?php echo $i; ?>" id="md_arg_<?php echo $i; ?>" value="<?php echo $val['arg']; ?>" />
	                <div class="clear"></div>
	                <label style="width:100px">Value <?php echo $i ;?></label><input style="width:350px;" type="text" name="md_val_<?php echo $i; ?>" id="md_val_<?php echo $i; ?>" value="<?php echo $val['val']; ?>" />
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
        	<h4 class="alert_warning" style="margin-top:0px">Bepsoke pages do not have any options. They are hard coded pages within the system. Speak to the developer to alter the workings of this page.</h4>
        </fieldset>
    </div>
    <footer>
        <div class="submit_link">
        	<strong>Save Options:</strong> &nbsp; &nbsp;
        	<?php if($page->posttype == "") { ?>
        		<input type="button" value="Save as Draft" onclick="ls_admin_saveForm('pg','draft','admin_page_frm')" /> &nbsp;
            	<input type="button" value="Publish" class="alt_btn" onclick="ls_admin_saveForm('pg','published','admin_page_frm')" />
            <?php } ?>
            <?php if($page->posttype == "draft") { ?>
        		<input type="button" value="Update Draft" onclick="ls_admin_saveForm('pg','draft','admin_page_frm')" /> &nbsp;
            	<input type="button" value="Publish" class="alt_btn" onclick="ls_admin_saveForm('pg','published','admin_page_frm')" />
            <?php } ?>
            <?php if($page->posttype == "published") { ?>
            	<input type="button" value="Update Published" class="alt_btn" onclick="ls_admin_saveForm('pg','published','admin_page_frm')" />
            <?php } ?>
            <input type="hidden" name="pg_posttype" id="pg_posttype" value="<?php echo $page->posttype; ?>" />
        </div>
    </footer>
</article>
</form>

<script>
tinyMCE.init({
        mode : "textareas",
        theme : "advanced"
});
function mtypeSelected(typee) {
    if(typee == "tx") {
        $('#options_bs').hide('slow');
        $('#options_tx').show('slow');
    } else {
        $('#options_bs').show('slow');
        $('#options_tx').hide('slow');
        
    }
}
<?php if($page->type != "") { ?>mtypeSelected('<?php echo strtolower($page->type); ?>');<?php } ?>
</script>