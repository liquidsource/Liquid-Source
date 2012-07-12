<?php
global $m_type;
$uids = "";
$m_type = "U";
if(isset($_GET['m_type']) && $_GET['m_type'] != "") $m_type = $_GET['m_type'];

?>
<article class="module width_full">
<header><h3 class="tabs_involved">Edit Users</h3>
<ul class="tabs">
    <li <?php if($m_type == "U") { echo "class='active'"; } ?>><a href="?module=editusers&m_type=U">Site Users</a></li>
    <li <?php if($m_type == "A") { echo "class='active'"; } ?>><a href="?module=editusers&m_type=A">Admin</a></li>
</ul>
</header>

<div class="tab_container">
    <table class="tablesorter dataTable" cellspacing="0"> 
    <thead> 
        <tr> 
            <th> mid</th>
            <th> Username</th>
            <th> Created On</th>
            <th> Actions</th>
        </tr> 
    </thead> 
    <tbody>
        <?php
        $arr = getMembers(array("m_type" => $m_type));
		foreach($arr as $member) {
			$mid = $member->mid;
			
			echo "<tr onclick=\"rowSelect('$mid');\" id=\"row_$mid\">
			 <td><a href=\"?module=newusert&mid=$mid\">$mid</a></td>
			 <td>" . $member->m_username . "</td>
			 <td>" . $member->m_createdate . "</td>
			 <td>
			 	<a href=\"?module=newuser&mid=$mid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>
			 	<a href=\"scripts/action.php?action=deleteuser&mid=$mid&to=editusers\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Trash\"></a>
			 </td>
			 </tr>";
			$uids .= $mid . ";";
		}
		?>
    </tbody> 
    </table>
	<p style="clear:both"></p>
    <br />
</div>
</article>