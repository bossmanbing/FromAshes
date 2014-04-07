<?php
session_start();
include('./include/init.inc.php');
if (isset($_SESSION['id'])){
	header('Location:/survivor/'.$pl_s_url.'/');
}
else{}
?>
<!DOCTYPE html>

<?php include('./include/head.inc.php'); ?>
	<title>From Ashes | Welcome</title>
</head>

<?php include('./include/top.inc.php'); ?>

	<hr />
	<div id='content'>
		<br /><br />
	 <center><img src='/resources/images/1-bg.jpg' /></center>
		<h2>We have survived the apocalypse</h2>
		<p><strong>The end of the world has passed.</strong> Nearly the entire population of our planet has been wiped out. Some people were able to find shelter in underground bunkers, but most of us were forced to face the destruction head on.</p>
		<p><strong>We have survived.</strong> Humanity has not breathed its last breath. Survivors are all over the place, doing whatever we can to survive. Some of us have formed camps, and those camps are growing. We live off of each other and work towards finding a way to rebuild society.</p>
		<p>For now, we rise from the ashes of the Earth. For now, we survive.</p>
		<p><a href='/page/create/'>Create your Survivor to get started.</a>
		<br /><br />
	</div>

<?php include('./include/footer.inc.php'); ?>
</div>	
</body>
</html>