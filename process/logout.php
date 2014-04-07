<?php
//
// Log out and redirect to home page.
//
session_start();
session_destroy();

setcookie("user", "", time()- 300,"/");
setcookie("userKey", "", time()- 300,"/");
	
header('Location:/');
echo $root;
?>