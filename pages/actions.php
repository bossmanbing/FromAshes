<?php
session_start();
include('../include/init.inc.php');
include('../include/functions.php');

if (!isset($_SESSION['online'])){
	header("Location:/home/");
}
else{}

$surv_qry = "SELECT surv_desc
				FROM survivor
				WHERE surv_id = '$player_id'";
$surv_result = $db->query($surv_qry);
$surv_row = $surv_result->fetch_assoc();

$surv_desc = trim(br2nl($surv_row['surv_desc']),'\t');

$act_qry = "SELECT sa.act_id, sa.sa_time, a.act_name
				FROM surv_action sa
				JOIN action a
					ON a.act_id = sa.act_id
				WHERE surv_id = $player_id";
$act_res = $db->query($act_qry);
$act = $act_res->fetch_assoc();

$act_id = $act['act_id'];
$act_time = $act['sa_time'];
$act_name = $act['act_name'];

if($act_time == 1){
	$act_time = $act_time." update";
}
elseif($act_time > 1){
	$act_time = $act_time." updates";
}
?>
<!DOCTYPE html>

<?php include('../include/head.inc.php'); ?>
	<title>From Ashes | Actions</title>
</head>

<?php include('../include/top.inc.php'); ?>

	<hr />
	<div id='content'>
		<?php include('../include/control.inc.php'); ?>
		
		<div id='page-content'>
			<h2 id='surv_name'><?php echo $pl_s_name; ?></h2>

			
			<h3>What would you like to do next?</h3>
			
		<?php
		if($act_id){
			echo "<em>You have selected to $act_name. The action will be completed after $act_time.</em>";
			echo "<hr />";
		}
		else{}
		?>
			<form id='action-form' method='post' action='/action/survivor/actions/'>
			
			<div class='action'>
				Search the wastes for supplies.
				<br />
				<input type='submit' value='Scavenge' name='action' />
			</div>

			<div class='action'>
				Hunt for food.
				<br />
				<input type='submit' value='Hunt' name='action' />
			</div>
				
			<div class='action'>
				Trade supplies with other survivors.
				<br />
				<input type='submit' value='Trade' name='action' disabled/>
			</form>
		</div>
		
<?php include('../include/footer.inc.php'); ?>

</body>
</html>