<?php

session_start();

include "connect.php";

//$loc_id = $_GET['loc_id'];

$order_id = $_SESSION['order_id'];

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
   
	$outputtext .= "
		<div id='main'>
			<center>
		";
		
	while($order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC))
	{
		if($order_row['delivery'] == 'delivery')
		{
			$outputtext .= "
				<a href='javascript:popupEditOrder()' class='orderinfo'>
					Delivery Order Summary 
					<br>
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
				<a href='javascript:popupEditOrder()' class='orderinfo'>Pickup Order Summary</a>
				";
		}
		
		
		
		$outputtext .= "
			<br>
			<br>
			<table>
			";
		
		$query_get_item = "
			SELECT * FROM Customer_Order_Item 
			WHERE order_id='$order_id' 
			ORDER BY order_item_id
			";
		$result_get_item = mysqli_query($dbc, $query_get_item);
		
		while($item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC))
		{
			
			$encodedname = rawUrlEncode($item_row['item_name']);
			
			$outputtext .= $item_row['quantity'];
			$outputtext .= " ";
			if($item_row['size_name'])
			{
				$outputtext .= $item_row['size_name']." ";
			}
			$outputtext .= $item_row['item_name'];
			$outputtext .= " $";
			$outputtext .= $item_row['total_item_cost'];
			$outputtext .= "<br>";
			$outputtext .= "<a href='javascript:editOrderItemPopup(".$item_row['order_item_id'].',"'.$encodedname.'"'.")'>edit</a> &nbsp;";
			$outputtext .= "<a href='javascript:deleteItem(".$item_row['order_item_id'].',"'.$order_row['loc_id'].'",'.$order_row['rest_id'].")'>delete</a>";
			$outputtext .= "<br>";
			$outputtext .= "<br>";
			
			$i++;
		}
		
		$outputtext .= "
			</table>
			";
		
		$outputtext .= "
				subtotal: $".$order_row['subtotal']."
				<br>
				";
				
		if($order_row['delivery']=='delivery' && $order_row['subtotal'] < $order_row['delivery_minimum'])
		{
			$tominimum = $order_row['delivery_minimum'] - $order_row['subtotal'];
			
			$outputtext .= " 
				$".$tominimum." to delivery minimum
				<br>
				";
		}
		
		if($order_row['delivery']=='pickup' && $order_row['subtotal'] < $order_row['pickup_minimum'])
		{
			$tominimum = $order_row['pickup_minimum'] - $order_row['subtotal'];
			
			$outputtext .= " 
				$".$tominimum." to order minimum
				<br>
				";
		}
		
		$outputtext .= "
				tax: $".$order_row['tax']."
				";
				
		if($order_row['delivery'] == 'delivery')
		{
			$outputtext .= "
				<br>
				delivery fee: ";
				if($order_row['delivery_fee']==0)
				{
					$outputtext .= "free
						";
				}
				else
				{
					$outputtext .= "$".$order_row['delivery_fee']."
					";
				}
		}
				
		$outputtext .= "
					<br>
					<a href='javascript:mobileEditOrderTipPopup()' class='orderinfo'>tip: $".$order_row['tip']."</a>
					<br>
					total: $".$order_row['total']."
					<br>
					<br>
					<input type='button' id='stylebutton' onClick='reviewOrder()' name='Review Order' value='Review Order' />
					<br>
					<a href='javascript:popupDeleteOrder()'>delete order</a>
				</center>
			</div>
			";
	}
}
else
{ 
	$outputtext .= "No Categories In Database";
}

//mysqli_close($dbc);

?>