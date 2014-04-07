<?php
session_start();
include('../../include/init.inc.php');

$camp = $db->escape_string(htmlentities($_POST['camp']));

if (empty($camp) || empty($player_id)){
	header('Location:/page/create/');
}

$upd_qry = "UPDATE survivor_camp
				SET camp_id = '$camp'
				WHERE surv_id = $player_id
				LIMIT 1";


if(!$result = $db->query($upd_qry)){
    die('There was an error running the query [' . $db->error . ']');
}
else{
	$del_qry = "DELETE FROM supporters
					WHERE give_surv_id = $player_id
					OR rec_surv_id = $player_id";
	$db->query($del_qry);
	
	$cmp_qry = "SELECT camp_name, camp_url FROM camp
					WHERE camp_id = '$camp'
					LIMIT 1";
	$camp_res = $db->query($cmp_qry);
	$camps = $camp_res->fetch_assoc();
	$new_camp = $camps['camp_name'];
	$camp_url = $camps['camp_url'];
	$old_camp = $pl_c_name;
	
	$h_msg1 = "<a href='/survivor/$pl_s_url'>$pl_s_name</a> has arrived from <a href='/camp/$pl_c_url'>$pl_c_name</a>.";
	$h_msg2 = "<a href='/survivor/$pl_s_url'>$pl_s_name</a> has left for <a href='/camp/$camp_url'>$new_camp</a>.";
	$h_msg3 = "<a href='/survivor/$pl_s_url'>$pl_s_name</a> left <a href='/camp/$pl_c_url'>$pl_c_name</a> for <a href='/camp/$camp_url'>$new_camp</a>.";
	$h_msg1 = $db->escape_string($h_msg1);
	$h_msg2 = $db->escape_string($h_msg2);
	$h_msg3 = $db->escape_string($h_msg3);
	$time = time();
	
	$ins_qry = "INSERT INTO happenings
					(surv_id, camp_id, h_message, h_timestamp, h_type)
					VALUES ($player_id, $camp, '$h_msg1', $time, 1)";
	if(!$result = $db->query($ins_qry)){
		die('There was an error running the query [' . $db->error . ']');
	}
	
	$ins_qry = "INSERT INTO happenings
					(surv_id, camp_id, h_message, h_timestamp, h_type)
					VALUES ($player_id, $pl_c_id, '$h_msg2', $time, 1)";
	if(!$result = $db->query($ins_qry)){
		die('There was an error running the query [' . $db->error . ']');
	}
	
	$ins_qry = "INSERT INTO happenings
					(surv_id, camp_id, h_message, h_timestamp, h_type)
					VALUES ($player_id, $pl_c_id, '$h_msg3', $time, 0)";
	if(!$result = $db->query($ins_qry)){
		die('There was an error running the query [' . $db->error . ']');
	}
	
	header('Location:/camp/'.$camp_url.'/');
}
?>