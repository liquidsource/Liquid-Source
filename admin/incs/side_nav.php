<section id="secondary_bar">
	<div class="user">
		<p><a href="index.php"><?php echo getUserFullName($mid); ?></a></p>
	</div>
	<div class="breadcrumbs_container">
		<article class="breadcrumbs"><a href="index.php">Website Admin</a> <div class="breadcrumb_divider"></div>
        <a class="current"><?php echo getMetaInfo($module,'title'); ?></a>
        </article>
	</div>
</section>

<aside id="sidebar" class="column">
	<?php include("incs/ad_menu.php"); ?>
	<footer>
		<hr />
		<p><strong>Copyright &copy; <?php echo date("Y"); ?> Liquid Source</strong></p>
	</footer>
</aside>