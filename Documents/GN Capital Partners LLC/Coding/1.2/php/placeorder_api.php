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
	
	if($_POST['delivery'])
	{
		$delivery = $_POST['delivery'];
		$email = $_POST['email'];
		$phone = $_POST['phonenumber'];
		$first_name = $_POST['firstname'];
		$last_name = $_POST['lastname'];
		$del_address = $_POST['deliveryaddress'];
		$del_city = $_POST['deliverycity'];
		$del_state = $_POST['deliverystate'];
		$del_zip = $_POST['deliveryzipcode'];
	}
	else
	{
		$delivery = $order_row['delivery'];
		$email = $order_row['email'];
		$phone = $order_row['phone'];
		$first_name = $order_row['first_name'];
		$last_name = $order_row['last_name'];
		$del_address = $order_row['del_address'];
		$del_city = $order_row['del_city'];
		$del_state = $order_row['del_state'];
		$del_zip = $order_row['del_zip'];
	}
	
	$name_on_card = $_POST['nameoncard'];
	$bill_address = $_POST['billaddress'];
	$bill_city = $_POST['billcity'];
	$bill_state = $_POST['billstate'];
	$bill_zip = $_POST['billzipcode'];
	$card_type = $_POST['cardtype'];
	$pattern = '/[^0-9]*/';
	$card_number = preg_replace($pattern,'',$_POST['cardnumber']);
	$last_card_digits = substr($card_number, -4);
	$confirm_number = $_POST['confirmnumber'];
	$exp_date = $_POST['expdate'];
	$place_date = date("Y-m-d H:i:s");
	
	if($card_type == 'Amex' || $card_type == 'American Express')
	{
		$card_number = substr($card_number, 0, 4) . ' ' . substr($card_number, 4, 6) . ' ' . substr($card_number, 10);
	}
	else
	{
		$card_number = chunk_split($card_number, 4, ' ');
	}
	
	$query_update_customer = "
		UPDATE Customer_Order 
		SET first_name='$first_name', last_name='$last_name', phone='$phone', email='$email', delivery='$delivery', del_address='$del_address', del_city='$del_city', del_state='$del_state', del_zip='$del_zip', bill_name='$name_on_card', bill_address='$bill_address', bill_city='$bill_city', bill_state='$bill_state', bill_zip='$bill_zip', place_date='$place_date', status='placed' 
		WHERE order_id='$order_id'
		";
	$result_update_customer = mysqli_query($dbc, $query_update_customer);
	
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
		$outputtext = "Your order could not be placed because the restaurant is currently closed.";
	}
	else
	{
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
			
			$header_table_for_doc = '
				<w:tbl>
					<w:tblPr>
    					<w:tblW w:w="5000" w:type="pct"/>
    				</w:tblPr>
					<w:tr>
						<w:tc>
							<w:p><w:r><w:t>Incoming Gottanom Order</w:t></w:r></w:p>
						</w:tc>
					</w:tr>
				</w:tbl>
				';
	
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
					
				$delivery_address_for_doc = "
					<w:p><w:r><w:t>Order ID: ".$order_row['order_id']."</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>".$place_date."</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>Delivery Address</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>".$order_row['first_name']." ".$order_row['last_name']."</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>".$order_row['phone']."</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>".$order_row['email']."</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>";
				
				if($order_row['del_on_beach']=='checked')
				{
					$delivery_address .= "On The Beach At ";
					$delivery_address_for_doc .= "On The Beach At ";
				}
					
				$delivery_address .= $order_row['del_address']."
					<br>
					".$order_row['del_city'].", ".$order_row['del_state']." ".$order_row['del_zip']."
				";
				
				$delivery_address_for_doc .= $order_row['del_address']."</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>".$order_row['del_city'].", ".$order_row['del_state']." ".$order_row['del_zip']."</w:t></w:r></w:p>
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
					
				$delivery_address_for_doc = "
					<w:p><w:r><w:t>Order ID: ".$order_row['order_id']."</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>".$place_date."</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>Pickup Order</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>".$order_row['first_name']." ".$order_row['last_name']."</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>".$order_row['phone']."</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>".$order_row['email']."</w:t></w:r></w:p>
					";
			}
		
			$billing_address_for_restaurant = "
					Billing Info
					<br>
					".$order_row['bill_name']."
					<br>
					Card Type: ".$card_type."
					<br>
					Card Number: ".$card_number."
					<br>
					Exp. Date: ".$exp_date."
				";
				
			$billing_address_for_doc = "
					<w:p><w:r><w:t>Billing Info</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>".$order_row['bill_name']."</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>Card Type: ".$card_type."</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>Card Number: ".$card_number."</w:t></w:r></w:p>
					
					<w:p><w:r><w:t>Exp. Date: ".$exp_date."</w:t></w:r></w:p>
				";
		
			//$last_card_digits = $card_number % 10000;
	
			/*
			$last_card_digits = $card_number;
			while($last_card_digits >=10000)
			{
				$last_card_digits = $last_card_digits - 10000;
			}
			*/
		
			$billing_address_for_customer = "
					Billing Info
					<br>
					".$order_row['bill_name']."
					<br>
					".$card_type." ending in ".$last_card_digits."
					<br>
					".$order_row['bill_address']."
					<br>
					".$order_row['bill_city'].", ".$order_row['bill_state']." ".$order_row['bill_zip']."
				";
		
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
				
			$address_table_for_doc = '
				<w:tbl>
					<w:tblPr>
    					<w:tblW w:w="5000" w:type="pct"/>
    				</w:tblPr>
					<w:tr>
						<w:tc>
							'.$delivery_address_for_doc.'
						</w:tc>
						<w:tc>
							'.$billing_address_for_doc.'
						</w:tc>
					</w:tr>
				</w:tbl>
				';
	
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
					
				$summary_table_for_doc = '
				<w:tbl>
					<w:tblPr>
    					<w:tblW w:w="5000" w:type="pct"/>
    				</w:tblPr>
					<w:tr>
						<w:tc>
							<w:p><w:r><w:t>Qty</w:t></w:r></w:p>
						</w:tc>
						<w:tc>
							<w:p><w:r><w:t>Size</w:t></w:r></w:p>
						</w:tc>
						<w:tc>
							<w:p><w:r><w:t>Item</w:t></w:r></w:p>
						</w:tc>
						<w:tc>
							<w:p><w:r><w:t>Options</w:t></w:r></w:p>
						</w:tc>
						<w:tc>
							<w:p><w:r><w:t>Notes</w:t></w:r></w:p>
						</w:tc>
						<w:tc>
							<w:p><w:r><w:t>Price</w:t></w:r></w:p>
						</w:tc>
					</w:tr>
					';
		
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
						
					$summary_table_for_doc .= "
						<w:tr>
							<w:tc>
								<w:p><w:r><w:t>".$item_row['quantity']."</w:t></w:r></w:p>
							</w:tc>
							<w:tc>
								<w:p><w:r><w:t>".$item_row['size_name']."</w:t></w:r></w:p>
							</w:tc>
							<w:tc>
								<w:p><w:r><w:t>".$item_row['cat_name']."-->".$item_row['subcat_name']."-->".$item_row['item_name']."</w:t></w:r></w:p>
							</w:tc>
							<w:tc>
								<w:p><w:r><w:t>".$options."</w:t></w:r></w:p>
							</w:tc>
							<w:tc>
								<w:p><w:r><w:t>".$item_row['notes']."</w:t></w:r></w:p>
							</w:tc>
							<w:tc>
								<w:p><w:r><w:t>$".$item_row['total_item_cost']."</w:t></w:r></w:p>
							</w:tc>
						</w:tr>
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
						
				$summary_table_for_doc .= "
						<w:tr>
							<w:tc>
								<w:p><w:r><w:t>subtotal:</w:t></w:r></w:p>
							</w:tc>
							<w:tc>
								<w:p><w:r><w:t>$".$order_row['subtotal']."</w:t></w:r></w:p>
							</w:tc>
						</w:tr>
						<w:tr>
							<w:tc>
								<w:p><w:r><w:t>tax:</w:t></w:r></w:p>
							</w:tc>
							<w:tc>
								<w:p><w:r><w:t>$".$order_row['tax']."</w:t></w:r></w:p>
							</w:tc>
						</w:tr>
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
						
					$summary_table_for_doc .= "
						<w:tr>
							<w:tc>
								<w:p><w:r><w:t>delivery fee:</w:t></w:r></w:p>
							</w:tc>
							<w:tc>
								<w:p><w:r><w:t>$".$order_row['delivery_fee']."</w:t></w:r></w:p>
							</w:tc>
						</w:tr>
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
					
				$summary_table_for_doc .= "
						<w:tr>
							<w:tc>
								<w:p><w:r><w:t>tip:</w:t></w:r></w:p>
							</w:tc>
							<w:tc>
								<w:p><w:r><w:t>$".$order_row['tip']."</w:t></w:r></w:p>
							</w:tc>
						</w:tr>
						<w:tr>
							<w:tc>
								<w:p><w:r><w:t>total:</w:t></w:r></w:p>
							</w:tc>
							<w:tc>
								<w:p><w:r><w:t>$".$order_row['total']."</w:t></w:r></w:p>
							</w:tc>
						</w:tr>
					</w:tbl>
					";
			
			$separator = '-----'.md5(time()).'-----';
	
			$message_for_customer = "<html><body>" . $header_table_for_customer . $address_table_for_customer . $summary_table . "</body></html>";
	
			$headers_for_customer = "MIME-Version: 1.0\r\n";
			$headers_for_customer .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$headers_for_customer .= "From: noreply@gottanom.com";
		
			//$headers_for_restaurant = "MIME-Version: 1.0\r\n";
			//$headers_for_restaurant .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$headers_for_restaurant .= "From: restaurant@gottanom.com\r\n";
			$headers_for_restaurant .= "Content-Type: multipart/mixed; boundary=\"$separator\"";
	
			$message_for_restaurant = "<html><body>" . $header_table_for_restaurant . $address_table_for_restaurant . $summary_table . "</body></html>";
			
			$doc = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>
				<?mso-application progid="Word.Document"?>
				<w:wordDocument
xmlns:w="http://schemas.microsoft.com/office/word/2003/wordml"
   xmlns:wx="http://schemas.microsoft.com/office/word/2003/auxHint"
   xmlns:o="urn:schemas-microsoft-com:office:office"
   w:macrosPresent="no"
   w:embeddedObjPresent="no"
   w:ocxPresent="no"
   xml:space="preserve">
				<w:body>
				';
			
			$doc .= $header_table_for_doc . $address_table_for_doc . $summary_table_for_doc;
				
			$doc .= '
				</w:body>
				</w:wordDocument>';
			
			//$attachment = chunk_split(base64_encode($message_for_restaurant));
			$attachment = $message_for_restaurant;
			file_put_contents('orders/'.$filename, $message_for_restaurant);
			
			
			$body_for_restaurant = "--$separator\r\n"
				//. "Content-Type: text/html; charset=ISO-8859-1; format=flowed\r\n"
				//. "Content-Transfer-Encoding: 7bit\r\n"
				//. "\r\n"
				//. "$message_for_restaurant\r\n"
				//. "--$separator\r\n";
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
			
			$mail_for_customer = mail($email, 'Your Gottanom Order From '.$rest_row['name'], $message_for_customer, $headers_for_customer);
			//$mail_for_restaurant = mail($fax, '', $body_for_restaurant, $headers_for_restaurant);
	
			/*
			require("class.phpmailer.php");
			$fax = new PHPMailer();
			$fax->IsSMTP();
			$fax->Host     = 'smtpout.secureserver.net';
			$fax->IsHTML(true);
			$fax->From      = 'restaurant@gottanom.com';
			$fax->FromName  = 'Gottanom';
			$fax->Subject   = 'Your Gottanom Order';
			$fax->Body      = $message_for_restaurant;
			$fax->AddAddress($rest_row['fax']);

			$fax->AddStringAttachment($message_for_restaurant,$filename,'base64','text/html')

			$mail_for_restaurant = $fax->Send();
			*/
	
			//$transaction->amount = $order_row['total'];
			//$transaction->card_num = $_POST['cardnumber'];
			//$transaction->exp_date = $_POST['expdate'];

			//$response = $transaction->authorizeAndCapture();
			
			
			function fetch_url_post($url, $variable_array){
				$fields_string = "";
				//set POST variables
				foreach($variable_array as $key => $value){
					$fields[$key] =  urlencode($value);
					//$fields[$key] =  $value;
				}

				//url-ify the data for the POST
				foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
				$fields_string = rtrim($fields_string, '&');
				echo $fields_string;
				
				//open connection
				$ch = curl_init();

				//set the url, number of POST vars, POST data
				curl_setopt($ch,CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch,CURLOPT_POST, count($fields));
				curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

				//execute post
				$result = curl_exec($ch);
				
				return $result;
				
				//close connection
				curl_close($ch);
			}
			
			
			$url = 'https://service.ringcentral.com/faxapi.asp';
			$faxData = array();
			$faxData['Username'] = '18666048452';
			$faxData['Password'] = 'g0ttaN0m!';
			$faxData['Recipient'] = "1".$rest_row['fax'];
			//$outputtext .= $faxData['Recipient'];
			//$faxData['Attachment'] = urlencode('https://gottanom.com/1.1/php/orders/'.$filename);
			$faxData['Attachment'] = $attachment;
			//$faxData['Coverpagetext'] = $message_for_restaurant;
			$faxResult = fetch_url_post($url, $faxData);
			$outputtext .= "\r\nresult:".$faxResult."!";
			
			
			/*
			$body_separator = '---------------------------'.md5(time());
			
			$request_body = "Content-Type: multipart/form-data; boundary=".$body_separator."\r\n";
			$request_body .= "\r\n";
			$request_body .= $body_separator."\r\n";
			
			$request_body .= 'Content-Disposition: form-data; name="Username"'."\r\n";
			$request_body .= "\r\n";
			$request_body .= "18666048452 \r\n";
			$request_body .= $body_separator."\r\n";
			
			$request_body .= 'Content-Disposition: form-data; name="Password"'."\r\n";
			$request_body .= "\r\n";
			$request_body .= "g0ttaN0m! \r\n";
			$request_body .= $body_separator."\r\n";
			
			$request_body .= 'Content-Disposition: form-data; name="Recipient"'."\r\n";
			$request_body .= "\r\n";
			$request_body .= "1".$rest_row['fax']."|Restaurant\r\n";
			//$request_body .= "16092576122\r\n";
			$request_body .= $body_separator."--\r\n";
			
			$request_body .= 'Content-Disposition: form-data; name="Attachment"; filename="'.$filename.'"'."\r\n";
			$request_body .= "\r\n";
			$request_body .= "$attachment\r\n";
			//$request_body .= "01110100011001010111001101110100011110010010000001110100011001010111001101110100011110010010000001110100011001010111001101110100 \r\n";
			$request_body .= $body_separator."\r\n";
			
			//$request_body .= 'Content-Disposition: form-data; name="Coverpagetext"'."\r\n";
			//$request_body .= "\r\n";
			//$request_body .= "$attachment\r\n";
			//$request_body .= $body_separator."\r\n";
			
			$outputtext .= $request_body;
			
			$url = 'https://service.ringcentral.com/faxapi.asp';
			$ch = curl_init($url);
 
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
 
			$faxResult = curl_exec($ch);
			curl_close($ch);
			$outputtext .= "result:".$faxResult."!";
			*/
			
			/*
			$url = 'https://service.ringcentral.com/faxapi.asp';
			$faxResult = http_post_data($url, $request_body);
			$outputtext .= "result:".$faxResult."!";
			*/
			
			/*
			// use key 'http' even if you send the request to https://...
			$options = array(
				'http' => array(
					'header'  => "Content-Type: multipart/form-data; boundary=---------------------------7d54b1fee05aa\r\n",
					'method'  => 'POST',
					'content' => http_build_query($faxData),
				)
			);
			//print_r($options);
			//$context  = stream_context_create($options);
			//print_r($context);
			
			//$faxResult = file_get_contents($url, false, $request_body);

			//var_dump($result);
			
			//$faxResult = "";
			//$faxResult = fetch_url_post($url, $faxData);
			//$outputtext .= $faxResult;
			*/
			
			//$faxResult = file_get_contents($url, false, $request_body);
			//$outputtext .= $faxResult;
			
			/*
			$url = 'https://service.ringcentral.com/faxapi.asp';
			$faxData = array();
			$faxData['Username'] = '18666048452';
			$faxData['Password'] = 'g0ttaN0m!';
			//$faxData['Password'] = '110887';
			$faxData['Attachment'] = $attachment;
			$faxData['Recipient'] = "1".$rest_row['fax'];
			//$faxData['Coverpage'] = 'Clasmod';

			// use key 'http' even if you send the request to https://...
			$options = array(
				'http' => array(
					'header'  => "Content-Type: multipart/form-data\r\n",
					'method'  => 'POST',
					'content' => http_build_query($faxData)
				)
			);
			$context  = stream_context_create($options);
			$faxResult = file_get_contents($url, false, $context);
			$outputtext .= "result:".$faxResult."!";

			//var_dump($result);
			*/
			
			if($PhoneNumbers=$rest_row['call_in_order'])
			{
				include "callinorder.php";
			}

			if ($mail_for_customer && ($faxResult==0)) {
				//echo "<h1>Success! The test credit card has been charged!</h1>";
				//echo "Transaction ID: " . $response->transaction_id;
				$query_update_customer = "UPDATE Customer SET rest_id=0, order_id=0 WHERE user_id='$user_id'";
				$result_update_customer = mysqli_query($dbc, $query_update_customer);
		
				$outputtext .= "1";
			}
			else
			{
				//echo $response->error_message;
				$outputtext .= "Unfortunately, we were unable to place your order. Please try again later.";
			}
	
		}
		
	}
	
	//session_write_close();
	echo $outputtext;
?>