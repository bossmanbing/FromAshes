<?php
/*
CREATES A BRAND NEW SURVIVOR
*/
session_start();
include('../../include/init.inc.php');


$username = $db->escape_string(htmlentities($_POST['username']));
$password = $db->escape_string(htmlentities($_POST['password']));
$repassword = $db->escape_string(htmlentities($_POST['repassword']));
$email = $db->escape_string(htmlentities($_POST['email']));

// Check for empty required input and redirects
//
if (empty($username) || empty($password)){
	header('Location:/page/create/');
}

// Check for matching passwords
//
if ($password != $repassword){
	header('Location:/page/create/');
}

// Only allow alphanumeric user name, spaces are okay
//
if(!preg_match("/^[a-zA-Z0-9 ]+$/", $username)) {
   header('Location:/page/create/');
}

// user name to lowercase, replace spaces with underscore. Create URL friendly version of username
//
$urlname = strtolower(str_replace(' ', '_', $username));
$password = md5($password); // Hash password
$time = time();

// Add survivor record to db
//
$qry = "INSERT INTO survivor
		(surv_name, surv_url, surv_password, surv_email, surv_created)
		VALUES ('$username', '$urlname', '$password', '$email', $time)";

if(!$result = $db->query($qry)){
    die('There was an error running the query [' . $db->error . ']');
}

// If succesful, continue:
//
else{
	$id = $db->insert_id;
	
	// Get ID of 'the_wastelands' feeder camp
	$camp_qry = "SELECT camp_id FROM camp
					WHERE camp_url = 'the_wastelands'
					LIMIT 1";
	$camp_res = $db->query($camp_qry);
	$camp_row = $camp_res->fetch_assoc();
	$camp = $camp_row['camp_id'];
	
	// Add new survivor to "The Wastelands" as starter camp
	//
	$camp_ins = "INSERT INTO survivor_camp
					VALUES ($id,$camp,0)";
	$db->query($camp_ins);
	
	// Prep automatic 'Welcome' private message for new players
	//
	$msg = "Welcome to From Ashes! It's great to have you on the team, however you decide to contribute!
			<br /><br />
			After you check out the game, you're encouraged to sign up on our community forums, which can be found here:
			<br /><br />
			<a href='http://fromashes.boards.net/' target='_blank'><strong>From Ashes Forum</strong></a>
			<br /><br />
			We'll do a lot of communication through the forum for things like brainstorming new ideas, reporting bugs, helping development stay on track, and updates of new features as they're released. Communication with users throughout development will be one of the greatest assets during the creation of From Ashes.
			<br /><br />
			Thanks again for signing up! I'm looking forward to what we'll be able to do.";
	
	$msg = $db->escape_string($msg);
	
	// Default the letter from game admin, might change later
	//
	$adm_qry = "SELECT surv_id FROM survivor
					WHERE surv_url = 'mr_williams'
					LIMIT 1";
	$adm_res = $db->query($adm_qry);
	$adm_row = $adm_res->fetch_assoc();
	$adm = $adm_row['surv_id'];
	
	$time = time();
	
	// Send message
	//
	$msg_qry = "INSERT INTO pvt_msg
				(surv_from, surv_to, pm_content, pm_timestamp)
			VALUES ($adm, $id, '$msg', $time)";

	if(!$result = $db->query($msg_qry)){
    die('There was an error running the query [' . $db->error . ']');
} else{}
	
	$_SESSION['online'] = true;
	$_SESSION['id'] = $id;
	$_SESSION['camp'] = $camp;
	header('Location:/survivor/'.$urlname.'/');
}
?>