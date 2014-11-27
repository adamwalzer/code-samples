<?php

session_start();

$dont_set = array('user_id','username','password','salt','signin');

foreach($_POST as $key => $value)
{
	if(!in_array(key, $dont_set))
	{
		$_SESSION[$key] = $value;
		$outputtext .= '<variable name="'.$key.'">'.$_SESSION[$key].'</variable>';
	}
}

echo $outputtext;

?>