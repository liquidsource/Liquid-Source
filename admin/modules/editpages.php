<?php
$llimit = "0";
$hlimit = "20";

if($_GET['llimit'] != "") $llimit = $_GET['llimit'];
if($_GET['hlimit'] != "") $hlimit = $_GET['hlimit'];

if($_GET['orderby'] == "" && $_GET['llimit'] == "") { clearSessionSorts(); }
setOrderBy('pgid');
$orderby = $_SESSION['curorderby'];
$orderdir = $_SESSION['orderdir'];
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
                <th <?php echo tableSorter('pg_title'); ?>> Page Title</th>
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
            $arr = getPages(array("num" => $llimit . "," . $hlimit, "orderby" => $orderby, "orderdir" => $orderdir, "state" => 'all'));
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
				<td>
		 		<a href=\"?module=newpage&pgid=$pgid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>";
                    if($page->isdefault == 0) {
                    	echo "<a href=\"scripts/action.php?action=deletepage&pgid=$pgid&to=editpages\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Trash\"></a>";
					}
				echo "
				</td>
				</tr>";
				$uids .= $pgid . ";";
			}
			?>
		</tbody> 
    </table>
    <div class="paging">
    <?php
    $pagenum = ($llimit / $hlimit) + 1;
	$arr = getPages(array("state" => 'all'));
    $numrows = sizeof($arr);
    if($numrows > $hlimit) {
        $numpages = ceil($numrows / $hlimit);
		
		$maxnumtoshow = $pagenum + 12;
		$dontShowEnd = false;
		if($numpages < $maxnumtoshow) {$maxnumtoshow = $numpages; $dontShowEnd = true; }
		
		$minnumtoshow = $pagenum - 12;
		if($minnumtoshow < 1) { $minnumtoshow = "1";
		} else { echo "<a href=\"?module=$module&llimit=0\" $class>1</a> ... "; }
		
        for($i=$minnumtoshow;$i<=$maxnumtoshow;$i++) {
            $nllimit = ($i-1) * $hlimit;
            $class = "";
            if($llimit == $nllimit) { $class="class=\"chosen\""; }
            echo "<a href=\"?module=$module&llimit=$nllimit\" $class>$i</a> ";
        }
		
		if(!$dontShowEnd) {
            $nllimit = ($numpages-1) * $hlimit;
			echo " ... <a href=\"?module=$module&llimit=$nllimit\" $class>$numpages</a> ";
		}
		
    }
    ?>
    </div>
			
	<div class='search'>
    	<fieldset class='actions'>
    	<p><a href="javascript:;" onclick="selectAll('<?php echo $uids; ?>')">select all</a></p>
    	With selected:
    	<select name="chosen_action" id="chosen_action" class="search_input">
    	</select>
    	<input type="button" onclick="carryOutBulkAction();" value="Go" />
    	</fieldset>
   </div>
</div><!-- end of .tab_container -->
</article><!-- end of content manager article -->