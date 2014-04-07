<?php
session_start();
include('../../include/init.inc.php');

$camp = $db->escape_string(htmlentities($_POST['camp']));
$msg = $db->escape_string(nl2br(htmlentities($_POST['msg'])));

if (empty($camp) || empty($player_id) || empty($msg)){
	header('Location:/camp/'.$pl_c_url.'/');
}

$time = time();
$ins_qry = "INSERT INTO camp_msg
				(surv_id, camp_id, msg_content, msg_timestamp)
			VALUES ($player_id, $camp, '$msg', $time)";

if(!$result = $db->query($ins_qry)){
    die('There was an error running the query [' . $db->error . ']');
}
else{

	header('Location:/camp/'.$pl_c_url.'/');
}

?>