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
   
	$outputtext .= "
		<div id='main'>
		";
		
	while($order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC))
	{
		if($order_row['delivery'] == 'delivery')
		{
			$outputtext .= "
				<a href='javascript:popupEditOrder()' class='orderinfo'>
					Delivery Order
					<br>
					".$order_row['first_name']." ".$order_row['last_name']."
					<br>
					".$order_row['phone']."
					<br>
					".$order_row['email']."
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
				<a href='javascript:popupEditOrder()' class='orderinfo'>Pickup Order</a>
				<br>
				".$order_row['first_name']." ".$order_row['last_name']."
				<br>
				".$order_row['phone']."
				<br>
				".$order_row['email'];
		}
		
		$outputtext .= "
			<br>
			<br>
			<a href='".'javascript:setMain("restaurantpage.php?loc_id='.$loc_id.'&rest_id='.$rest_id.'");showSide()'."' class='orderinfo'>Back To Menu</a>
			<br>
			<br>
			<table id='fullorder'>
				<colgroup>
					<col width='5%'>
				</colgroup>
				<colgroup>
					<col width='5%'>
				</colgroup>
				<colgroup>
					<col width='15%'>
				</colgroup>
				<colgroup>
					<col width='20%'>
				</colgroup>
				<colgroup>
					<col width='20%'>
				</colgroup>
				<colgroup>
					<col width='28%'>
				</colgroup>
				<colgroup>
					<col width='7%'>
				</colgroup>
				<tr>
					<th>
						&nbsp;
					</th>
					<th>
						Qty
					</th>
					<th>
						Size
					</th>
					<th>
						Item
					</th>
					<th>
						Options
					</th>
					<th>
						Notes
					</th>
					<th>
						Price
					</th>
				</tr>
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
			$order_item_id = $item_row['order_item_id'];
			
			$query_get_option = "
				SELECT * FROM Customer_Order_Item_Option 
				WHERE order_id='$order_id' AND order_item_id='$order_item_id' 
				ORDER BY option_order
				";
			$result_get_option = mysqli_query($dbc, $query_get_option);
			$options = '';
			while($option_row = mysqli_fetch_array($result_get_option, MYSQLI_ASSOC))
			{
				if($option_row['checked'] == 'checked')
				{
					if($options == '')
					{
						$options = $option_row['option_name'];
					}
					else
					{
						$options .= ',<br>'.$option_row['option_name'];
					}
				}
			}
			
			$outputtext .= "
					<tr>
						<td>
							<i onClick='editOrderItemViewFullOrderPopup(".$item_row['order_item_id'].',"'.$encodedname.'"'.")' class='icon-pencil'></i>
							<i onClick='deleteItemViewFullOrderPopup(".$item_row['order_item_id'].")' class='icon-cancel'></i>
						</td>
						<td>
							".$item_row['quantity']."
						</td>
						<td>
							".$item_row['size_name']."
						</td>
						<td>
							".$item_row['item_name']."
						</td>
						<td>
							".$options."
						</td>
						<td>
							".$item_row['notes']."
						</td>
						<td class='money'>
							$".$item_row['total_item_cost']."
						</td>
					</tr>
				";
		}
		
		$outputtext .= "
				<tr>
					<td colspan='6' class='money'>
						subtotal:
					</td>
					<td class='money'>
						$".$order_row['subtotal']."
					</td>
				</tr>
				";
					
		if($order_row['delivery']=='delivery' && $order_row['subtotal'] < $order_row['delivery_minimum'])
		{
			$tominimum = $order_row['delivery_minimum'] - $order_row['subtotal'];
			
			$outputtext .= " 
				<tr>
					<td colspan='7' class='money'>
						$".$tominimum." to delivery minimum
					</td>
				</tr>
				";
		}
		
		$outputtext .= "
				<tr>
					<td colspan='6' class='money'>
						tax:
					</td>
					<td class='money'>
						$".$order_row['tax']."
					</td>
				</tr>
				";
				
		if($order_row['delivery'] == 'delivery')
		{
			$outputtext .= "
				<tr>
					<td colspan='6' class='money'>
						delivery fee:
					</td>
					<td class='money'>
						$".$order_row['delivery_fee']."
					</td>
				</tr>
				";
		}
				
		$outputtext .= "
				<tr>
					<td colspan='6' class='money'>
						tip:
					</td>
					<td class='money'>
						$".$order_row['tip']."
					</td>
				</tr>
				<tr>
					<td colspan='6' class='money'>
						total:
					</td>
					<td class='money'>
						$".$order_row['total']."
					</td>
				</tr>
				<tr>
					<td colspan='7'>
						<input type='button' id='stylebutton' onClick='setMain(".'"placeorderpage.php"'.")' name='Review Order' value='Review Order' />
					</td>
				</tr>
				<tr>
					<td colspan='7'>
						<a href='javascript:popupDeleteOrder()'>delete order</a>
					</td>
				</tr>
			</div>
			";
			
		$outputtext .= "
			</table>
			";
	}
}
else
{ 
	$outputtext .= "No Categories In Database";
}

//mysqli_close($dbc);

?>