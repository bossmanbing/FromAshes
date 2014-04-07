<?php

// Converts timestamp from seconds to time since the action (seconds ago, minutes ago, etc)
//
function convertTime($string){
	$time = time()-$string;
	if ($time < 60){
		return $time.' seconds ago.';
	}
	else if ($time >= 60 && $time < 3600){
		$time = floor($time/60);
		return $time.' minutes ago.';
	}
	else if ($time >= 3600 && $time < 86400){
		$time = floor($time/3600);
		return $time.' hours ago.';
	}
	else if ($time >= 86400 && $time < 31536000){
		$time = floor($time/86400);
		return $time.' days ago.';
	}
	else if ($time >= 31536000){
		return 'Over a year ago.';
	}
	else{}
}

// Reverse of nl2br for textareas
//
FUNCTION br2nl($string){
RETURN PREG_REPLACE('#<br\s*?/?>#i', "\n", $string); 
}

function stripslashes_deep($value)
{
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);

    return $value;
}

?>