<?php
session_start();
include('../include/init.inc.php');


$camp_qry = "SELECT COUNT(sc.surv_id) AS s_count, sc.camp_id, c.camp_name, c.camp_url 
				FROM camp c
				LEFT JOIN survivor_camp sc
					ON sc.camp_id = c.camp_id
				GROUP BY sc.camp_id, c.camp_name, c.camp_url
				ORDER BY COUNT(sc.surv_id) DESC";
$camp_result = $db->query($camp_qry);

?>
<!DOCTYPE html>

<?php include('../include/head.inc.php'); ?>
	<title>From Ashes | Camps</title>
</head>

<?php include('../include/top.inc.php'); ?>

	<hr />
	<div id='content'>
		<?php include('../include/control.inc.php'); ?>
		
		<div id='page-content'>
			<h2 id='camp_name'>Survivor Camps</h2>
			
			
			<div id='camp_members'>
				
	<?php
	//
	// Print names of camp members to screen
	//
	while ($row = $camp_result->fetch_assoc()){
	?>
	<a href="/camp/<?php echo $row['camp_url']; ?>/">
		<?php echo $row['camp_name'];?>
	</a>
	 - Survivors: <?php echo $row['s_count']; ?>
	
	<br /><br />
	<?php
	} //ENDS THE WHILE LOOP

	?>
			</div>

<?php include('../include/footer.inc.php'); ?>

</body>
</html>