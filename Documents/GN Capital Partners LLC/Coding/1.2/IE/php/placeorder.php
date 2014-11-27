<?php

	session_start();
	
	include "connecttowrite.php";
	
	//require_once 'AuthorizeNet.php'; // Make sure this path is correct.
	//$transaction = new AuthorizeNetAIM('9p5nJ37UvTZX', '4V4h8M6h87UsM353');
	
	$order_id = $_SESSION['order_id'];
	$user_id = $_SESSION['user_id'];
	
	$query_get_order = "
		SELECT * FROM Customer_Order 
		WHERE order_id = '$order_id'
		";
	$result_get_order = mysqli_query($dbc, $query_get_order);
	$order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC);

	$delivery = $order_row['delivery'];
	$email = $order_row['email'];
	$phone = $order_row['phone'];
	$first_name = $order_row['first_name'];
	$last_name = $order_row['last_name'];
	$del_address = $order_row['del_address'];
	$del_city = $order_row['del_city'];
	$del_state = $order_row['del_state'];
	$del_zip = $order_row['del_zip'];
	 
	$rest_id = $order_row['rest_id'];
	$day_num = date("w");
	$time = date("H:i:s", time());

	$query_get_open = "
		SELECT * FROM Hours 
		WHERE ( rest_id='$rest_id' AND ( ( day_num='$day_num' AND open<='$time' AND ( close>'$time' OR ( close_day_num>'$day_num' OR ( day_num='6' AND close_day_num='0' ) ) ) ) OR ( ( day_num<'$day_num' OR ( day_num='6' AND close_day_num='0' ) ) AND ( close_day_num='$day_num' AND close>'$time' ) ) ) )
		";
	$result_get_open = mysqli_query($dbc, $query_get_open);

	if(@mysqli_num_rows($result_get_open) == 0)
	{
		$outputtext = "Your order could not be placed because the restaurant is currently closed.<br/>";
	}
	else
	{
		$card_type = $_POST['card_type'];
		$place_date = date("Y-m-d H:i:s");
	
		if($_POST['stripeToken'])
		{
			$last_card_digits = $_POST['last_card_digits'];
		}
		else
		{
			$pattern = '/[^0-9]*/';
			$card_number = preg_replace($pattern,'',$_POST['card_number']);
			$last_card_digits = substr($card_number, -4);
			$confirm_number = $_POST['confirm_number'];
			$exp_date = $_POST['exp_date'];
	
			if($card_type == 'Amex' || $card_type == 'American Express')
			{
				$card_number = substr($card_number, 0, 4) . ' ' . substr($card_number, 4, 6) . ' ' . substr($card_number, 10);
			}
			else
			{
				$card_number = chunk_split($card_number, 4, ' ');
			}	
		}
	
		$query_update_customer = "
			UPDATE Customer_Order 
			SET first_name='$first_name', last_name='$last_name', phone='$phone', email='$email', delivery='$delivery', del_address='$del_address', del_city='$del_city', del_state='$del_state', del_zip='$del_zip', place_date='$place_date', status='placed' 
			WHERE order_id='$order_id'
			";
		$result_update_customer = mysqli_query($dbc, $query_update_customer);
		
		$filename = $order_row['order_id'].".html";
	
		$query_get_item = "
			SELECT * FROM Customer_Order_Item 
			WHERE order_id = '$order_id'
			";
		$result_get_item = mysqli_query($dbc, $query_get_item);
	
		$disabled_items = array();
		while($item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC))
		{	
			$cat_id = $item_row['cat_id'];
			$query_get_disable = "
				SELECT * FROM Menu_Category_Disable_Hours 
				WHERE ( rest_id='$rest_id' AND cat_id='$cat_id' AND ( ( day_num='$day_num' AND start<='$time' AND ( stop>'$time' OR ( stop_day_num>'$day_num' OR ( day_num='6' AND stop_day_num='0' ) ) ) ) OR ( ( day_num<'$day_num' OR ( day_num='6' AND stop_day_num='0' ) ) AND ( stop_day_num='$day_num' AND stop>'$time' ) ) ) )
				";
			$result_get_disable = mysqli_query($dbc, $query_get_disable);
			if(@mysqli_num_rows($result_get_disable) > 0)
			{
				$disabled_items[] = $item_row['item_name'];
			}
		}
	
		if(count($disabled_items)>0)
		{
			$outputtext = "Your order could not be placed because the following items are not available at this time:
				<br>
				<br>
				";
		
			for($i=0;$i<count($disabled_items);$i++)
			{
				$outputtext .= $disabled_items[$i]."<br>";
			}
		}
		else
		{
			$filename = $order_row['order_id'].".html";
		
			$query_get_rest = "
				SELECT * FROM Restaurant 
				WHERE rest_id = '$rest_id'
				";
			$result_get_rest = mysqli_query($dbc, $query_get_rest);
			$rest_row = mysqli_fetch_array($result_get_rest, MYSQLI_ASSOC);
	
			$header_table_for_customer = "
				<table id='fullorder' width='100%'>
					<colgroup>
						<col width='100%'>
					</colgroup>
					<tr>
						<td>
							Your Order From ".$rest_row['name']."
						</td>
					</td>
				</table>
				<br>
				";
		
			$header_table_for_restaurant = "
				<table id='fullorder' width='100%'>
					<colgroup>
						<col width='100%'>
					</colgroup>
					<tr>
						<td align='center'>
							Incoming Gottanom Order
						</td>
					</tr>
				</table>
				<br>
				";
	
			if ($order_row['delivery'] == 'delivery')
			{
				$delivery_address = "
					Order ID: ".$order_row['order_id']."
					<br>
					".$place_date."
					<br>
					Delivery Address
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
					$delivery_address .= "On The Beach At ";
				}
					
				$delivery_address .= $order_row['del_address']."
					<br>
					".$order_row['del_city'].", ".$order_row['del_state']." ".$order_row['del_zip']."
				";
			}
			else
			{
				$delivery_address = "
					Order ID: ".$order_row['order_id']."
					<br>
					".$place_date."
					<br>
					Pickup Order
					<br>
					".$order_row['first_name']." ".$order_row['last_name']."
					<br>
					".$order_row['phone']."
					<br>
					".$order_row['email']."
					";
			}
			
			$billing_address_for_customer = "
					Billing Info
					<br/>
					".$card_type." ending in ".$last_card_digits."
					<br/>
					$".number_format($order_row['subtotal']<50?$order_row['subtotal']*.01:.50,2)." was donated from this order.
				";
			
			if($_POST['stripeToken'])
			{
				$billing_address_for_restaurant = $billing_address_for_customer;
			}
			else
			{
				$billing_address_for_restaurant = "
						Billing Info
						<br>
						Card Type: ".$card_type."
						<br>
						Card Number: ".$card_number."
						<br>
						Exp. Date: ".$exp_date."
					";
			}
		
			$address_table_for_customer = "
				<table id='fullorder' width='100%'>
					<colgroup>
						<col width='50%'>
					</colgroup>
					<colgroup>
						<col width='50%'>
					</colgroup>
					<tr>
						<td>
							".$delivery_address."
						</td>
						<td>
							".$billing_address_for_customer."
						</td>
					</td>
				</table>
				<br>
				";
		
			$address_table_for_restaurant = "
				<table id='fullorder' width='100%'>
					<colgroup>
						<col width='50%'>
					</colgroup>
					<colgroup>
						<col width='50%'>
					</colgroup>
					<tr>
						<td>
							".$delivery_address."
						</td>
						<td>
							".$billing_address_for_restaurant."
						</td>
					</tr>
				</table>
				<br>
				";
	
			$summary_table = "
				<table id='fullorder' width='100%' border='1'>
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
						<col width='30%'>
					</colgroup>
					<colgroup>
						<col width='10%'>
					</colgroup>
					<tr>
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
			
					$summary_table .= "
						<tr>
							<td align='center'>
								".$item_row['quantity']."
							</td>
							<td align='center'>
								".$item_row['size_name']."
							</td>
							<td align='center'>
								".$item_row['cat_name']."-->".$item_row['subcat_name']."-->".$item_row['item_name']."
							</td>
							<td align='center'>
								".$options."
							</td>
							<td align='center'>
								".$item_row['notes']."
							</td>
							<td align='right'>
								$".$item_row['total_item_cost']."
							</td>
						</tr>
						";
				}
		
				$summary_table .= "
						<tr>
							<td colspan='5' align='right'>
								subtotal:
							</td>
							<td align='right'>
								$".$order_row['subtotal']."
							</td>
						</tr>
						<tr>
							<td colspan='5' align='right'>
								tax:
							</td>
							<td align='right'>
								$".$order_row['tax']."
							</td>
						</tr>
						";
				
				if($order_row['delivery'] == 'delivery')
				{
					$summary_table .= "
						<tr>
							<td colspan='5' align='right'>
								delivery fee:
							</td>
							<td align='right'>
								$".$order_row['delivery_fee']."
							</td>
						</tr>
						";
				}
				
				$summary_table .= "
						<tr>
							<td colspan='5' align='right'>
								tip:
							</td>
							<td align='right'>
								$".$order_row['tip']."
							</td>
						</tr>
						<tr>
							<td colspan='5' align='right'>
								total:
							</td>
							<td align='right'>
								$".$order_row['total']."
							</td>
						</tr>
					</table>
					<br>
					";
			
			$separator = '-----'.md5(time()).'-----';
	
			$message_for_customer = "<html><body>" . $header_table_for_customer . $address_table_for_customer . $summary_table . "</body></html>";
	
			$headers_for_customer = "MIME-Version: 1.0\r\n";
			$headers_for_customer .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$headers_for_customer .= "From: noreply@gottanom.com";
			
			$headers_for_restaurant .= "From: restaurant@gottanom.com\r\n";
			$headers_for_restaurant .= "Content-Type: multipart/mixed; boundary=\"$separator\"";
	
			$message_for_restaurant = "<html><body>" . $header_table_for_restaurant . $address_table_for_restaurant . $summary_table . "</body></html>";
			
			$attachment = chunk_split(base64_encode($message_for_restaurant));
			
			$body_for_restaurant = "--$separator\r\n"
				. "Content-Type: text/html\r\n"
				. "Content-Transfer-Encoding: base64\r\n"
				. "Content-Disposition: attachment; filename=\"$filename\"\r\n"
				. "\r\n"
				. "$attachment\r\n"
				. "--$separator--";
		
			if($rest_row['fax'])
			{
				$fax = $rest_row['fax'].'@rcfax.com';
			}
			else
			{
				$fax = $rest_row['email'];
			}
			
			$error = '';
			$success = 1;
			
			if($_POST['stripeToken'])
			{
				require 'lib/Stripe.php';
		
				Stripe::setApiKey("sk_test_GeF0xwnbuvJqhqsLfMavlCDP");
				$error = '';
				$success = '';
				try {
					if (!isset($_POST['stripeToken']))
					throw new Exception("The Stripe Token was not generated correctly");
					Stripe_Charge::create(array("amount" => $order_row['total']*100,
												"currency" => "usd",
												"card" => $_POST['stripeToken']));
					$success = 1;
				}
				catch (Exception $e) {
					$error = $e->getMessage();
				}
			}
			
			if($error === '' && $success === 1)
			{			
				$mail_for_customer = mail($email, 'Your Gottanom Order From '.$rest_row['name'], $message_for_customer, $headers_for_customer);
				$mail_for_restaurant = mail($fax, '', $body_for_restaurant, $headers_for_restaurant);
				
				if($PhoneNumbers=$rest_row['call_in_order'])
				{
					include "callinorder.php";
				}
			}
			else
			{
				$outputtext .= "Your order could not be placed because there was an error processing your payment.<br/>";
			}

			if ($mail_for_customer && $mail_for_restaurant && $outputtext == '')
			{
				//echo "<h1>Success! The test credit card has been charged!</h1>";
				//echo "Transaction ID: " . $response->transaction_id;
				$query_update_customer = "UPDATE Customer SET rest_id=0, order_id=0 WHERE user_id='$user_id'";
				$result_update_customer = mysqli_query($dbc, $query_update_customer);
				$_SESSION['order_id'] = 0;
				$_SESSION['rest_id'] = 0;
				
				$outputtext .= "Your order has been placed!";
				
				$outputtext = "
					<action type='popup' name='place_order' title='Success!'>
						<text>
							".$outputtext."
						</text>
						<button>
							{ text: 'Awesome', click: function() { location.reload(); } }
						</button>
					</action>
					";
			}
			else
			{
				//echo $response->error_message;
				$outputtext .= "Unfortunately, we were unable to place your order. Please try again later.";
				//$outputtext .= " result: ".$faxResult;
				
				$outputtext = "
					<action type='popup' name='place_order' title='Order Status'>
						<text>
							".$outputtext."
						</text>
						<button>
							{ text: 'Alright', click: function() { $( this ).dialog( 'close' ); } }
						</button>
					</action>
					";
			}
	
		}
		
	}
	
	//session_write_close();
	echo $outputtext;
?>