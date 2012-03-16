<?php
$llimit = "0";
$hlimit = "20";

if($_GET['llimit'] != "") $llimit = $_GET['llimit'];
if($_GET['hlimit'] != "") $hlimit = $_GET['hlimit'];

if($_GET['orderby'] == "" && $_GET['llimit'] == "") { clearSessionSorts(); }
setOrderBy('mdid');
$orderby = $_SESSION['curorderby'];
$orderdir = $_SESSION['orderdir'];
?>
<article class="module width_full" id="media_listing">
<header><h3 class="tabs_involved">Edit Media</h3></header>
<div class="tab_container">
    <table class="tablesorter" cellspacing="0"> 
    <thead> 
        <tr> 
            <th <?php echo tableSorter('mdid'); ?>> mdid</th> 
            <th></th> 
            <th <?php echo tableSorter('md_title'); ?>> Media Title</th> 
            <th <?php echo tableSorter(''); ?>> Media Category</th> 
            <th <?php echo tableSorter('md_dateuploaded'); ?>> Created On</th> 
            <th> Actions</th> 
        </tr> 
    </thead> 
    <tbody> 
        <?php
        $arr = getMedia(array("num" => $llimit . "," . $hlimit, "orderby" => $orderby, "orderdir" => $orderdir));
		foreach($arr as $media) {
			$mdid = $media->id;
			
			echo "<tr onclick=\"rowSelect('$mdid');\" id=\"row_$mdid\">
			 <td><a href=\"?module=newmedia&pgid=$mdid\">$mdid</a></td>
			 <td>" . $media->getLocation(array('echo' => true, 'lvl' => '../')) . "</td>
			 <td>" . $media->title . "</td>
			 <td>" . $media->categoryNames() . "</td>
			 <td>" . $media->dateuploaded . "</td>
			 <td>
			 	<a href=\"?module=newmedia&mdid=$mdid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>
                <a href=\"scripts/action.php?action=deletemedia&mdid=$mdid&to=editmedia\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Trash\"></a>
			 </td>
			 </tr>";
			$uids .= $mdid . ";";
		}
		?>
    </tbody> 
    </table>
    
    <div class="paging">
    <?php
    $pagenum = ($llimit / $hlimit) + 1;
	$arr = ls_m_getMediaInfo();
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
    
    
</div><!-- end of .tab_container -->
</article><!-- end of content manager article -->