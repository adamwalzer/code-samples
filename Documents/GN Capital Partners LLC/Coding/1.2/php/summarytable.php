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
		$outputtext .= "
			<table id='fullorder' border='0'>
				<colgroup>
					<col width='7%'>
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
					<col width='26%'>
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
				if($option_row['option_quantity'])
				{
					if($options == '')
					{
						$options = $option_row['option_quantity']." ".$option_row['option_name'];
					}
					else
					{
						$options .= ',<br>'.$option_row['option_quantity']." ".$option_row['option_name'];
					}
				}
				elseif($option_row['checked'] == 'checked')
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
							<i onClick='editOrderItemPlaceOrderPopup(".$item_row['order_item_id'].',"'.$encodedname.'"'.")' class='icon-pencil'></i>
							<i onClick='deleteItemPlaceOrderPopup(".$item_row['order_item_id'].")' class='icon-cancel'></i>
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
		
		if($order_row['delivery']=='pickup' && $order_row['subtotal'] < $order_row['pickup_minimum'])
		{
			$tominimum = $order_row['pickup_minimum'] - $order_row['subtotal'];
			
			$outputtext .= " 
				<tr>
					<td colspan='7' class='money'>
						$".$tominimum." to order minimum
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
			</table>
			";
	}
}
else
{ 
	$outputtext .= "No Categories In Database";
}

mysqli_close($dbc);

?>