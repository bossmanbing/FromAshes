<?php
/*
SEND A PRIVATE MESSAGE
*/
session_start();
include('../../include/init.inc.php');

$surv_to = $db->escape_string(htmlentities($_POST['msg_get']));
$surv_from = $player_id;
$surv_url = $db->escape_string(htmlentities($_POST['surv_url']));
$msg = $db->escape_string(nl2br(htmlentities($_POST['msg'])));

// Check for empty input
//
if (empty($surv_to) || empty($player_id) || empty($msg)){
	header('Location:/survivor/'.$pl_s_url.'/');
}

$time = time();

// Add new message to db
//
$ins_qry = "INSERT INTO pvt_msg
				(surv_from, surv_to, pm_content, pm_timestamp)
			VALUES ($surv_from, $surv_to, '$msg', $time)";

if(!$result = $db->query($ins_qry)){
    die('There was an error running the query [' . $db->error . ']');
}
else{

	header('Location:/survivor/'.$surv_url.'/');
}

?>