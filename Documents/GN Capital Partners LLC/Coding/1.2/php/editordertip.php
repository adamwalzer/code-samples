<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

include ('connecttowrite.php');

$tip = $_POST['tip'];

if($_SESSION['order_id']!=0 && $_SESSION['order_id'])
{
	$order_id = $_SESSION['order_id'];
	
	$query_get_order = "SELECT * FROM Customer_Order WHERE order_id='$order_id'";
	$result_get_order = mysqli_query($dbc, $query_get_order);
	$order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC);
	
	if($order_row['delivery']=='delivery')
	{
		$total = $order_row['subtotal'] + $order_row['tax'] + $order_row['delivery_fee'] + $tip;
	}
	else
	{
		$total = $order_row['subtotal'] + $order_row['tax'] + $tip;
	}
	
	$last_edit = date("Y-m-d H:i:s");
	
	$query_update_customer = "
		UPDATE Customer_Order 
		SET total='$total', tip='$tip', last_edit='$last_edit' 
		WHERE order_id='$order_id'
		";
	$result_update_customer = mysqli_query($dbc, $query_update_customer);
	
	$outputtext .= "
		<div>
			<script>
				executePage('leftorderdiv');
			</script>
		</div>
		";
}
else
{
	$outputtext .= "This order has not been started.";
}

/// var_dump($error);
mysqli_close($dbc);

?>