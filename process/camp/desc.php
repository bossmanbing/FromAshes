<?php
session_start();
include('../../include/init.inc.php');

$camp_id = $pl_c_id;
$camp_url = $pl_c_url;
$desc = $db->escape_string(htmlentities($_POST['desc']));

if (empty($desc) || empty($camp_id) || empty($camp_url)){
	header('Location:/page/controls/');
}


/*
	RUN CHECKS TO MAKE SURE THAT THE CURRENT PLAYER IS THE LEADER OF THE CAMP.
	Can worry about this later.
*/

$ins_qry = "UPDATE camp
			SET camp_desc = '$desc'
			WHERE camp_id = $camp_id";

if(!$result = $db->query($ins_qry)){
    die('There was an error running the query [' . $db->error . ']');
}
else{

	header('Location:/page/controls/');
}

?>