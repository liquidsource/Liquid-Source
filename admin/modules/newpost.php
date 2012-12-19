<?php
$p_publisheddate = "";
$pid = "";
if(isset($_GET['pid'])) $pid = $_GET['pid'];

if($pid != "") {
    $post = new Post($pid);
	if($post->posttype == "published") {
		$p_publisheddate = date("d-m-Y H:i",strtotime($post->publisheddate));
	}
} else {
	$post = new Post();
}
?>
<form method="post" action="scripts/action.php?action=newpost&to=newpost&pid=<?php echo $pid; ?>" id="admin_post_frm">
<article class="module width_full">
	<header><h3>Post New Article</h3></header>
    <div class="module_content">
        <fieldset>
            <label>Post Title</label>
            <input type="text" name="p_title" id="p_title" value="<?php echo $post->p_title; ?>" required />
        </fieldset>
        <fieldset>
            <label>Content</label>
            <div class="clear"></div>
            <textarea rows="12" name="p_content" id="p_content"><?php echo $post->p_content; ?></textarea>
        </fieldset>
        <fieldset style="width:48%; float:left; margin-right: 10px">
            <label>Category</label>
            <select style="width:92%;height:150px" id="cid" name="cid[]" multiple>
                <?php
                $arr = getCategories(array());
				foreach($arr as $category) {
					echo showCategoryOption($category,'0',$post->categoryArray());
				}
                ?>
            </select>
        </fieldset>
		<fieldset style="width:48%; float:right;">
            <label>Meta Data</label>
            <div class="clear"></div>
            <?php
            $i=0;
            $meta_data_arr = $post->metaData();
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
            <label style="width:100px">Argument</label><input style="width:250px;" type="text" name="md_arg_<?php echo $i; ?>" id="md_arg_<?php echo $i; ?>" value="" />
            <div class="clear"></div>
            <label style="width:100px">Value</label><input style="width:250px;" type="text" name="md_val_<?php echo $i; ?>" id="md_val_<?php echo $i; ?>" value="" />
        </fieldset>
        
        <div class="clear"></div>
    </div>
    <footer>
        <div class="submit_link">
        	<strong>Schedule Publish At:</strong> &nbsp;
            <input type="text" class="timepicker" id="p_publisheddate" name="p_publisheddate" style="width:150px" value="<?php echo $p_publisheddate; ?>" />
            <input type="button" value="Schedule" class="alt_btn" onclick="ls_admin_saveForm('p','published','admin_post_frm')" />
            &nbsp; &nbsp; &nbsp; or
            <strong style="margin-left:20px">Save Options:</strong> &nbsp; &nbsp;
        	<?php if($post->p_posttype == "") { ?>
        		<input type="button" value="Save as Draft" onclick="ls_admin_saveForm('p','draft','admin_post_frm')" /> &nbsp;
            	<input type="button" value="Publish Now" class="alt_btn" onclick="ls_admin_saveForm('p','published','admin_post_frm')" />
            <?php } ?>
            <?php if($post->p_posttype == "draft") { ?>
        		<input type="button" value="Update Draft" onclick="ls_admin_saveForm('p','draft','admin_post_frm')" /> &nbsp;
            	<input type="button" value="Publish Now" class="alt_btn" onclick="ls_admin_saveForm('p','published','admin_post_frm')" />
            <?php } ?>
            <?php if($post->p_posttype == "published") { ?>
            	<input type="button" value="Update Published" class="alt_btn" onclick="ls_admin_saveForm('p','published','admin_post_frm')" />
            <?php } ?>
            <input type="hidden" name="p_posttype" id="p_posttype" value="<?php echo $post->p_posttype; ?>" />
        </div>
    </footer>
</article>
</form>

<article class="module width_full">
	<header><h3>Post Information</h3></header>
    <div class="module_content">
    	<fieldset>
    		<label style="width:150px">Post ID</label>
		    	<div style="margin-top:6px"><?php echo $pid; ?></div>
		    	<div class="clear small">&nbsp;</div>
	    	<label style="width:150px">Type</label>
		    	<div style="margin-top:6px">Post</div>
		    	<div class="clear small">&nbsp;</div>
	    	<label style="width:150px">State</label>
		    	<div style="margin-top:6px"><?php echo $post->p_posttype; ?></div>
		    	<div class="clear small">&nbsp;</div>
	    	<?php if($post->p_posttype == "published") { ?>
	    	<label style="width:150px">Live on Site</label>
		    	<div style="margin-top:6px">
		    	<?php
		    	if(strtotime($post->p_publisheddate) <= time()) { echo "Live &nbsp; &nbsp; (Since - <i>" . date("D jS M Y @ H:i",strtotime($post->p_publisheddate)) . "</i>)"; }
				else { echo "Scheduled for the future &nbsp; &nbsp; (Live on  - <i>" . date("D jS M Y @ H:i",strtotime($post->p_publisheddate)) . "</i>)"; }
		    	?>
		    	</div>
		    	<div class="clear small">&nbsp;</div>
	    	<?php } ?>
    	</fieldset>
    </div>
</article>
<script type="text/javascript" >
tinyMCE.init({
        mode : "textareas",
        theme : "simple"
});
</script >