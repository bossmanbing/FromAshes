<?php
session_start();
include('../include/init.inc.php');
include('../include/functions.php');

if (!isset($_SESSION['online'])){
	header("Location:/home/");
}
else{}

$pm_qry = "SELECT pm_id, surv_from, pm_content, pm_viewed, pm_timestamp,
				  surv_name, surv_url
					FROM pvt_msg
					JOIN survivor s
						ON surv_id = surv_from
					WHERE surv_to = $player_id
					AND pm_active = 1
					ORDER BY pm_id DESC";
					
$pm_res = $db->query($pm_qry);
$pm_count = $pm_res->num_rows;

$update_qry = "UPDATE pvt_msg
				SET pm_viewed = 1
				WHERE surv_to = $player_id";
$db->query($update_qry);

?>
<!DOCTYPE html>

<?php include('../include/head.inc.php'); ?>
	<title>From Ashes | Messages</title>
</head>

<?php include('../include/top.inc.php'); ?>

	<hr />
	<div id='content'>
		<?php include('../include/control.inc.php'); ?>
		
		<div id='page-content'>
			<h2 id='camp_name'>Messages for <?php echo $pl_s_name; ?></h2>

			<div id='surv_messages'>
				
		<?php
			if ($pm_count == 0){
				echo "You doesn't have any messages yet.";
			}
			else{
				while($row = $pm_res->fetch_assoc()){
					echo "<div class='camp-msg'>
							<div class='msg-head'>
								<a href='/survivor/".$row['surv_url']."/'>
									".$row['surv_name']."
								</a>
								 <span class='msg-time'>".convertTime($row['pm_timestamp'])."</span>
							
						</div>
						<div class='cmp-msg-con'>
							".stripslashes($row['pm_content'])
					."	</div>
					</div>";
				}
			}
			
			
		?>
			</div>
		
<?php include('../include/footer.inc.php'); ?>

</body>
</html>