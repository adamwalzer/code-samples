<?php

session_start();

include "connect.php";

//$loc_id = $_GET['loc_id'];

$order_id = $_SESSION['order_id'];
$loc_id = $_SESSION['loc_id'];
$rest_id = $_SESSION['rest_id'];

$query_get_order = "
	SELECT * FROM Customer_Order 
	WHERE order_id = '$order_id'
	";

$result_get_order = mysqli_query($dbc, $query_get_order);
if(!$result_get_order)
{//If the QUery Failed 
	$outputtext .= 'Query Failed ';
}

if (@mysqli_num_rows($result_get_order) > 0)//if Query is successfull 
{ // A match was made.
	//$_SESSION = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
		
	while($order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC))
	{	
		
		if($order_row['delivery'] == 'delivery')
		{
			$outputtext .= "
				<a href='javascript:editOrderPlaceOrderPopup()' class='orderinfo'>
					<h3>Order Info</h3>
					".$order_row['first_name']." ".$order_row['last_name']."
					<br>
					".$order_row['email']."
					<br>
					".$order_row['phone']."
					<br>
					<br>
					<h3>Delivery Address</h3>
					";
   
		   if($order_row['del_on_beach']=='checked')
		   {
				$outputtext .= "On The Beach At ";
		   }
   
		   $outputtext .= $order_row['del_address']."
					<br>
					".$order_row['del_city'].", ".$order_row['del_state']." ".$order_row['del_zip']."
				</a>
				";
		}
		elseif($order_row['delivery'] == 'pickup')
		{
			$outputtext .= "
				<a href='javascript:editOrderPlaceOrderPopup()' class='orderinfo'>
					<h3>Order Info</h3>
					".$order_row['first_name']." ".$order_row['last_name']."
					<br>
					".$order_row['email']."
					<br>
					".$order_row['phone']."
					<br>
					Pickup Order
				</a>
				";
		}
	}
}
else
{ 
	$outputtext .= "No Categories In Database";
}

mysqli_close($dbc);

?>