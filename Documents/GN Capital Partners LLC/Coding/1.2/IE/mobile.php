<?php

if(!$_SESSION)
{
	session_start();
}

if($_SESSION['loc_id'])
{
	$loc_id = $_SESSION['loc_id'];
}
else if($_GET['loc_id'])
{
	$loc_id = $_GET['loc_id'];
}
else
{
	$loc_id = "ac_area";
}

if($_SESSION['rest_id'])
{
	$rest_id = $_SESSION['rest_id'];
}
else if($_GET['rest_id'])
{
	$rest_id = $_GET['rest_id'];
}
else
{
	$rest_id = 0;
}

if($_SESSION['order_id'])
{
	$order_id = $_SESSION['order_id'];
	
	/*
	include ('connecttowrite.php');
	$query_get_order = "SELECT * FROM Customer_Order WHERE (order_id='$order_id')";
	$result_get_order = mysqli_query($dbc, $query_get_order);
	$order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC);
	$last_edit = strtotime($order_row['last_edit']);
	$now15 = strtotime(date('Y-m-d H:i:s',strtotime('-15 minutes')));
	$diff = $now15 - $last_edit;
	//$outputtext .= 'now: '.$now15;
	//$outputtext .= 'last_edit: '.$last_edit;
	//$outputtext .= 'diff: '.$diff;

	if($diff > 0)
	{
		//$outputtext .= 'more than 15';
		$order_id = 0;
		$_SESSION['order_id'] = 0;
		$query_update_customer = "UPDATE Customer SET order_id=0 WHERE user_id='$user_id'";
		$result_update_customer = mysqli_query($dbc, $query_update_customer);
	}
	*/
	
	//$outputtext .= "order_id1:".$order_id;
}
elseif($_GET['order_id'])
{
	$order_id = $_GET['order_id'];
}
else
{
	$order_id = 0;
}

$outputtext .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /> <!--320-->';

$outputtext .= "<html>\n";

	include "php/mobilehead.php";

	$outputtext .= "<body>\n";

		if($_SESSION['username'])
		{
			include "php/topmobilewelcome.php";
		}
		else
		{
			include "php/topmobilenav.php";
		}

	$outputtext .= "</body>\n";

$outputtext .= "</html>\n";

echo $outputtext;

?>