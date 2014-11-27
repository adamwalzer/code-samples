<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

$_SESSION['input'] = $_POST['input'];

if($_SESSION['order_id']==0 || !$_SESSION['order_id'])
{
	if($_SESSION['user_id'])
	{
		include "startneworderpopup.php";
	}
	else
	{
		include "loginpopup.php";
	}
}
else
{
	include "additemtoorder.php";
}

?>