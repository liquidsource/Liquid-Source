<?php
$lid = $_GET['lid'];
if($lid != "") {
    $link = new Link($lid);
} else {
	$link = new Link();
}
?>
<form method="post" action="scripts/action.php?action=newlink&to=newlink&lid=<?php echo $lid; ?>" id="admin_link_frm" onsubmit="return submitForm('admin_link_frm');">
<article class="module width_full">
    <header><h3>New Link</h3></header>
    <div class="module_content">
        <fieldset>
            <label>Link Text</label>
            <input type="text" name="l_text" id="l_text" value="<?php echo $link->l_text; ?>" />
        </fieldset>
        <fieldset>
            <label>Http Link</label>
            <input type="text" name="l_link" id="l_link" value="<?php echo $link->l_link; ?>" required />
        </fieldset>
        <fieldset style="width:48%; float:left; margin-right: 3%;">
            <label>Category</label>
            <select style="width:92%;" id="cid" name="cid">
            <?php
            $arr = ls_m_getCategoryInfo(array('type' => 'link'));
			foreach($arr as $category) {
				echo showCategoryOption($category,'0',$link->getCid(),'link');
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
            $meta_data_arr = $link->getMetaDataArray();
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
            <input type="submit" value="Publish" class="alt_btn">
        </div>
    </footer>
</article>
</form>