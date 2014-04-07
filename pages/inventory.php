<?php
session_start();
include('../include/init.inc.php');
include('../include/functions.php');

if (!isset($_SESSION['online'])){
	header("Location:/home/");
}
else{}

$item_qry = "SELECT COUNT(si.item_id) AS 'count', i.item_name, i.item_weight
				FROM surv_item si
				JOIN item i
					ON si.item_id = i.item_id
				WHERE si.surv_id = $player_id
				GROUP BY i.item_name";
				
$item_res = $db->query($item_qry);
?>
<!DOCTYPE html>

<?php include('../include/head.inc.php'); ?>
	<title>From Ashes | Inventory</title>
</head>

<?php include('../include/top.inc.php'); ?>

	<hr />
	<div id='content'>
		<?php include('../include/control.inc.php'); ?>
		
		<div id='page-content'>
			<h2 id='surv_name'><?php echo $pl_s_name; ?></h2>

			
			<h3>Your Inventory</h3>
			
		<?php
		if($item_res->num_rows == 0){
			echo "You don't have any items yet.";
		}
		else{
			echo "Item  -  Count  -  Weight <br /><br />";
			while ($row = $item_res->fetch_assoc()){
				echo $row['item_name']." - ".$row['count']." - ".(($row['count']*$row['item_weight'])/10)."<br />";
			}
		
		}
		?>
		</div>
		
<?php include('../include/footer.inc.php'); ?>

</body>
</html>