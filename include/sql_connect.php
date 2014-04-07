<?php

$db = new mysqli('localhost', 'root', '', 'ashes');
//$db = new mysqli('mysql10.000webhost.com', 'a8999301_ashes', 'Fry!Br34d', 'a8999301_ashes');

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}
?>