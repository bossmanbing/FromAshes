<?php
/*
REMOVES ONE PLAYER'S SUPPORT FROM ANOTHER PLAYER
*/
session_start();
include('../../include/init.inc.php');

$rec_surv = $db->escape_string(htmlentities($_POST['rec_survivor']));

// Redirect on empty fields
//
if (empty($rec_surv) || empty($player_id)){
	header('Location:/page/create/');
}
$time = time();

// Delete previous support record
//
$ins_qry = "DELETE FROM supporters
				WHERE give_surv_id = $player_id
				AND rec_surv_id = $rec_surv
				LIMIT 1";

if(!$result = $db->query($ins_qry)){
    die('There was an error running the query [' . $db->error . ']');
}

// If it all works, redirect back to user's page who lost support
//
else{
	$surv_qry = "SELECT surv_url FROM survivor
					WHERE surv_id = '$rec_surv'
					LIMIT 1";
	$result = $db->query($surv_qry);
	$row = $result->fetch_assoc();
	$surv_url = $row['surv_url'];
	header('Location:/survivor/'.$surv_url.'/');
}
?>