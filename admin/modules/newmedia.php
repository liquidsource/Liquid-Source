<?php
$mdid = $_GET['mdid'];
if($mdid != "") {
    $media = new Media($mdid);
} else {
	$media = new Media();
}
?>
<form method="post" id="media_frm" action="scripts/action.php?action=newmedia&to=newmedia&mdid=<?php echo $mdid; ?>" enctype="multipart/form-data" onSubmit="return submitForm('admin_media_frm');">
<article class="module width_full">
    <header><h3>New Media</h3></header>
    <div class="module_content">
        <fieldset>
	        <label>Media:</label>
	        <?php if($mdid != "") { ?><?php echo $media->getLocation(array('echo' => true, 'lvl' => '../', 'style' => 'height:100px')); ?><?php } ?>
	        <div class="clear" style="padding:1px;">&nbsp;</div>
	        <label>Media Location:</label>
	        <span style="font-family:courier"><?php echo $media->getLocation(); ?></span>
	        <div class="clear" style="padding:1px;">&nbsp;</div>
	        <label>New / Update Media:</label>
	        <input type="file" name="newmedia" id="newmedia" />
	        <input type="hidden" name="cur_file" id="cur_file" value="<?php echo $media->getLocation(); ?>" />
        </fieldset>
        <fieldset>
            <label>Media Title</label>
            <input type="text" name="md_title" id="md_title" value="<?php echo $media->md_title; ?>">
        </fieldset>
        <fieldset>
            <label>Media Alt Text</label>
            <input type="text" name="md_alttext" id="md_alttext" value="<?php echo $media->md_alttext; ?>">
        </fieldset>
        <fieldset>
            <label>Media Text</label>
            <textarea name="md_text" id="md_text"><?php echo $media->md_text; ?></textarea>
        </fieldset>
        <fieldset style="width:48%; float:left; margin-right: 3%;">
            <label>Category</label>
            <select style="width:92%;height: 130px;" id="cid" name="cid[]" multiple>
            	<?php
	            $arr = ls_m_getCategoryInfo(array('type' => 'media'));
				foreach($arr as $category) {
					echo showCategoryOption($category,'0',$media->getCid(),'media');
				}
	            ?>
            </select>
        </fieldset>
        <div class="clear"></div>
    </div>
    <footer>
        <div class="submit_link">
            <input type="submit" value="Add / Update" class="alt_btn">
        </div>
    </footer>
</article>
</form>