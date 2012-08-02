<?php
switch ($plugin_code) {
	// ADMIN MENU
	case "admin.view.menu":
		?>
		<h3 id="jobs">Jobs</h3>
		<ul class="toggle" id="sh_jobs">
		    <li class="icn_edit_article"><a href="?module=pg_editjobs">Edit Jobs</a></li>
		    <li class="icn_new_article"><a href="?module=pg_newjob">New Job</a></li>
		    <li class="icn_categories"><a href="?module=pg_jobcategories">Job Categories</a></li>
		</ul>
		<h3 id="companies">Companies</h3>
		<ul class="toggle" id="sh_companies">
		    <li class="icn_edit_article"><a href="?module=pg_editcompanies">Edit Companies</a></li>
		    <li class="icn_new_article"><a href="?module=pg_newcompany">New Company</a></li>
		</ul>
		<hr />
		<?php
		break;
	case "admin.action.category.new":
		$to = "_nrecruitment/pg_jobcategories";
		break;
	case "admin.action.category.delete":
		$to = "_nrecruitment/pg_jobcategories";
		break;
	case "admin.action":
		switch ($action) {
			case "newjob":
				$job = new Job($_GET['jid']);
				$jid = $job->updateJob($_POST);
	        	$to = "_nrecruitment/pg_newjob&jid=$jid";
				break;
			case "deletejob":
				$job = new Job($_GET['jid']);
				$job->deleteJob();
				break;
			case "newcompany":
				$comp = new Company($_GET['cmid']);
				$cmid = $comp->updateCompany($_POST);
	        	$to = "_nrecruitment/pg_newcompany&cmid=$cmid";
				break;
			case "deletecompany":
				$comp = new Company($_GET['cmid']);
				$comp->deleteCompany();
				break;
		}
		break;
}