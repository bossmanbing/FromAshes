<?php
/*
UPDATE SURVIVOR BIOGRAPHY ON PERSONAL PAGE
*/
session_start();
include('../../include/init.inc.php');

$surv_id = $player_id;
$surv_url = $pl_s_url;
$bio = $db->escape_string(htmlentities($_POST['bio']));

// Redirect on empty input
//
if (empty($bio) || empty($surv_id) || empty($surv_url)){
	header('Location:/page/settings/');
}

// Update bio in db
//
$ins_qry = "UPDATE survivor
			SET surv_desc = '$bio'
			WHERE surv_id = $surv_id";

if(!$result = $db->query($ins_qry)){
    die('There was an error running the query [' . $db->error . ']');
}
else{

	header('Location:/survivor/'.$surv_url.'/');
}

?>