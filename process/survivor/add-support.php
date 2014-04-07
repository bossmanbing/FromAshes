<?php
/*
ONE PLAYER ADDS SUPPORT TO ANOTHER PLAYER
*/
session_start();
include('../../include/init.inc.php');

$rec_surv = $db->escape_string(htmlentities($_POST['rec_survivor']));

// Redirect on empty input
//
if (empty($rec_surv) || empty($player_id)){
	header('Location:/page/create/');
}
$time = time();

// Only allow player to support 1 other player - Delete all entries for this player
//
$del_qry = "DELETE FROM supporters
				WHERE give_surv_id = $player_id
				LIMIT 1";
$db->query($del_qry);

// Add new support
//
$ins_qry = "INSERT INTO supporters
				VALUES ($player_id, '$rec_surv', $time)";

if(!$result = $db->query($ins_qry)){
    die('There was an error running the query [' . $db->error . ']');
}
else{
	
	// Redirect to player that is being supported
	//
	$surv_qry = "SELECT surv_url FROM survivor
					WHERE surv_id = '$rec_surv'
					LIMIT 1";
	$result = $db->query($surv_qry);
	$row = $result->fetch_assoc();
	$surv_url = $row['surv_url'];
	header('Location:/survivor/'.$surv_url.'/');
}
?>