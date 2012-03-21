<?php
$pid = $_GET['pid'];
if($pid != "") {
    $post = new Post('',$pid);
} else {
	$post = new Post();
}
?>
<form method="post" action="scripts/action.php?action=newpost&to=newpost&pid=<?php echo $pid; ?>" id="admin_post_frm" onsubmit="return submitForm('admin_post_frm');">
<article class="module width_full">
	<header><h3>Post New Article</h3></header>
    <div class="module_content">
        <fieldset>
            <label>Post Title</label>
            <input type="text" name="p_title" id="p_title" value="<?php echo $post->title; ?>" required />
        </fieldset>
        <fieldset>
            <label>Content</label>
            <textarea rows="12" name="p_content" id="p_content"><?php echo $post->content; ?></textarea>
        </fieldset>
        <fieldset style="width:48%; float:left; margin-right: 3%;">
            <label>Category</label>
            <select style="width:92%;height:150px" id="cid" name="cid[]" multiple>
                <?php
                $arr = getCategories(array());
				foreach($arr as $category) {
					echo showCategoryOption($category,'0',$post->categories());
				}
                ?>
            </select>
        </fieldset>
        <div class="clear"></div>
        
        <fieldset>
            <label>Meta Data</label>
            <div class="clear"></div>
            <?php
            $i=0;
            $meta_data_arr = $post->metaData();
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
        
        <div class="clear"></div>
    </div>
    <footer>
        <div class="submit_link">
            <strong>Save Options:</strong> &nbsp; &nbsp;
        	<?php if($post->posttype == "") { ?>
        		<input type="button" value="Save as Draft" onclick="ls_admin_saveForm('p','draft','admin_post_frm')" /> &nbsp;
            	<input type="button" value="Publish" class="alt_btn" onclick="ls_admin_saveForm('p','published','admin_post_frm')" />
            <?php } ?>
            <?php if($post->posttype == "draft") { ?>
        		<input type="button" value="Update Draft" onclick="ls_admin_saveForm('p,'draft','admin_post_frm')" /> &nbsp;
            	<input type="button" value="Publish" class="alt_btn" onclick="ls_admin_saveForm('p','published','admin_post_frm')" />
            <?php } ?>
            <?php if($post->posttype == "published") { ?>
            	<input type="button" value="Update Published" class="alt_btn" onclick="ls_admin_saveForm('p','published','admin_post_frm')" />
            <?php } ?>
            <input type="hidden" name="p_posttype" id="p_posttype" value="<?php echo $post->posttype; ?>" />
        </div>
    </footer>
</article>
</form>
<script type="text/javascript" >
tinyMCE.init({
        mode : "textareas",
        theme : "simple"
});
</script >