<?php
$pid = "";
if(isset($_GET['pid'])) $pid = $_GET['pid'];
if($pid != "") {
    $template = new Template($pid);
} else {
	$template = new Template();
}
?>
<form method="post" action="scripts/action.php?action=newtemplate&to=newtemplate&pid=<?php echo $pid; ?>" id="admin_template_frm">
<article class="module width_full">
    <header><h3>New Email Template</h3></header>
    <div class="module_content">
        <fieldset>
            <label>Template Name</label>
            <input type="text" name="p_title" id="p_title" value="<?php echo $template->p_title; ?>" required />
        </fieldset>
        <fieldset>
            <label>Template Slug</label>
            <input type="text" name="p_slug" id="p_slug" value="<?php echo $template->p_slug; ?>" />
        </fieldset>
        <fieldset>
            <label>Content</label>
            <div class="clear"></div>
            <textarea rows="30" name="p_content" id="p_content"><?php echo $template->p_content; ?></textarea>
        </fieldset>
        <div class="clear"></div>
    </div>
    <footer>
        <div class="submit_link">
            <strong>Save Options:</strong> &nbsp; &nbsp;
        	<?php if($template->p_posttype == "") { ?>
        		<input type="button" value="Save as Draft" onclick="ls_admin_saveForm('p','draft','admin_template_frm')" /> &nbsp;
            	<input type="button" value="Publish" class="alt_btn" onclick="ls_admin_saveForm('p','published','admin_template_frm')" />
            <?php } ?>
            <?php if($template->p_posttype == "draft") { ?>
        		<input type="button" value="Update Draft" onclick="ls_admin_saveForm('p','draft','admin_template_frm')" /> &nbsp;
            	<input type="button" value="Publish" class="alt_btn" onclick="ls_admin_saveForm('p','published','admin_template_frm')" />
            <?php } ?>
            <?php if($template->p_posttype == "published") { ?>
            	<input type="button" value="Update Published" class="alt_btn" onclick="ls_admin_saveForm('p','published','admin_template_frm')" />
            <?php } ?>
            <input type="hidden" name="p_posttype" id="p_posttype" value="<?php echo $template->p_posttype; ?>" />
        </div>
    </footer>
</article>
</form>

<script>
tinyMCE.init({
        mode : "textareas",
        theme : "simple"
});
</script>