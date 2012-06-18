<?php
$llimit = "0";
$hlimit = "20";

if($_GET['llimit'] != "") $llimit = $_GET['llimit'];
if($_GET['hlimit'] != "") $hlimit = $_GET['hlimit'];

if($_GET['orderby'] == "" && $_GET['llimit'] == "") { clearSessionSorts(); }
setOrderBy('pgid');
$orderby = $_SESSION['curorderby'];
$orderdir = $_SESSION['orderdir'];

if($_GET['active'] == "0") {
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
    <table class="tablesorter" cellspacing="0"> 
    <thead> 
        <tr> 
            <th <?php echo tableSorter('pgid'); ?>> pgid</th>
            <th <?php echo tableSorter('pg_meta_title'); ?>> Page Title</th>
            <th <?php echo tableSorter('pg_posttype'); ?>> Page State</th>
            <th <?php echo tableSorter('pg_type'); ?>> Page Type</th> 
            <th <?php echo tableSorter('pg_slug'); ?>> Page Slug</th>
            <th <?php echo tableSorter('pg_meta_description'); ?>> Meta Description</th>
            <th <?php echo tableSorter('pg_createdate'); ?>> Created On</th>
            <th> Actions</th>
        </tr> 
    </thead> 
    <tbody> 
        <?php
        $pg_arr = array("num" => $llimit . "," . $hlimit, "orderby" => $orderby, "orderdir" => $orderdir, "state" => 'all');
		if(!empty($x_arr)) { $pg_arr = array_merge($pg_arr,$x_arr); }
        $arr = getPages($pg_arr);
		foreach($arr as $page) {
			$pgid = $page->id;
			$posttype = $page->posttype;
			
			echo "<tr onclick=\"rowSelect('$pgid');\" id=\"row_$pgid\" class=\"posttype_$posttype\">
			<td><a href=\"?module=newpage&pgid=$pgid\">$pgid</a></td>
			<td><strong>" . $page->title . "</strong></td>
			<td>" . $posttype . "</td>
			<td>" . $page->type_eng . "</td>
			<td>" . $page->slug . "</td>
			<td>" . $page->meta_description . " ... </td>
			<td>" . $page->createdate . "</td>
			";
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
    <div class="paging">
    <?php
    $pagenum = ($llimit / $hlimit) + 1;
	$pg_count_arr = array("state" => 'all');
	if(!empty($x_arr)) { $pg_count_arr = array_merge($pg_count_arr,$x_arr); }
	$arr = getPages($pg_count_arr);
    $numrows = sizeof($arr);
    if($numrows > $hlimit) {
        $numpages = ceil($numrows / $hlimit);
		
		$maxnumtoshow = $pagenum + 12;
		$dontShowEnd = false;
		if($numpages < $maxnumtoshow) {$maxnumtoshow = $numpages; $dontShowEnd = true; }
		
		$minnumtoshow = $pagenum - 12;
		if($minnumtoshow < 1) { $minnumtoshow = "1";
		} else { echo "<a href=\"?module=$module&llimit=0&active=$active\" $class>1</a> ... "; }
		
        for($i=$minnumtoshow;$i<=$maxnumtoshow;$i++) {
            $nllimit = ($i-1) * $hlimit;
            $class = "";
            if($llimit == $nllimit) { $class="class=\"chosen\""; }
            echo "<a href=\"?module=$module&llimit=$nllimit&active=$active\" $class>$i</a> ";
        }
		
		if(!$dontShowEnd) {
            $nllimit = ($numpages-1) * $hlimit;
			echo " ... <a href=\"?module=$module&llimit=$nllimit&active=$active\" $class>$numpages</a> ";
		}
		
    }
    ?>
    </div>
		
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

<article class="module width_full">
<div class="tab_container">
<?php
if($viewingTrash) { $n = Page::numberNonTrashItems(); $nonwords = "non-"; } else { $n = Page::numberTrashItems(); $xurl = "&active=0"; }
?>
<ul><li><a href="?module=editpages<?php echo $xurl; ?>">View <?php echo $nonwords; ?>trash (<?php echo $n; ?> items)</a></li></ul>
</div>
</article>
