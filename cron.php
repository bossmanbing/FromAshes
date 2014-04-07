<?php
	//$db = new mysqli('localhost', 'root', '', 'ashes');
	$db = new mysqli('mysql10.000webhost.com', 'a8999301_ashes', 'Fry!Br34d', 'a8999301_ashes');

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}
$time = date("Y-m-d H:i:s");
$ud_qry = "INSERT INTO updates
			(update_time)
			VALUES ('$time')";
$db->query($ud_qry);

$order_qry = "SELECT camp_id FROM camp
				WHERE camp_url != 'the_wastelands'
				ORDER BY RAND()";
				
$order_res = $db->query($order_qry);

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
						SET camp_leader = ".$row['camp_id']."
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

?>