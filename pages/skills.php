<?php
session_start();
include('../include/init.inc.php');
include('../include/functions.php');

if (!isset($_SESSION['online'])){
	header("Location:/home/");
}
else{}

$sk_qry = "SELECT s.skill_id, s.skill_name, ss.skill_points
			FROM skill s
			JOIN surv_skill ss
				ON ss.skill_id = s.skill_id
			WHERE ss.surv_id = $player_id";
$sk_res = $db->query($sk_qry);
?>
<!DOCTYPE html>

<?php include('../include/head.inc.php'); ?>
	<title>From Ashes | Skills</title>
</head>

<?php include('../include/top.inc.php'); ?>

	<hr />
	<div id='content'>
		<?php include('../include/control.inc.php'); ?>
		
		<div id='page-content'>
			<h2 id='surv_name'><?php echo $pl_s_name; ?></h2>

			
			<h3>Your current skill set.</h3>
			
		<?php
			while ($row = $sk_res->fetch_assoc()){
				echo "<div class='skill-row'>
					<span class='skill-name'>".$row['skill_name']."</span>
					<span class='skill-points'>".floor($row['skill_points']/10)."</span>
				</div>";
			}
		?>
<?php include('../include/footer.inc.php'); ?>

</body>
</html>