<?php
session_start();
include('../../include/init.inc.php');

$camp_id = $pl_c_id;
$camp_url = $pl_c_url;
$camp_name = $pl_c_name;
$ldr_id = $player_id;

$surv = $db->escape_string($_POST['survivor']);

if (empty($surv) || empty($camp_id) || empty($camp_url)){
	header('Location:/page/controls/');
}

$srv_qry = "SELECT surv_name, surv_url FROM survivor
				WHERE surv_id = '$surv'
				LIMIT 1";
$srv_res = $db->query($srv_qry);
$srv_row = $srv_res->fetch_assoc();
$surv_name = $srv_row['surv_name'];
$surv_url = $srv_row['surv_url'];


/*
	RUN CHECKS TO MAKE SURE THAT THE CURRENT PLAYER IS THE LEADER OF THE CAMP.
	Can worry about this later.
	
	CHECK TO MAKE SURE THE EXILED PLAYER HAS NO SUPPORTERS
*/
$camp_qry = "SELECT camp_id FROM camp
					WHERE camp_url = 'the_wastelands'
					LIMIT 1";
	$camp_res = $db->query($camp_qry);
	$camp_row = $camp_res->fetch_assoc();
	$camp = $camp_row['camp_id'];
	
$upd_qry = "UPDATE survivor_camp
			SET camp_id = $camp, camp_leader = 0
			WHERE surv_id = $surv";

if(!$result = $db->query($upd_qry)){
    die('There was an error running the query [' . $db->error . ']');
}

else{
	$msg = "You have been exiled from <a href='/camp/".$camp_url."/'>".$camp_name."</a>";
	$msg = $db->escape_string($msg);

	$time = time();
	$msg_qry = "INSERT INTO pvt_msg
				(surv_from, surv_to, pm_content, pm_timestamp)
			VALUES ($player_id, $surv, '$msg', $time)";
	$db->query($msg_qry);
	
	$del_qry = "DELETE FROM supporters
					WHERE give_surv_id = $surv
					OR rec_surv_id = $surv";
	$db->query($del_qry);
	
	
	$h_msg1 = "<a href='/survivor/$surv_url'>$surv_name</a> has arrived from <a href='/camp/".$camp_url."/'>".$camp_name."</a>.";
	$h_msg2 = "<a href='/survivor/$surv_url'>$surv_name</a> has been exiled to <a href='/camp/the_wastelands/'>The Wastelands</a>.";
	$h_msg3 = "<a href='/survivor/$pl_s_url'>$pl_s_name</a> exiled <a href='/survivor/$surv_url'>$surv_name</a> from <a href='/camp/".$camp_url."/'>".$camp_name."</a>.";
	$h_msg4 = "<a href='/survivor/$surv_url'>$surv_name</a> was exiled from <a href='/camp/".$camp_url."/'>".$camp_name."</a> by <a href='/survivor/$pl_s_url'>$pl_s_name</a>.";
	$h_msg1 = $db->escape_string($h_msg1);
	$h_msg2 = $db->escape_string($h_msg2);
	$h_msg3 = $db->escape_string($h_msg3);
	$h_msg4 = $db->escape_string($h_msg4);
	
	$ins_qry = "INSERT INTO happenings
					(surv_id, camp_id, h_message, h_timestamp, h_type)
					VALUES ($surv, $camp, '$h_msg1', $time, 1)";
	$db->query($ins_qry);
	$ins_qry = "INSERT INTO happenings
					(surv_id, camp_id, h_message, h_timestamp, h_type)
					VALUES ($surv, $pl_c_id, '$h_msg2', $time, 1)";
	$db->query($ins_qry);
	$ins_qry = "INSERT INTO happenings
					(surv_id, camp_id, h_message, h_timestamp, h_type)
					VALUES ($player_id, $pl_c_id, '$h_msg3', $time, 0)";
	$db->query($ins_qry);
	$ins_qry = "INSERT INTO happenings
					(surv_id, camp_id, h_message, h_timestamp, h_type)
					VALUES ($surv, $pl_c_id, '$h_msg4', $time, 0)";
	$db->query($ins_qry);
	
	header('Location:/page/controls/');
}

?>