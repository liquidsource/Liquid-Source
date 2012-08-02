<?php
$cmid = "";
if(isset($_GET['cmid'])) $cmid = $_GET['cmid'];
if($cmid != "") {
    $comp = new Company($cmid);
} else {
	$comp = new Company();
}
?>
<form method="post" action="scripts/action.php?action=newcompany&to=pg_newcompany&cmid=<?php echo $cmid; ?>" id="admin_company_frm" onsubmit="return submitForm('admin_company_frm');">
<article class="module width_full">
    <header><h3>New Company</h3></header>
    <div class="module_content">
        <fieldset>
            <label>Company Name</label>
            <input type="text" name="cm_name" id="cm_name" value="<?php echo $comp->cm_name; ?>" />
        </fieldset>
        <fieldset>
            <label>Company Address</label>
            <input type="text" name="cm_address" id="cm_address" value="<?php echo $comp->cm_address; ?>" />
        </fieldset>
        <fieldset>
            <label>Company Tel</label>
            <input type="text" name="cm_tel" id="cm_tel" value="<?php echo $comp->cm_tel; ?>" />
        </fieldset>
        <fieldset>
            <label>Company Website</label>
            <input type="text" name="cm_website" id="cm_website" value="<?php echo $comp->cm_website; ?>" />
        </fieldset>
        <fieldset>
            <label>Company Email</label>
            <input type="text" name="cm_email" id="cm_email" value="<?php echo $comp->cm_email; ?>" />
        </fieldset>
        <fieldset>
            <label>Company Details</label>
           <textarea name="cm_description" id="cm_description"><?php echo $comp->cm_description; ?></textarea>
        </fieldset>
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
        theme : "simple"
});
</script >