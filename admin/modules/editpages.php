<?php
$viewingTrash = false;
if(isset($_GET['active']) && $_GET['active'] == "0") {
	$viewingTrash = true;
	$x_arr = array('active' => '0');
	$active = "0";
}
?>
<article class="module width_full">
<header>
<h3 class="tabs_involved">Edit Pages</h3>
</header>
<div class="tab_container">
    <table class="tablesorter dataTable" cellspacing="0"> 
    <thead> 
        <tr> 
            <th> pgid</th>
            <th> Page Title</th>
            <th> Page State</th>
            <th> Page Type</th> 
            <th> Page Slug</th>
            <th> Meta Description</th>
            <th class='no_sort'> Created On</th>
            <?php
            /* Plugin option */
			$plugin_code = "admin.view.page.editpages.table_headers"; include(INCLUDE_PLUGIN_ROOT . "core.php");
            ?>
            <th> Actions</th>
        </tr> 
    </thead> 
    <tbody> 
        <?php
        $uids = "";
        $pg_arr = array("state" => 'all');
		if(!empty($x_arr)) { $pg_arr = array_merge($pg_arr,$x_arr); }
		
		/* Plugin option */
		$plugin_code = "admin.view.page.editpages.plugin_arr"; include(INCLUDE_PLUGIN_ROOT . "core.php");
		if(!empty($plugin_arr)) { $pg_arr = array_merge($pg_arr,$plugin_arr); }
		
        $arr = getPages($pg_arr);
		foreach($arr as $page) {
			$pgid = $page->pgid;
			$posttype = $page->pg_posttype;
			
			echo "<tr onclick=\"rowSelect('$pgid');\" id=\"row_$pgid\" class=\"posttype_$posttype\">
			<td><a href=\"?module=newpage&pgid=$pgid\">$pgid</a></td>
			<td><strong>" . $page->pg_meta_title . "</strong></td>
			<td>" . $posttype . "</td>
			<td>" . $page->pg_type_eng . "</td>
			<td>" . $page->pg_slug . "</td>
			<td>" . $page->pg_meta_description . " ... </td>
			<td>" . $page->pg_createdate . "</td>
			";
			
            /* Plugin option */
			$plugin_code = "admin.view.page.editpages.table_columns"; include(INCLUDE_PLUGIN_ROOT . "core.php");
			
			if($viewingTrash) {
				echo "
				<td>
		 		<a href=\"scripts/action.php?action=restorepage&pgid=$pgid&to=editpages\"><input type=\"image\" src=\"images/icn_jump_back.png\" title=\"Restore\"></a>
                <a href=\"scripts/action.php?action=removepage&pgid=$pgid&to=editpages\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Remove\"></a>
				</td>
				</tr>";
			} else {
				echo "
				<td>
		 		<a href=\"?module=newpage&pgid=$pgid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>";
                if($page->isdefault == 0) {
                	echo "<a href=\"scripts/action.php?action=deletepage&pgid=$pgid&to=editpages\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Trash\"></a>";
				}
				echo "
				</td>
				</tr>";
			}
			$uids .= $pgid . ";";
		}
		?>
	</tbody> 
    </table>
	<p style="clear:both"></p>
	
	<?php if(!$viewingTrash) { ?>
	<div class='search'>
    	<fieldset class='actions'>
    	<p><a href="javascript:;" onclick="selectAll('<?php echo $uids; ?>')">select all</a></p>
    	With selected:
    	<select name="chosen_action" id="chosen_action" class="search_input">
    	</select>
    	<input type="button" onclick="carryOutBulkAction();" value="Go" />
    	</fieldset>
   </div>
   <?php } ?>
</div><!-- end of .tab_container -->
</article><!-- end of content manager article -->

<!-- View / not view trash box -->
<article class="module width_full">
<div class="tab_container">
<?php
$nonwords = "";
$xurl = "";
if($viewingTrash) { $n = Page::numberNonTrashItems(); $nonwords = "non-"; } else { $n = Page::numberTrashItems(); $xurl = "&active=0"; }
?>
<ul><li><a href="?module=editpages<?php echo $xurl; ?>">View <?php echo $nonwords; ?>trash (<?php echo $n; ?> items)</a></li></ul>
</div>
</article>
<!-- end of view / not view trash box -->