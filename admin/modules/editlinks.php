<?php
$llimit = "0";
$hlimit = "20";

if($_GET['llimit'] != "") $llimit = $_GET['llimit'];
if($_GET['hlimit'] != "") $hlimit = $_GET['hlimit'];

if($_GET['orderby'] == "" && $_GET['llimit'] == "") { clearSessionSorts(); }
setOrderBy('lid');
$orderby = $_SESSION['curorderby'];
$orderdir = $_SESSION['orderdir'];
?>
<article class="module width_full">
<header>
<h3 class="tabs_involved">Edit Links</h3>
</header>
<div class="tab_container">
    <table class="tablesorter" cellspacing="0"> 
    <thead> 
        <tr> 
            <th <?php echo tableSorter('lid'); ?>> lid</th>
            <th <?php echo tableSorter('l_text'); ?>> Link Text</th>
            <th <?php echo tableSorter('l_link'); ?>> Link</th>
            <th> Actions</th>
        </tr> 
    </thead> 
    <tbody> 
        <?php
        $arr = ls_m_getLinkInfo(array("num" => $llimit . "," . $hlimit, "orderby" => $orderby, "orderdir" => $orderdir));
		foreach($arr as  $link) {
			$lid = $link->lid;
			
			echo "<tr onclick=\"rowSelect('$lid');\" id=\"row_$lid\">
			 <td><a href=\"?module=newlink&lid=$lid\">$lid</a></td>
			 <td>" . $link->l_text . "</td>
			 <td>" . $link->l_link . "</td>
			 <td>
			 	<a href=\"?module=newlink&lid=$lid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>
                <a href=\"scripts/action.php?action=deletelink&lid=$lid&to=editlinks\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Trash\"></a>
			 </td>
			 </tr>";
			$uids .= $lid . ";";
		}
		?>
    </tbody> 
    </table>
    <div class="paging">
    <?php
    $pagenum = ($llimit / $hlimit) + 1;
	$arr = ls_m_getLinkInfo();
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