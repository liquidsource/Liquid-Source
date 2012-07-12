<?php
$uids = "";
?>
<article class="module width_full">
<header>
<h3 class="tabs_involved">Edit Templates</h3>
</header>
<div class="tab_container">
    <table class="tablesorter dataTable" cellspacing="0"> 
    <thead> 
        <tr> 
            <th> pid</th> 
            <th> Template Name</th> 
            <th> Template Slug</th> 
            <th> Created On</th> 
            <th> Actions</th> 
        </tr> 
    </thead> 
    <tbody> 
        <?php
        $arr = getTemplates(array("state" => 'all'));
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
	<p style="clear:both"></p>
    <br />
    
    
</div><!-- end of .tab_container -->
</article><!-- end of content manager article -->