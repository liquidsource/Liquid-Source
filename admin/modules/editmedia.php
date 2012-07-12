<?php
$uids = "";
?>
<article class="module width_full" id="media_listing">
<header><h3 class="tabs_involved">Edit Media</h3></header>
<div class="tab_container">
    <table class="tablesorter dataTable" cellspacing="0"> 
    <thead> 
        <tr> 
            <th> mdid</th> 
            <th></th> 
            <th> Media Title</th> 
            <th> Media Category</th> 
            <th> Created On</th> 
            <th> Actions</th> 
        </tr> 
    </thead> 
    <tbody> 
        <?php
        $arr = getMedia(array());
		foreach($arr as $media) {
			$mdid = $media->mdid;
			
			echo "<tr onclick=\"rowSelect('$mdid');\" id=\"row_$mdid\">
			 <td><a href=\"?module=newmedia&pgid=$mdid\">$mdid</a></td>
			 <td>" . $media->getLocation(array('echo' => true, 'lvl' => '../')) . "</td>
			 <td>" . $media->md_title . "</td>
			 <td>" . $media->categoryNames() . "</td>
			 <td>" . $media->md_dateuploaded . "</td>
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
	<p style="clear:both"></p>
    <br />
    
</div><!-- end of .tab_container -->
</article><!-- end of content manager article -->