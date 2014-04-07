<?php
/*
PROCESS USER'S SELECTED ACTION
*/
session_start();
include('../../include/init.inc.php');

$surv_id = $player_id;
$surv_url = $pl_s_url;
$action = $db->escape_string(htmlentities($_POST['action']));

// Check for empty input
//
if (empty($player_id) || empty($action)){
	header('Location:/survivor/'.$pl_s_url.'/');
}

// Find matching action based on input
//
$action = strtolower($action);
$act_qry = "SELECT act_id, act_time, act_level
				FROM action
				WHERE act_name = '$action'
				LIMIT 1";
$act_res = $db->query($act_qry);

// Jump out on invalid input
//
if ($act_res->num_rows != 1){
	header('Location:/survivor/'.$pl_s_url.'/');
}
else{
	$act = $act_res->fetch_assoc();
}

$act_id = $act['act_id'];
$act_time = $act['act_time'];
$act_level = $act['act_level'];

// Delete any previous action entries for this user
//
$del_qry = "DELETE FROM surv_action
				WHERE surv_id = $surv_id
				LIMIT 1";
$db->query($del_qry);

// Insert the new action into the db
//
$ins_qry = "INSERT INTO surv_action
				VALUES ($surv_id, $act_id, $act_time)";
$db->query($ins_qry);

header('Location:/page/actions/');

?>