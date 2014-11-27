<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

include ('connecttowrite.php');

$order_id = $_SESSION['order_id'];
$order_item_id = $_GET['order_item_id'];

// $outputtext .= $order_id;
// $outputtext .= " ";
// $outputtext .= $item_num;
// $outputtext .= " ";

if($order_id)
{
	$query_get_order = "
		SELECT * FROM Customer_Order 
		WHERE (order_id='$order_id')
		";
	$result_get_order = mysqli_query($dbc, $query_get_order);
	$order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC);
	//$item_array = unserialize($order_row['item_array']);
	
	$query_get_item = "
		SELECT * FROM Customer_Order_Item 
		WHERE (order_id='$order_id' AND order_item_id='$order_item_id')
		";
	$result_get_item = mysqli_query($dbc, $query_get_item);
	$item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC);
	
	$subtotal = $order_row['subtotal'] - $item_row['total_item_cost'];
	$tax = $subtotal*$order_row['tax_percent']/100;
	$total = $subtotal + $tax + $order_row['tip'];
	
	$query_delete_item = "
		DELETE FROM Customer_Order_Item 
		WHERE (order_id='$order_id' AND order_item_id='$order_item_id')
		";
	$result_delete_item = mysqli_query($dbc, $query_delete_item);
	
	$query_delete_group = "
		DELETE FROM Customer_Order_Item_Option_Group 
		WHERE (order_id='$order_id' AND order_item_id='$order_item_id')
		";
	$result_delete_group = mysqli_query($dbc, $query_delete_group);
	
	$query_delete_option = "
		DELETE FROM Customer_Order_Item_Option 
		WHERE (order_id='$order_id' AND order_item_id='$order_item_id')
		";
	$result_delete_option = mysqli_query($dbc, $query_delete_option);
	
	/* 
	unset($item_array[$item_num]);
	$item_array = array_values($item_array);
	
	$item = serialize($item_array);
	*/
	
	$query_get_items = "
		SELECT * FROM Customer_Order_Item 
		WHERE order_id='$order_id'
		";
	$result_get_items = mysqli_query($dbc, $query_get_items);
	
	if(@mysqli_num_rows($result_get_items) == 0)
	{
		$query_delete_order = "
			DELETE FROM Customer_Order 
			WHERE order_id='$order_id'
			";
		$result_delete_order = mysqli_query($dbc, $query_delete_order);
		
		$user_id = $_SESSION['user_id'];
		$query_update_customer = "
			UPDATE Customer 
			SET order_id='' 
			WHERE user_id='$user_id'
			";
		$result_update_customer = mysqli_query($dbc, $query_update_customer);
		$_SESSION['order_id'] = '';
		setcookie("order_id", false, time() + (60 * 15), '/', 'gottanom.com');
		
		$outputtext .= 0;
	}
	else
	{
		$last_edit = date("Y-m-d H:i:s");
		$query_update_order = "
			UPDATE Customer_Order 
			SET subtotal='$subtotal', tax='$tax', total='$total', last_edit='$last_edit' 
			WHERE order_id='$order_id'
			";
		$result_update_order = mysqli_query($dbc, $query_update_order);
		$outputtext .= $order_id;
		setcookie("order_id", $order_id, time() + (60 * 15), '/', 'gottanom.com');
	}
}
else
{
	$outputtext .= "There is nothing to delete.";
}

/// var_dump($error);
mysqli_close($dbc);

echo $outputtext;

?>