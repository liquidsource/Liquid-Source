<?php
$jid = "";
if(isset($_GET['jid'])) $jid = $_GET['jid'];
if($jid != "") {
    $job = new Job($jid);
} else {
	$job = new Job();
}
?>
<form method="post" action="scripts/action.php?action=newjob&to=_nrecruitment/pg_newjob&jid=<?php echo $jid; ?>" id="admin_job_frm" onsubmit="return submitForm('admin_job_form');">
<article class="module width_full">
    <header><h3>New Job</h3></header>
    <div class="module_content">
        <fieldset>
            <label>Job Title</label>
            <input type="text" name="j_title" id="j_title" value="<?php echo $job->j_title; ?>" />
        </fieldset>
        <fieldset>
            <label>Job Ref</label>
            <input type="text" name="j_ref" id="j_ref" value="<?php echo $job->j_ref; ?>" required />
        </fieldset>
        <fieldset>
            <label>Job Details</label>
            <p class="clear:both:">&nbsp;</p>
           <textarea name="j_details" id="j_details"><?php echo $job->j_details; ?></textarea>
        </fieldset>
        <fieldset style="width:48%; float:left; margin-right: 3%;">
            <label>Category</label>
            <select style="width:92%;height:130px" id="cid" name="cid[]" multiple>
            <?php
            $arr = getCategories(array('type' => 'job'));
			foreach($arr as $category) {
				echo showCategoryOption($category,'0',$job->categoryArray());
			}
            ?>
            </select>
        </fieldset>
        <fieldset style="width:48%; float:right;">
            <label>Start date</label>
            <input type="text" name="j_startdate" id="j_startdate" class="datepicker" value="<?php if($job->j_startdate != "") { echo date("d-m-Y",strtotime($job->j_startdate)); } ?>" required />
            <label>Expiry date</label>
            <input type="text" name="j_expirydate" id="j_expirydate" class="datepicker" value="<?php if($job->j_expirydate != "") echo date("d-m-Y",strtotime($job->j_expirydate)); ?>" required />
        </fieldset>
        <div class="clear"></div>
        
        <?php
        $arr = Job::getJobPropertyArray();
        foreach($arr as $arg => $inner_arr) {
        ?>
			<h3><?php echo $arg; ?></h3>
			<fieldset>
			<?php foreach($inner_arr as $j_arr) { ?>
			<label><?php echo $j_arr['jp_name']; ?></label>
			<?php echo Job::showJobInput($j_arr['jp_inputname'],NULL,$job->$j_arr['jp_inputname']); ?>
			<?php } ?>
			</fieldset>
        <?php
		}
	    ?>
        
    </div>
    <footer>
        <div class="submit_link">
            <input type="submit" value="Publish" class="alt_btn">
        </div>
    </footer>
</article>
</form>
<script type="text/javascript" >
tinyMCE.init({
        mode : "textareas",
        theme : "advanced"
});
</script >