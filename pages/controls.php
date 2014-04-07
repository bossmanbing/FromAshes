<?php
session_start();
include('../include/init.inc.php');
include('../include/functions.php');

if (!isset($_SESSION['online'])){
	header("Location:/home/");
}
else{}

$camp_qry = "SELECT camp_desc
				FROM camp
				WHERE camp_id = $pl_c_id";
$camp_result = $db->query($camp_qry);
$camp_row = $camp_result->fetch_assoc();

$camp_id = $pl_c_id;
$camp_name = $pl_c_name;
$camp_url = $pl_c_url;
$camp_desc = stripslashes_deep($camp_row['camp_desc']);

$surv_qry = "SELECT COUNT(ss.give_surv_id) AS 'count', sc.surv_id, s.surv_name, s.surv_url
				FROM survivor_camp sc
				JOIN survivor s
					ON s.surv_id = sc.surv_id
				LEFT JOIN supporters ss
					ON ss.rec_surv_id = s.surv_id
				WHERE sc.camp_id = $camp_id
				AND sc.surv_id != $player_id
				GROUP BY sc.surv_id, s.surv_name, s.surv_url
				ORDER BY COUNT( ss.give_surv_id ) ";
				
$surv_res = $db->query($surv_qry);

$ldr_qry = "SELECT s.surv_name, s.surv_url, sc.surv_id 
			FROM survivor_camp sc
			JOIN survivor s
				ON s.surv_id = sc.surv_id
			WHERE sc.camp_id = $camp_id
			AND sc.camp_leader = $camp_id
			AND s.surv_id = $player_id";
$ldr_res = $db->query($ldr_qry);

if ($ldr_res->num_rows != 1){
	header("Location:/camp/".$camp_url."/");
}
else{}
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
			
			
			<div id='control-desc'>
			<h4>Update camp description.</h4>
				<form id='update-desc' method='post' action='/action/camp/desc/'>
			
				<?php
					if ($camp_desc){
				?>
					<textarea name='desc' maxlength=512 cols=70 rows=7 required><?php echo br2nl($camp_desc); ?></textarea>
				<?php
				}
					else{
				?>
					<textarea name='desc' maxlength=700 cols=70 rows=7 placeholder='Limit 700 characters...' required></textarea>
				<?php
				}
				?>
					<br />
					<input type='submit' value='Update Description' name='update' />
					
				</form>
			</div>
			<hr />
			
			<div id='control-members'>
			<h4>Exile members to The Wastelands.</h4>
			<p>
				You can only exile members do do not have any supporters.
			</p>
		<?php
			$disabled = '';
			while ($row = $surv_res->fetch_assoc()){
			
			// If the player has supporters, disabled to exile button.
			
			if ($row['count'] > 0){
				$disabled = "disabled";
			}
			else{
				$disabled = '';
			}
		?>
			<div class='camp-mem'>
				<a href="/survivor/<?php echo $row['surv_url']; ?>/">
					<?php echo $row['surv_name']; ?>
				</a>
				
				<form class='control-exile' action='/action/camp/exile/' method='post'>
					<input type='hidden' name='survivor' value='<?php echo $row['surv_id']; ?>' />
					<input type='submit' name='exile' value='Exile' <?php echo $disabled; ?> />
				</form>
			</div>
		<?php
			} // END WHILE LOOP
		?>
			</div>
		
<?php include('../include/footer.inc.php'); ?>

</body>
</html>