<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();
	
if($_SESSION['order_id']==0 || !$_SESSION['order_id'])
{
	$outputtext .= "New Order";
}
else
{
	$outputtext .= "Existing Order";
}

echo $outputtext;

?>