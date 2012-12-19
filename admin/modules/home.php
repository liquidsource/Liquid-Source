<?php
$member = new Member(Member::getMid());
?>
<article class="module width_full">
<header>
<h3 class="tabs_involved">Dashboard</h3>
</header>

<div class="module_content">
	<div style="background-color:#FCFEC1;padding:10px;margin-bottom:20px">
	<h2>Welcome <?php echo $member->m_fname; ?> to your dashboard</h2>
	<p>From here feel free to play around etc.</p>
	</div>
	
    <div style="background-color:#A4D5AD;padding:10px;margin-top:40px">
    <h4>Need Help?</h4>
    <p>Get in contact with us</p>
    </div>
</div>

</article>