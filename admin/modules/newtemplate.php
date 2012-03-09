<?php
$pid = $_GET['pid'];
if($pid != "") {
    $template = new Template($pid);
}
?>
<form method="post" action="scripts/action.php?action=newtemplate&to=newtemplate&pid=<?php echo $pid; ?>" id="admin_template_frm" onsubmit="return submitForm('admin_template_frm');">
<article class="module width_full">
    <header><h3>New Email Template</h3></header>
    <div class="module_content">
        <fieldset>
            <label>Template Name</label>
            <input type="text" name="p_title" id="p_title" value="<?php echo $template->p_title; ?>" required />
        </fieldset>
        <fieldset>
            <label>Template Slug</label>
            <input type="text" name="p_slug" id="p_slug" <?php echo $disabled; ?> value="<?php echo $template->p_slug; ?>" />
        </fieldset>
        <fieldset>
            <label>Content</label>
            <textarea rows="30" name="p_content" id="p_content"><?php echo $template->p_content; ?></textarea>
        </fieldset>
        <div class="clear"></div>
    </div>
    <footer>
        <div class="submit_link">
            <input type="submit" value="Save" class="alt_btn">
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