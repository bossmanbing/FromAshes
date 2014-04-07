<?php
session_start();
include('../include/init.inc.php');
include('../include/functions.php');

$get_url = $_GET['survivor'];
$surv_qry = "SELECT surv_id, surv_name, surv_url, verified, surv_desc
				FROM survivor
				WHERE surv_url = '$get_url'";
$surv_result = $db->query($surv_qry);
$surv_row = $surv_result->fetch_assoc();

$surv_id = $surv_row['surv_id'];
$surv_name = $surv_row['surv_name'];
$surv_url = $surv_row['surv_url'];
$verified = $surv_row['verified'];
$surv_desc = $surv_row['surv_desc'];
$surv_desc = stripslashes_deep(nl2br($surv_desc));

$camp_qry = "SELECT sc.camp_id, c.camp_name, c.camp_url
				FROM survivor_camp sc
				JOIN camp c
					ON c.camp_id = sc.camp_id
				WHERE sc.surv_id = $surv_id";
$camp_result = $db->query($camp_qry);
$camp_row = $camp_result->fetch_assoc();

$camp_id = $camp_row['camp_id'];
$camp_name = $camp_row['camp_name'];
$camp_url = $camp_row['camp_url'];

$home_camp = '';
$supported = '';
if (isset($_SESSION['online'])){
	$home_qry = "SELECT camp_id FROM survivor_camp
					WHERE surv_id = $player_id
					AND camp_id = $camp_id";
					
	$home_res = $db->query($home_qry);
	$home_camp = $home_res->num_rows;

	$supp_qry = "SELECT give_surv_id FROM supporters
				WHERE give_surv_id = $player_id
				AND rec_surv_id = $surv_id";
				
	$supp_res = $db->query($supp_qry);
	$supported = $supp_res->num_rows;
}




$supps_qry = "SELECT s.surv_name, s.surv_url
				FROM survivor s
				JOIN survivor_camp sc
					ON sc.surv_id = s.surv_id
				JOIN camp c
					ON c.camp_id = sc.camp_id
					AND sc.camp_id = $camp_id
				JOIN supporters ss
					ON ss.give_surv_id = s.surv_id
				WHERE rec_surv_id = $surv_id
				AND c.camp_id = $camp_id";

$supps_res = $db->query($supps_qry);
$supps_cnt = $supps_res->num_rows;

$hap_qry = "SELECT h_id, h_message, h_timestamp
		FROM happenings
		WHERE surv_id = $surv_id
		AND h_type = 0
		ORDER BY h_id DESC
		LIMIT 5";
$hap_res = $db->query($hap_qry);
$hap_count = $hap_res->num_rows;

?>
<!DOCTYPE html>
<?php include('../include/head.inc.php'); ?>
	<title>From Ashes | <?php echo $surv_name; ?></title>
</head>

<?php include('../include/top.inc.php'); ?>

	<hr />
	<div id='content'>
	
		<?php include('../include/control.inc.php'); ?>
		
		<div id='page-content'>
			<h2><?php echo $surv_name; ?></h2>
			<hr />
			<div id='survivor-desc'>
			
			<h4>Camp: <a href="/camp/<?php echo $camp_url; ?>/">
					<?php echo $camp_name; ?>
				</a></h4>
			<?php
				if ($surv_desc){
					echo $surv_desc;
				}
				else{
					echo "$surv_name is a ragged, waste-torn survivor of <a href='/camp/".$camp_url."/'>".$camp_name."</a>. There is little else to say until $surv_name decides it needs to be said.";
				}
			?>
				
				<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>
			</div>
		<div id='supporter-list'>
			<h4>Supporters for <?php echo $surv_name; ?>: <?php echo $supps_cnt; ?></h4>
		<?php
			while($row = $supps_res->fetch_assoc()){
				echo "<div class='supporter'>";
					echo "<a href='/survivor/".$row['surv_url']."/'>".
						$row['surv_name']
					."</a>";
				echo "</div>";
			}
		?>
			
			
			<div id='support-form'>
			<br /><br />
		<?php
			if ($verified == 1 && $home_camp == 1 && $supported != 1 && ($player_id != $surv_id)){
		?>
				<form method='post' action='/action/survivor/add-support/'>
					<input type='hidden' value='<?php echo $surv_id; ?>' name='rec_survivor' />
					<input type='submit' value='Support <?php echo $surv_name; ?>' />
					<br />
					<em>Supporting <?php echo $surv_name; ?> will remove your support from other survivors.</em>
				</form>
		<?php
		}
			elseif  ($verified == 1 && $home_camp == 1 && $supported == 1 && ($player_id != $surv_id)){
		?>
				<form method='post' action='/action/survivor/remove-support/'>
					<input type='hidden' value='<?php echo $surv_id; ?>' name='rec_survivor' />
					<input type='submit' value='Remove support from <?php echo $surv_name; ?>' />
				</form>
		<?php
		}
			else{
				echo '';
			}
		?>
			</div> <!-- END #support-form DIV -->
		</div> <!-- END #support-list DIV -->
		<div class='clear'></div>
		<hr />
		
		<!-- recent survivor events -->
		<div id='camp-updates'>
			<h4><?php echo $surv_name; ?> Events</h4>	
		<?php
			if($hap_count == 0){
				echo "Nothing has happened yet...";
			}
			else{
				while($row = $hap_res->fetch_assoc()){
					echo "<div class='camp-upd'>
						<span class='hap-time'>".convertTime($row['h_timestamp'])."</span> - ".
							$row['h_message']."
						</div>";
				}
			}
		?>
			</div>
		<!-- END survivor events -->
		<hr />
		<?php
			
			if (isset($_SESSION['online']) && ($player_id != $surv_id)){
		?>
			<div id='pvt-msg'>
				Send a message to <?php echo $surv_name; ?>:<br /><br />
				<form method='post' action='/action/survivor/send-msg/'>
					<input type='hidden' value='<?php echo $surv_id; ?>' name='msg_get' />
					<input type='hidden' value='<?php echo $surv_url; ?>' name='surv_url' />
					<textarea name='msg' maxlength=1200 cols=70 rows=7 required></textarea>
					<br />
					<input type='submit' value='Message <?php echo $surv_name; ?>' />
				</form>
			</div>
		<?php
		} // end above IF
		else{}
		?>
		
<?php include('../include/footer.inc.php'); ?>

</body>
</html>