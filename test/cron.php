<?php
	//$db = new mysqli('localhost', 'root', '', 'ashes');
	$db = new mysqli('mysql10.000webhost.com', 'a8999301_ashes', 'Fry!Br34d', 'a8999301_ashes');

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}


/*
GENERATES A RANDOM ORDERING OF THE CAMPS
*/
$order_qry = "SELECT camp_id FROM camp
				WHERE camp_url != 'the_wastelands'
				ORDER BY RAND()";
				
$order_res = $db->query($order_qry);


/*
COUNTS SUPPORTERS AN ADDS A LEADER
*/
while($row=$order_res->fetch_assoc()){

	$supps_qry = "SELECT COUNT(ss.rec_surv_id) 'Count', sc.surv_id, sc.camp_id
				FROM survivor_camp sc
				JOIN supporters ss
					ON ss.rec_surv_id = sc.surv_id

				WHERE ss.give_surv_id IN
					(SELECT sp.give_surv_id FROM supporters sp
						JOIN survivor_camp cs
							ON cs.surv_id = sp.give_surv_id
						WHERE cs.camp_id = ".$row['camp_id'].")
				AND sc.camp_id = ".$row['camp_id']."
				GROUP BY sc.surv_id, sc.camp_id
				ORDER BY COUNT(ss.rec_surv_id) DESC LIMIT 1";

	$supps_res = $db->query($supps_qry);
		
	while($inRow = $supps_res->fetch_assoc()){
		
		$upd_qry = "UPDATE survivor_camp
						SET camp_leader = ".$inRow['camp_id']."
						WHERE surv_id = ".$inRow['surv_id']."
						AND camp_id = ".$row['camp_id'];
		$db->query($upd_qry);
		
		$upd_qry = "UPDATE survivor_camp
						SET camp_leader = 0
						WHERE surv_id != ".$inRow['surv_id']."
						AND camp_id = ".$row['camp_id'];
		$db->query($upd_qry);
	
	}
}

/*
DETERMINE POINTS BASED ON SKILLS
UPDATE IMPROVED SKILLS
*/

function get_points($surv_id, $sk1, $sk2, $sk3, $db){
	$sk_qry = "SELECT s.skill_id, s.skill_name, ss.skill_points
		FROM skill s
		JOIN surv_skill ss
			ON ss.skill_id = s.skill_id
		WHERE ss.surv_id = $surv_id
		AND s.skill_name IN ('$sk1','$sk2','$sk3')";
			
	$sk_res = $db->query($sk_qry);
	
		
	$sk1p = 0;
	$sk1_qry = '';
	$sk2p = 0;
	$sk2_qry = '';
	$sk3p = 0;
	$sk3_qry = '';
	
	$score = 0;
	$luck = 0;
	while ($row=$sk_res->fetch_assoc()){
	
		$point = floor($row['skill_points']/100);
		$score += $point;
		if ($row['skill_name'] == 'Luck'){
			$luck = round($row['skill_points']/100);
			$luck = rand(0,$luck);
			$score += $luck;
		}
		$point = 0;
		$luck = 0;
		
		//
		// Update skill points
		//
		$skill = $row['skill_points'];
		if ($row['skill_name'] == $sk1){
			$sk1p = $skill + 
					((500*pow(1.038,(($skill+1)/10))-475) -
					(500*pow(1.038,($skill/10))-475));
			$sk1_qry = "UPDATE surv_skill
							SET skill_points = ".round($sk1p)."
							WHERE skill_id = ".$row['skill_id']."
							AND surv_id = $surv_id";
		}
		elseif ($row['skill_name'] == $sk2){
			$sk2p = $skill + 
					((500*pow(1.029,(($skill+1)/10))-475) -
					(500*pow(1.029,($skill/10))-475));
			$sk2_qry = "UPDATE surv_skill
							SET skill_points = ".round($sk2p)."
							WHERE skill_id = ".$row['skill_id']."
							AND surv_id = $surv_id";
		}
		elseif ($row['skill_name'] == $sk3){
			$sk3p = $skill + 
					((500*pow(1.017,(($skill+1)/10))-475) -
					(500*pow(1.017,($skill/10))-475));
			$sk3_qry = "UPDATE surv_skill
							SET skill_points = ".round($sk3p)."
							WHERE skill_id = ".$row['skill_id']."
							AND surv_id = $surv_id";
		}
	}

	$db->query($sk1_qry);
	$db->query($sk2_qry);
	$db->query($sk3_qry);
	
	return $score;
}

/*
EXECUTE ACTIONS
*/

// Function to update SCAVENGE actions

function action($surv_id,$act_id,$db){

	$sk1 = '';
	$sk2 = '';
	$sk3 = '';
	if ($act_id == 1){
		$sk1 = 'Exploration';
		$sk2 = 'Sneaking';
		$sk3 = 'Luck';
	}
	elseif ($act_id == 2){
		$sk1 = 'Guns';
		$sk2 = 'Traps';
		$sk3 = 'Luck';
	}
	$score = get_points($surv_id, $sk1, $sk2, $sk3, $db);
	
	$item_qry = "SELECT item_id,item_odds
				FROM item
				WHERE item_action = $act_id";
	$item_res = $db->query($item_qry);


	$items = array();
	$find = array();
	while ($row = $item_res->fetch_assoc()){
		foreach (range(1,$row['item_odds']) as $odd){
			array_push($items, $row['item_id']);
		}
	}

	foreach (range(1,$score) as $range){
		$index = rand(0, count($items));
		$ins_qry = "INSERT INTO surv_item
						VALUES ($surv_id,$items[$index])";
		$db->query($ins_qry);
	}
}


// fuction to update HUNT actions 

$act_upd_qry = "UPDATE surv_action
					SET sa_time = sa_time - 1";
$db->query($act_upd_qry);

$act_qry = "SELECT surv_id, act_id
				FROM surv_action
				WHERE sa_time = 0";
$act_res = $db->query($act_qry);

$del_qry = "DELETE FROM surv_action
				WHERE sa_time = 0";
$db->query($del_qry);


while ($act = $act_res->fetch_assoc()){

	if ($act['act_id']){
		action($act['surv_id'],$act['act_id'],$db);
	}
}

?>