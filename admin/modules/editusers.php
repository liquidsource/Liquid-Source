<?php
global $m_type;
$llimit = "0";
$hlimit = "20";
$m_type = "U";

if($_GET['llimit'] != "") $llimit = $_GET['llimit'];
if($_GET['hlimit'] != "") $hlimit = $_GET['hlimit'];
if($_GET['m_type'] != "") $m_type = $_GET['m_type'];

if($_GET['orderby'] == "" && $_GET['llimit'] == "" && $_GET['m_type'] == "") { clearSessionSorts(); }
setOrderBy('mid');
$orderby = $_SESSION['curorderby'];
$orderdir = $_SESSION['orderdir'];
?>
<article class="module width_full">
<header><h3 class="tabs_involved">Edit Users</h3>
<ul class="tabs">
    <li <?php if($m_type == "U") { echo "class='active'"; } ?>><a href="?module=editusers&m_type=U">Site Users</a></li>
    <li <?php if($m_type == "A") { echo "class='active'"; } ?>><a href="?module=editusers&m_type=A">Admin</a></li>
</ul>
</header>

<div class="tab_container">
    <table class="tablesorter" cellspacing="0"> 
    <thead> 
        <tr> 
            <th <?php echo tableSorter('mid'); ?>> mid</th> 
            <th <?php echo tableSorter('m_username'); ?>> Username</th> 
            <th <?php echo tableSorter('m_email'); ?>> Email</th> 
            <th <?php echo tableSorter('m_createdate'); ?>> Created On</th> 
            <th> Actions</th> 
        </tr> 
    </thead> 
    <tbody>
        <?php
        $arr = getMembers(array("num" => $llimit . "," . $hlimit, "orderby" => $orderby, "orderdir" => $orderdir, "m_type" => $m_type));
		foreach($arr as $member) {
			$mid = $member->id;
			
			echo "<tr onclick=\"rowSelect('$mid');\" id=\"row_$mid\">
			 <td><a href=\"?module=newusert&mid=$mid\">$mid</a></td>
			 <td>" . $member->username . "</td>
			 <td>" . $member->email . "</td>
			 <td>" . $member->createdate . "</td>
			 <td>
			 	<a href=\"?module=newuser&mid=$mid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>
			 	";
			if($member->protected == '0') {
            echo "<a href=\"scripts/action.php?action=deleteuser&mid=$mid&to=editusers\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Trash\"></a>";
			}
			echo "
			 </td>
			 </tr>";
			$uids .= $mid . ";";
		}
		?>
    </tbody> 
    </table>
    <div class="paging">
        <?php
	    $pagenum = ($llimit / $hlimit) + 1;
		$arr = ls_m_getMemberInfo();
	    $numrows = sizeof($arr);
	    if($numrows > $hlimit) {
	        $numpages = ceil($numrows / $hlimit);
			
			$maxnumtoshow = $pagenum + 12;
			$dontShowEnd = false;
			if($numpages < $maxnumtoshow) {$maxnumtoshow = $numpages; $dontShowEnd = true; }
			
			$minnumtoshow = $pagenum - 12;
			if($minnumtoshow < 1) { $minnumtoshow = "1";
			} else { echo "<a href=\"?module=$module&llimit=0&m_type=$m_type\" $class>1</a> ... "; }
			
	        for($i=$minnumtoshow;$i<=$maxnumtoshow;$i++) {
	            $nllimit = ($i-1) * $hlimit;
	            $class = "";
	            if($llimit == $nllimit) { $class="class=\"chosen\""; }
	            echo "<a href=\"?module=$module&llimit=$nllimit&m_type=$m_type\" $class>$i</a> ";
	        }
			
			if(!$dontShowEnd) {
	            $nllimit = ($numpages-1) * $hlimit;
				echo " ... <a href=\"?module=$module&llimit=$nllimit&m_type=$m_type\" $class>$numpages</a> ";
			}
			
	    }
	    ?>
    </div>
</div>
</article>