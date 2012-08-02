<?php
$uids = "";
?>
<article class="module width_full">
<header>
<h3 class="tabs_involved">Edit Companies</h3>
</header>
<div class="tab_container">
    <table class="tablesorter dataTable" cellspacing="0"> 
    <thead> 
        <tr> 
            <th> cmid</th>
            <th> Name</th>
            <th> Address</th>
            <th> Actions</th>
        </tr> 
    </thead> 
    <tbody> 
        <?php
        $arr = getCompanies(array());
		foreach($arr as  $comp) {
			$cmid = $comp->cmid;
			
			echo "<tr onclick=\"rowSelect('$cmid');\" id=\"row_$cmid\">
			 <td><a href=\"?module=newlink&lid=$cmid\">$cmid</a></td>
			 <td>" . $comp->cm_name . "</td>
			 <td>" . $comp->cm_address . "</td>
			 <td>
			 	<a href=\"?module=_nrecruitment/pg_newcompany&cmid=$cmid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>
                <a href=\"scripts/action.php?action=deletecompany&cmid=$cmid&to=_nrecruitment/pg_editcompanies\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Trash\"></a>
			 </td>
			 </tr>";
			$uids .= $cmid . ";";
		}
		?>
    </tbody> 
    </table>
    <p style="clear:both"></p>
    <br />
</div><!-- end of .tab_container -->
</article><!-- end of content manager article -->