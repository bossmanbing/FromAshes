<div id='user-control'>
	<ul>
<?php
	if (!isset($_SESSION['online'])){
		
		echo "<li><a href='/page/create/'>New Survivor</a></li>";
	}
	else {
		echo "<li><a href='/survivor/$pl_s_url/'>$pl_s_name</a></li>";
		echo "<li><u><a href='/camp/$pl_c_url/'>$pl_c_name</a></u></li>";
		echo "<li><a href='/page/skills/'>Skills</a></li>";
		echo "<li><a href='/page/actions/'>Actions</a></li>";
		echo "<li><a href='/page/inventory/'>Inventory</a></li>";
		echo "<li><a href='/page/messages/'>Messages $pm_alert</a></li>";
		echo "<li><a href='/page/settings/'>Settings</a></li>";
	}
?>
	<hr />
	<li><a href='/page/camps/'>Find a Camp</a></li>
	</ul>
</div>