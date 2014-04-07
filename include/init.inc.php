<?php

/* CONNECT TO DATABASE - copy for local and live servers - */
	//$db = new mysqli('localhost', 'root', '', 'ashes');
	//$db = new mysqli('mysql10.000webhost.com', 'a8999301_ashes', 'Fry!Br34d', 'a8999301_ashes');
	 $db = new mysqli('localhost','ashes_db','hhgi(*2DDfya00987','ashes');

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}
	/* If the user is logged in, set player variables. 
	
	*/
	if (isset($_COOKIE['userKey']) && isset($_COOKIE['user'])){
		$ck_user = $_COOKIE['user'];
		$ck_key = $_COOKIE['userKey'];
		$ck_qry = "SELECT surv_url, surv_id, surv_created
					FROM survivor
					WHERE surv_url = '$ck_user'
					LIMIT 1";
		if ($ck_res = $db->query($ck_qry)){
			$ck_row = $ck_res->fetch_assoc();
			$check = $ck_row['surv_id'].$ck_row['surv_url'].$ck_row['surv_created'].$ck_row['surv_id'];
			$check = md5($check);
			
			if ($check == $ck_key){
				$_SESSION['online'] = true;
				$_SESSION['id'] = $ck_row['surv_id'];
			}
			else{}
		}
		else{}
	}

	if (isset($_SESSION['online'])){
		$player_id = $_SESSION['id'];
		
		// Info for player and base camp
		//
		$pl_qry = "SELECT surv_name, surv_url, surv_created, camp_name, camp_url , c.camp_id
						FROM survivor s
						JOIN survivor_camp sc
							ON sc.surv_id = s.surv_id
						JOIN camp c
							ON sc.camp_id = c.camp_id
						WHERE s.surv_id = $player_id";
		$pl_result = $db->query($pl_qry);
		$pl_row = $pl_result->fetch_assoc();
		
		$pl_s_name = $pl_row['surv_name'];
		$pl_s_url = $pl_row['surv_url'];
		$pl_s_created = $pl_row['surv_created'];
		$pl_c_name = $pl_row['camp_name'];
		$pl_c_url = $pl_row['camp_url'];
		$pl_c_id = $pl_row['camp_id'];
		
		// Any new private messages?
		//
		$pm_qry = "SELECT pm_id
					FROM pvt_msg
					WHERE surv_to = $player_id
					AND pm_viewed = 0";
		$pm_res = $db->query($pm_qry);
		$pm_num = $pm_res->num_rows;
		
		// Set a notification for new messages
		//
		$pm_alert = '';
		if ($pm_num > 0){
			$pm_alert = "<strong>($pm_num)</strong>";
		}
		else{}
	}
	else{}
	
	$root = 'http://fromashes.hostoi.com';
?>