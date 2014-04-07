<?php
session_start();
include('../include/init.inc.php');
include('../include/functions.php');


$item_qry = "SELECT item_id, item_name, item_weight, item_odds
				FROM item";
$item_res = $db->query($item_qry);


$items = array();
$find = array();
while ($row = $item_res->fetch_assoc()){
	foreach (range(1,$row['item_odds']) as $odd){
		array_push($items, $row['item_name']);
	}
}

foreach (range(1,5) as $range){
	$index = rand(0, count($items));
	array_push($find, $items[$index]);
}
print_r($find);

?>

