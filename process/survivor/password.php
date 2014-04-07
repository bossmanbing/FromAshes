<?php
/*
UPDATES THE USER'S PASSWORD
*/
session_start();
include('../../include/init.inc.php');

$surv_id = $player_id;
$surv_url = $pl_s_url;
$curr = $db->escape_string(htmlentities($_POST['curr']));
$new = $db->escape_string(htmlentities($_POST['new']));
$con = $db->escape_string(htmlentities($_POST['con']));

// Hash all new inputs
//
$curr = md5($curr);
$new = md5($new);
$con = md5($con);

// Check to see if CURRENT password matches password in the system
//
$qry = "SELECT surv_id
			FROM survivor
			WHERE surv_id = $surv_id
			AND surv_password = '$curr'";
			
if(!$result = $db->query($qry)){
    die('There was an error running the query [' . $db->error . ']');
}

$res_cnt = $result->num_rows;

// Redirect out if user failed to enter correct current password
//
if ($res_cnt != 1){
	header('Location:/page/settings/');
}

// Redirect on matching fields
//
elseif (empty($con) || empty($new) || empty($curr)){
	header('Location:/page/settings/');
}

// Redirect on mismatched new passwords
//
elseif ($new != $con){
	header('Location:/page/settings/');
}

// Update the user's password
//
else{
	$ins_qry = "UPDATE survivor
				SET surv_password = '$new'
				WHERE surv_id = $surv_id";

	if(!$result = $db->query($ins_qry)){
		die('There was an error running the query [' . $db->error . ']');
	}
	else{

		header('Location:/survivor/'.$surv_url.'/');
	}
}

?>