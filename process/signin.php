<?php
//
// Logs the player in to their account
//
session_start();
include('../include/init.inc.php');


$username = $db->escape_string(htmlentities($_POST['username']));
$password = $db->escape_string(htmlentities($_POST['password']));

// Check for empty fields
//
if (empty($username) || empty($password)){
	header('Location: ../');
}

// Hash the password, and convert user name to standard url version
//
$password = md5($password);
$urlname = strtolower(str_replace(' ', '_', $username));

// Check for matching user name and password hash
//
$qry = "SELECT s.surv_id, surv_created, sc.camp_id 
			FROM survivor s
			JOIN survivor_camp sc
				ON sc.surv_id = s.surv_id
			WHERE s.surv_url = '$urlname'
			AND s.surv_password = '$password'";
			
if(!$result = $db->query($qry)){
    die('There was an error running the query [' . $db->error . ']');
}

$res_cnt = $result->num_rows;

// If there is not a match, jump out
//
if ($res_cnt != 1){
	header('Location:../');
}

// With successful match, set session variables and redirect to survivor's page
//
else{
	$row = $result->fetch_assoc();
	$_SESSION['online'] = true;
	$_SESSION['id'] = $row['surv_id'];
	$_SESSION['camp'] = $row['camp_id'];
	
	// SET PERSISTENT LOG IN
	$check = $row['surv_id'].$urlname.$row['surv_created'].$row['surv_id'];
	$check = md5($check);
	
	setcookie("user", $urlname, time()+36000000, "/");
	setcookie("userKey", $check, time()+36000000, "/");
	
	header('Location:/survivor/'.$urlname.'/');
}

?>