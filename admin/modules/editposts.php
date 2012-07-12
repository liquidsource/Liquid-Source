<?php
$uids = "";
?>
<article class="module width_full">
<header>
<h3 class="tabs_involved">Edit Posts</h3>
</header>
<div class="tab_container">
        <table class="tablesorter dataTable" cellspacing="0"> 
        <thead> 
            <tr> 
                <th> pid</th> 
                <th> Post Title</th>
                <th> Post State</th>
                <th> Post State</th>
                <th> Category</th> 
                <th> Created On</th> 
                <th> Actions</th> 
            </tr> 
        </thead> 
        <tbody> 
            <?php
            $arr = getPosts(array("state" => 'all'));
			foreach($arr as $post) {
				$pid = $post->pid;
				$posttype = $post->p_posttype;
				
				echo "<tr onclick=\"rowSelect('$pid');\" id=\"row_$pid\" class=\"posttype_$posttype\">
				 <td><a href=\"?module=newpost&pid=$pid\">$pid</a></td>
				 <td><strong>" . $post->p_title . "</strong></td>
				 <td>" . $post->p_posttype . "</td>
				 <td>" . $posttype . "</td>
				 <td>" . $post->categoryNames() . "</td>
				 <td>" . $post->p_createdate . "</td>
				 <td>
				 	<a href=\"?module=newpost&pid=$pid\"><input type=\"image\" src=\"images/icn_edit.png\" title=\"Edit\"></a>
                    <a href=\"scripts/action.php?action=deletepost&pid=$pid&to=editposts\"><input type=\"image\" src=\"images/icn_trash.png\" title=\"Trash\"></a>
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