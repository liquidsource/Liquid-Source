<?php
$uids = "";
?>
<article class="module width_full">
<header>
<h3 class="tabs_involved">Edit Jobs</h3>
</header>
<div class="tab_container">
    <table class="tablesorter dataTable" cellspacing="0"> 
    <thead> 
        <tr> 
            <th> jid</th>
            <th> Job ref</th>
            <th> Job title</th>
            <th> Start date</th>
            <th> End date</th>
            <th> Actions</th>
        </tr> 
    </thead> 
    <tbody> 
        <?php
        $arr = getJobs(array('j_startdate' => '2050-00-00 00:00:00', 'j_expirydate' => '0000-00-00 00:00:00'));
		foreach($arr as  $job) {
			$jid = $job->jid;
			
			echo "<tr onclick=\"rowSelect('$jid');\" id=\"row_$jid\">
			 <td><a href=\"?module=newlink&lid=$jid\">$jid</a></td>
			 <td>" . $job->j_ref . "</td>
			 <td>" . $job->j_title . "</td>
			 <td>" . $job->j_startdate . "</td>
			 <td>" . $job->j_enddate . "</td>
			 <td>
			 	<a href=\"?module=_nrecruitment/pg_newjob&jid=$jid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>
                <a href=\"scripts/action.php?action=deletejob&jid=$jid&to=_nrecruitment/pg_editjobs\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Trash\"></a>
			 </td>
			 </tr>";
			$uids .= $jid . ";";
		}
		?>
    </tbody> 
    </table>
    <p style="clear:both"></p>
    <br />
</div><!-- end of .tab_container -->
</article><!-- end of content manager article -->