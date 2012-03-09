<?php
$llimit = "0";
$hlimit = "20";

if($_GET['llimit'] != "") $llimit = $_GET['llimit'];
if($_GET['hlimit'] != "") $hlimit = $_GET['hlimit'];

if($_GET['orderby'] == "" && $_GET['llimit'] == "") { clearSessionSorts(); }
setOrderBy('pid');
$orderby = $_SESSION['curorderby'];
$orderdir = $_SESSION['orderdir'];
?>
<article class="module width_full">
<header>
<h3 class="tabs_involved">Edit Templates</h3>
</header>
<div class="tab_container">
        <table class="tablesorter" cellspacing="0"> 
        <thead> 
            <tr> 
                <th <?php echo tableSorter('pid'); ?>> pid</th> 
                <th <?php echo tableSorter('p_title'); ?>> Template Name</th> 
                <th <?php echo tableSorter('p_slug'); ?>> Template Slug</th> 
                <th <?php echo tableSorter('p_createdate'); ?>> Created On</th> 
                <th> Actions</th> 
            </tr> 
        </thead> 
        <tbody> 
            <?php
            $arr = ls_m_getTemplateInfo(array("num" => $llimit . "," . $hlimit, "orderby" => $orderby, "orderdir" => $orderdir));
			foreach($arr as $template) {
				$pid = $template->pid;
				
				echo "<tr onclick=\"rowSelect('$pid');\" id=\"row_$pid\">
				 <td><a href=\"?module=newtemplate&pid=$pid\">$pid</a></td>
				 <td>" . $template->p_title . "</td>
				 <td>" . $template->p_slug . "</td>
				 <td>" . $template->p_createdate . "</td>
				 <td>
				 	<a href=\"?module=newtemplate&pid=$pid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>
                    <a href=\"scripts/action.php?action=deletetemplate&pid=$pid&to=edittemplates\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Trash\"></a>
				 </td>
				 </tr>";
				$uids .= $pid . ";";
			}
			?>
        </tbody> 
        </table>
        <div class="paging">
        <?php
	    $pagenum = ($llimit / $hlimit) + 1;
		$arr = ls_m_getTemplateInfo();
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