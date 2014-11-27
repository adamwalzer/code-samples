<?php

session_start();

$variable_names = array("user_id","username","email","first_name","last_name","del_address","del_city","del_state","del_zip","delivery_preference","bill_name","bill_address","bill_city","bill_state","bill_zip","phone","loc_id","rest_id","order_id","current_address","current_city","current_state","current_zip");

foreach($variable_names as $value)
{
	$outputtext .= '<variable name="'.$value.'">'.$_SESSION[$value].'</variable>';
}

echo $outputtext;

?>