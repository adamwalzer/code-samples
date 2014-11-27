<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

include ('connecttowrite.php');

if($order_data=$_POST)
{
	if($_SESSION['order_id']!=0 && $_SESSION['order_id'])
	{
		$order_id = $_SESSION['order_id'];
		
		setcookie("order_id", $order_id, time() + (60 * 15), '/', 'gottanom.com');
		
		$delivery = $order_data['delivery'];
		$email = $order_data['email'];
		$phone = $order_data['phone_number'];
		$first_name = $order_data['first_name'];
		$last_name = $order_data['last_name'];
		if($order_data['current_on_beach']=='true')
		{
			$del_on_beach = 'checked';
		}
		else
		{
			$del_on_beach = '';
		}
		$del_address = $order_data['address'];
		$del_city = $order_data['city'];
		$del_state = $order_data['state'];
		$del_zip = $order_data['zip'];
		
		$query_get_order = "
			SELECT * FROM Customer_Order 
			WHERE order_id='$order_id'
			";
		$result_get_order = mysqli_query($dbc, $query_get_order);
		$order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC);
		$rest_id = $order_row['rest_id'];
		
		$_SESSION['current_address'] = $del_address;
		$_SESSION['current_city'] = $del_city;
		$_SESSION['current_state'] = $del_state;
		$_SESSION['current_zip'] = $del_zip;
		
		if($del_city || $del_zip)
		{
			$query_get_delivers = "
				SELECT * FROM Delivery_Info 
				WHERE (rest_id='$rest_id' AND (city='$del_city' OR zip='$del_zip')) 
				LIMIT 1
				";
			$result_get_delivers = mysqli_query($dbc, $query_get_delivers);
			if (@mysqli_num_rows($result_get_delivers) == 1)
			{
				$delivers_row = mysqli_fetch_array($result_get_delivers, MYSQLI_ASSOC);
				$delivery_minimum = $delivers_row['delivery_minimum'];
				$delivery_fee = $delivers_row['delivery_fee'];
			}
			elseif($delivery=='delivery')
			{
				$outputtext .= "This restaurant does not deliver to this address.";
				$delivery = 'pickup';
			}
		}
		else
		{
			$outputtext .= "You must set a full address.";
			$delivery_minimum = $order_row['delivery_minimum'];
			$delivery_fee = $order_row['delivery_fee'];
		}
		
		if($delivery=='delivery')
		{
			$total = $order_row['subtotal'] + $order_row['tax'] + $delivery_fee + $order_row['tip'];
		}
		else
		{
			$total = $order_row['subtotal'] + $order_row['tax'] + $order_row['tip'];
		}
		
		$last_edit = date("Y-m-d H:i:s");
		
		$query_update_customer = "
			UPDATE Customer_Order 
			SET total='$total', first_name='$first_name', last_name='$last_name', phone='$phone', email='$email', delivery='$delivery', delivery_fee='$delivery_fee', delivery_minimum='$delivery_minimum', del_on_beach='$del_on_beach', del_address='$del_address', del_city='$del_city', del_state='$del_state', del_zip='$del_zip', last_edit='$last_edit' 
			WHERE order_id='$order_id'
			";
		$result_update_customer = mysqli_query($dbc, $query_update_customer);
		
		//$outputtext .= $delivery;
		//$outputtext .= $order_id;
	}
	else
	{
		$outputtext .= "This order has not been started.";
	}
}
else
{
	$outputtext .= "There is no order data.";
}

/// var_dump($error);
mysqli_close($dbc);

if($outputtext)
{
	$outputtext = "
		<div type='popup' name='continue_order' title='Continue Order?'>
			<button>
				{ text: 'Continue As Pickup Order', click: function() { executePage('leftorderdiv');popup['continue_order'].dialog('close'); } }, { text: 'Delete Order', click: function() { executePage('deleteorder');popup['continue_order'].dialog('close'); } }
			</button>
			<text>
				".$outputtext."
			</text>
		</div>
		";
}
else
{
	$outputtext = "
		<div>
			<script>
				executePage('revieworderdiv');
				popup['edit_order'].dialog('close');
			</script>
		</div>
		";
}

?>