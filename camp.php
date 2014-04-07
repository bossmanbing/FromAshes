<?php
session_start();
include('../include/init.inc.php');

$get_url = $_GET['camp'];
$camp_qry = "SELECT camp_id, camp_name, camp_url, camp_desc
				FROM camp
				WHERE camp_url = '$get_url'";
$camp_result = $db->query($camp_qry);
$camp_row = $camp_result->fetch_assoc();

$camp_id = $camp_row['camp_id'];
$camp_name = $camp_row['camp_name'];
$camp_url = $camp_row['camp_url'];
$camp_desc = $camp_row['camp_desc'];

$surv_qry = "SELECT sc.surv_id, s.surv_name, s.surv_url
				FROM survivor_camp sc
				JOIN survivor s
					ON s.surv_id = sc.surv_id
				WHERE sc.camp_id = $camp_id";
				
$surv_result = $db->query($surv_qry);
$surv_cnt = $surv_result->num_rows;
$surv_s = '';
if ($surv_cnt > 1){
	$surv_s = 's';
}
else{}

$home_camp = '';
$home_qry = "SELECT camp_id FROM survivor_camp
				WHERE surv_id = $player_id
				AND camp_id = $camp_id";
				
$home_res = $db->query($home_qry);
$home_camp = $home_res->num_rows;

$camp_msg_qry = "SELECT cm.camp_msg_id, cm.msg_content, cm.camp_id, cm.surv_id, s.surv_name 
					FROM camp_msg cm
					JOIN survivor s
						ON s.surv_id = cm.surv_id
					WHERE camp_id = $camp_id
					AND msg_active = 1
					ORDER BY camp_msg_id ASC
					LIMIT 10";
					
$camp_msg_res = $db->query($camp_msg_qry);
$camp_msg_cnt = $camp_msg_res->num_rows;
?>
<!DOCTYPE html>

<?php include('../include/head.inc.php'); ?>
	<title>From Ashes | <?php echo $camp_name; ?></title>
</head>

<?php include('../include/top.inc.php'); ?>

	<hr />
	<div id='content'>
		<?php include('../include/control.inc.php'); ?>
		
		<div id='page-content'>
			<h2 id='camp_name'><?php echo $camp_name; ?></h2>
			
			<div id='camp_desc'>
				<?php echo $camp_desc; ?>
			</div>
			<hr />
			<div id='camp-move'>
		<?php
			if ($home_camp != 1){
		?>
				<form id='move-form' method='post' action='/action/camp/move/'>
					<input type='hidden' name='camp' value='<?php echo $camp_id; ?>' />
					<input type='submit' value='Move to <?php echo $camp_name; ?>' />
				</form>
		<?php
		} //END IFF
		else{
		?>
				<button type='button'>
					<a href='/page/camps/'>
					Search for a new camp
					</a>
				</button>
		<?php
		} // END ELSE
		?>
			</div>
			
			<div id='camp_members'>
				<p><?php echo $camp_name; ?> has <?php echo $surv_cnt; ?> member<?php echo $surv_s; ?>:</p>

	<?php
	//
	// Print names of camp members to screen
	//
	while ($surv_row = $surv_result->fetch_assoc()){
	?>
	<a href="/survivor/<?php echo $surv_row['surv_url']; ?>/">
		<?php echo $surv_row['surv_name']; ?>
	</a>
	<br /><br />
	<?php
	} //ENDS THE WHILE LOOP

	?>
			</div>
			
			<div id='camp_messages'>
				<hr />
		<?php
			if ($camp_msg_cnt == 0){
				echo "$camp_name doesn't have any messages yet.";
			}
			else{
				while($row = $camp_msg_res->fetch_assoc()){
					echo "<div class='camp-msg'>".
						$row['surv_name']." - ".stripslashes($row['msg_content'])
					."</div>";
				}
			}
			
			if ($home_camp == 1){
		?>
				<hr />
				<form id='camp_msg_form' method='post' action='/action/camp/post/'>
					<input type='hidden' name='camp' value='<?php echo $camp_id; ?>' />
					<textarea name='msg' maxlength=1200 cols=30 rows=7 required></textarea>
					<br /><br />
					<input type='submit' value='Post Message' name='post' />
				</form>
		<?php
		 } else{}
		?>
				
			</div>
		
<?php include('../include/footer.inc.php'); ?>

</body>
</html>