<?php
session_start();
include('../include/init.inc.php');
include('../include/functions.php');

$new = $player_id.$pl_s_url.$pl_s_created.$player_id;

echo $new;
echo "<br /><br />";
$key = md5($new);

setcookie("user", $pl_s_url, time()+360000);
setcookie("userKey", $key, time()+360000);
	
?>

