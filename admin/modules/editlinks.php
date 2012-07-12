<?php
$orderby = "lid";
$uids = "";
?>
<article class="module width_full">
<header>
<h3 class="tabs_involved">Edit Links</h3>
</header>
<div class="tab_container">
    <table class="tablesorter dataTable" cellspacing="0"> 
    <thead> 
        <tr> 
            <th> lid</th>
            <th> Link Text</th>
            <th> Link Target</th>
            <th> Category</th>
            <th> Actions</th>
        </tr> 
    </thead> 
    <tbody> 
        <?php
        $arr = getLinks(array("orderby" => $orderby));
		foreach($arr as  $link) {
			$lid = $link->lid;
			
			echo "<tr onclick=\"rowSelect('$lid');\" id=\"row_$lid\">
			 <td><a href=\"?module=newlink&lid=$lid\">$lid</a></td>
			 <td>" . $link->l_text . "</td>
			 <td>" . $link->l_link . "</td>
			 <td>" . $link->categoryNames() . "</td>
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
    <p style="clear:both"></p>
    <br />
</div><!-- end of .tab_container -->
</article><!-- end of content manager article -->