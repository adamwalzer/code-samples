<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

include ('connecttowrite.php');
//sanitize($dbc,$_SESSION);

$loc_id = $_GET['loc_id'];
$rest_id = $_GET['rest_id'];
$item_id = $_GET['item_id'];

if($loc_id && $rest_id && $item_id)
{

	if($input=$_SESSION['input'])
	{
		$inputstring = implode(';;', $input);
		//$outputtext .= $inputstring;
		$inputarray = explode('::I', $inputstring);
		$inputcount = count($inputarray);
		$input = explode(';;', $inputarray[$inputcount-1]);
		
		while(count($input) > 1)
		{
			if(strncmp($input[0],"::Q",3)==0)
			{
				$quantity = $input[1];
				unset($input[0]); // remove item at index 0
				unset($input[1]); // remove item at index 1
			}
			elseif(strncmp($input[0],"::S",3)==0)
			{
				if($input[1]=="true")
				{
					$size_number = $input[0][3];
				}
				unset($input[0]); // remove item at index 0
				unset($input[1]); // remove item at index 1
			}
			elseif(strncmp($input[0],"::N",3)==0)
			{
				$notes = $input[1];
				unset($input[0]); // remove item at index 0
				unset($input[1]); // remove item at index 1
			}
			elseif(strncmp($input[0],"::G",3)==0)
			{
				//$group_id[] = $input[1];
				unset($input[0]); // remove item at index 0
				unset($input[1]); // remove item at index 1
			}
			elseif(strncmp($input[0],"::O",3)==0)
			{
				if($input[2] == 'true')
				{
					$option_checked[$input[1]] = 'checked';
					$option_quantity[$input[1]] = 0;
				}
				else if($input[2] != '')
				{
					$option_checked[$input[1]] = '';
					$option_quantity[$input[1]] = $input[2];
					//$outputtext .= $input[2];
				}
				else
				{
					$option_checked[$input[1]] = '';
					$option_quantity[$input[1]] = 0;
					//$outputtext .= 0;
				}
				
				unset($input[0]); // remove item at index 0
				unset($input[1]); // remove item at index 1
				unset($input[2]); // remove item at index 2
			}
			else
			{
				unset($input[0]); // remove item at index 0
			}
			//unset($input[0]); // remove item at index 0
			$input = array_values($input); // 'reindex' array
		}
		
		$query_get_item = "
			SELECT * FROM Menu_Item 
			WHERE (rest_id='$rest_id' AND item_id='$item_id')
			";
        $result_get_item = mysqli_query($dbc, $query_get_item);
		$item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC);
		
		$price_string = "price_".$size_number;
		
		$subcat_id = $item_row['subcat_id'];
		//$outputtext .= $subcat_id;
		
		$query_get_subcat = "
			SELECT * FROM Menu_Subcategory 
			WHERE (rest_id='$rest_id' AND subcat_id='$subcat_id')
			";
        $result_get_subcat = mysqli_query($dbc, $query_get_subcat);
		$subcat_row = mysqli_fetch_array($result_get_subcat, MYSQLI_ASSOC);
		$cat_id = $subcat_row['cat_id'];
		$subcat_name = sanitize($dbc,$subcat_row['name']);
		
		$query_get_cat = "
			SELECT * FROM Menu_Category 
			WHERE (rest_id='$rest_id' AND cat_id='$cat_id')
			";
        $result_get_cat = mysqli_query($dbc, $query_get_cat);
		$cat_row = mysqli_fetch_array($result_get_cat, MYSQLI_ASSOC);
		$cat_name = sanitize($dbc,$cat_row['name']);
		
		$size_string = "size_".$size_number;
		$size_name = sanitize($dbc,$subcat_row[$size_string]);
		
		$query_get_restaurant = "
			SELECT * FROM Restaurant 
			WHERE (loc_id='$loc_id' AND rest_id='$rest_id')
			";
        $result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
		$restaurant_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC);
		
		//$delivery_fee = $restaurant_row['delivery_fee'];
		//$delivery_minimum = $restaurant_row['delivery_minimum'];
		
		$item_name = sanitize($dbc,$item_row['name']);
		$item_cost = $item_row[$price_string];
		$total_item_cost = $quantity * $item_cost;
		$tax_percent = $restaurant_row['tax_percent'];
		$item_tax = round($tax_percent*$total_item_cost/100,2);
		$item_total = $total_item_cost+$item_tax;
		
		if($_POST)
		{
			$delivery = $_POST['delivery'];
			$email = $_POST['email'];
			$phone = $_POST['phone_number'];
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$del_address = $_POST['address'];
			$del_city = $_POST['city'];
			$del_state = $_POST['state'];
			$del_zip = $_POST['zip'];
			if($_POST['current_on_beach'])
			{
				$del_on_beach = 'checked';
			}
			else
			{
				$del_on_beach = '';
			}
			$start_date = date("Y-m-d H:i:s");
			$last_edit = $start_date;
			$user_id = $_SESSION['user_id'];
			$subtotal = $total_item_cost;
			$tax = $item_tax;
			$total = $item_total;
			$tip = 0;
			
			$_SESSION['current_address'] = $del_address;
			$_SESSION['current_city'] = $del_city;
			$_SESSION['current_state'] = $del_state;
			$_SESSION['current_zip'] = $del_zip;
			
			if($restaurant_row['delivers'])
			{
				if($del_city || $del_zip)
				{
					$query_get_delivers = "
						SELECT * FROM Delivery_Info 
						WHERE (rest_id='$rest_id' AND state='$del_state' AND (city='$del_city' OR zip='$del_zip')) 
						LIMIT 1
						";
					$result_get_delivers = mysqli_query($dbc, $query_get_delivers);
					if (@mysqli_num_rows($result_get_delivers) == 1)
					{
						$delivers_row = mysqli_fetch_array($result_get_delivers, MYSQLI_ASSOC);
						$delivery_minimum = $delivers_row['delivery_minimum'];
						$delivery_fee = $delivers_row['delivery_fee'];
					}
					else
					{
						if($delivery == 'delivery')
						{
							$outputtext .= 'This restaurant does not deliver to this location.';
							$delivery = 'pickup';
						}
						$delivery_minimum = $restaurant_row['delivery_minimum'];
						$delivery_fee = $restaurant_row['delivery_fee'];
					}
				}
				else
				{
					if($delivery == 'delivery')
					{
						$outputtext .= 'This restaurant does not deliver to this location.';
						$delivery = 'pickup';
					}
					$delivery_minimum = $restaurant_row['delivery_minimum'];
					$delivery_fee = $restaurant_row['delivery_fee'];
				}
			}
			
			$pickup_minimum = $restaurant_row['pickup_minimum'];
			
			if($delivery == 'delivery')
			{
				$tip = 2.00;
				$total = $total + $delivery_fee +$tip;
			}
			
			$status = 'started';
			
			$query_insert_order = "
				INSERT INTO Customer_Order 
						(loc_id, rest_id, user_id, subtotal, tax_percent, tax, tip, total, first_name, last_name, phone, email, delivery, delivery_fee, delivery_minimum, pickup_minimum, del_on_beach, del_address, del_city, del_state, del_zip, start_date, last_edit, status)
				VALUES ('$loc_id', '$rest_id', '$user_id', '$subtotal', '$tax_percent', '$tax', '$tip', '$total', '$first_name', '$last_name', '$phone', '$email', '$delivery', '$delivery_fee', '$delivery_minimum', '$pickup_minimum', '$del_on_beach', '$del_address', '$del_city', '$del_state', '$del_zip', '$start_date', '$last_edit', '$status')
				";
		
			$result_insert_order = mysqli_query($dbc, $query_insert_order);
			//$outputtext .= mysqli_error($dbc);
			
			$query_get_order = "
				SELECT * FROM Customer_Order 
				WHERE (rest_id='$rest_id' AND user_id='$user_id' AND start_date='$start_date')
				";
			$result_get_order = mysqli_query($dbc, $query_get_order);
			$order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC);
			$order_id = $order_row['order_id'];
			setcookie("order_id", $order_id, time() + (60 * 15), '/', 'gottanom.com');
			$order_item_id = 1;
			
			$query_insert_item = "
				INSERT INTO Customer_Order_Item 
						(order_id, order_item_id, item_id, cat_id, cat_name, subcat_name, item_name, quantity, size_number, size_name, item_cost, total_item_cost, notes)
				VALUES ('$order_id', $order_item_id, '$item_id', '$cat_id', '$cat_name', '$subcat_name', '$item_name', '$quantity', '$size_number', '$size_name', '$item_cost', '$total_item_cost', '$notes')
				";
			$result_insert_item = mysqli_query($dbc, $query_insert_item);
			
			$option_cost = 0;
			
			$query_get_updated = "
				SELECT MAX(updated) AS updated 
				FROM Menu_Item_Option_Group 
				WHERE (rest_id='$rest_id' AND item_id='$item_id')
				";
			$result_get_updated = mysqli_query($dbc, $query_get_updated);
			$updated_row = mysqli_fetch_array($result_get_updated, MYSQLI_ASSOC);
			if($updated_row['updated'])
			{
				$updated = $updated_row['updated'];
			}
			else
			{
				$updated = '0000-00-00 00:00:00';
			}
			
			$query_get_group = "
				SELECT * FROM Menu_Item_Option_Group 
				WHERE (rest_id='$rest_id' AND item_id='$item_id' AND updated='$updated') 
				ORDER BY group_order
				";
			$result_get_group = mysqli_query($dbc, $query_get_group);
			
			while($group_row = mysqli_fetch_array($result_get_group, MYSQLI_ASSOC))
			{
				$query_insert_group = "
					INSERT INTO Customer_Order_Item_Option_Group 
							(order_id, item_id, order_item_id, group_id, group_name, group_order, group_type, group_quantity)
					VALUES ('$order_id', '$item_id', $order_item_id, '".$group_row['group_id']."', '".sanitize($dbc,$group_row['group_name'])."', '".$group_row['group_order']."', '".$group_row['group_type']."', ".$group_row['group_quantity'].")
					";
				$result_insert_group = mysqli_query($dbc, $query_insert_group);
				
				$query_get_option = "
					SELECT * FROM Menu_Item_Option 
					WHERE (rest_id='$rest_id' AND item_id='$item_id' AND group_id='".$group_row['group_id']."' AND updated='$updated') 
					ORDER BY option_order
					";
				$result_get_option = mysqli_query($dbc, $query_get_option);
				
				while($option_row = mysqli_fetch_array($result_get_option, MYSQLI_ASSOC))
				{
					$option_id = $option_row['option_id'];
					$query_insert_option = "
						INSERT INTO Customer_Order_Item_Option 
								(order_id, item_id, order_item_id, group_id, option_id, option_order, option_name, option_cost, option_quantity, checked)
						VALUES ('$order_id', '$item_id', $order_item_id, '".$group_row['group_id']."', '$option_id', '".$option_row['option_order']."', '".sanitize($dbc,$option_row['option_name'])."', '".$option_row['option_cost']."', '".$option_quantity[$option_id]."', '".$option_checked[$option_id]."')
						";
					$result_insert_option = mysqli_query($dbc, $query_insert_option);
					
					if($option_checked[$option_id] == 'checked')
					{
						$option_cost = $option_cost + $option_row['option_cost'];
					}
					else if($option_quantity[$option_id])
					{
						$option_cost = $option_cost + $option_row['option_cost'] * $option_quantity[$option_id];
					}
				}
			}
			
			$item_cost = $item_cost + $option_cost;
			$total_item_cost = $quantity * $item_cost;
			$item_tax = $tax_percent*$total_item_cost/100;
			$item_total = $total_item_cost+$item_tax;
			
			$query_update_item = "
				UPDATE Customer_Order_Item 
				SET item_cost='$item_cost', total_item_cost='$total_item_cost' 
				WHERE order_id='$order_id' AND order_item_id='$order_item_id'
				";
			$result_update_item = mysqli_query($dbc, $query_update_item);
			
			$subtotal = $total_item_cost;
			$tax = $item_tax;
			$total = $item_total;
			if($order_row['delivery']=='delivery')
			{
				$total = $total + $order_row['delivery_fee'] + $order_row['tip'];
			}
			else
			{
				$total = $total + $order_row['tip'];
			}
			
			$query_update_order = "
				UPDATE Customer_Order 
				SET subtotal='$subtotal', tax='$tax', total='$total' 
				WHERE order_id='$order_id'
				";
			$result_update_order = mysqli_query($dbc, $query_update_order);
			
			$query_update_customer = "
				UPDATE Customer 
				SET loc_id='$loc_id', rest_id='$rest_id', order_id='$order_id' 
				WHERE user_id='$user_id'
				";
			$result_get_customer = mysqli_query($dbc, $query_update_customer);
			
			$query_get_charity = "
				SELECT * FROM Customer_Charity
				WHERE (user_id='$user_id')
				";
			$result_get_charity = mysqli_query($dbc, $query_get_charity);
			
			if (@mysqli_num_rows($result_get_charity) < 1)
			{
				$query_get_charity = "
				SELECT * FROM Charity
				WHERE (loc_id='$loc_id')
				";
				$result_get_charity = mysqli_query($dbc, $query_get_charity);
			}
			
			while($charity_row = mysqli_fetch_array($result_get_charity, MYSQLI_ASSOC))
			{
				$charity_id = $charity_row['charity_id'];
				$charity_name = sanitize($dbc,$charity_row['charity_name']);
				if($charity_row['donate'])
				{
					$donate = 1;
				}
				else
				{
					$donate = 0;
				}
				$query_insert_charity = "
					INSERT INTO Customer_Order_Charity 
							(order_id, charity_id, charity_name, donate)
					VALUES ('$order_id', '$charity_id', '$charity_name', '$donate')
					";
				$result_insert_charity = mysqli_query($dbc, $query_insert_charity);
			}
    		
    		$_SESSION['loc_id'] = $loc_id;
    		$_SESSION['rest_id'] = $rest_id;
    		$_SESSION['order_id'] = $order_id;
    		$_SESSION['delivery'] = $delivery;
			
			//$outputtext .= $user_id;
			//$outputtext .= $order_id;
			$outputtext .= "
				<div>
					<script>
						changeURLVariable(".'"loc_id='.$loc_id.'&rest_id='.$rest_id.'"'.",{".'"reload"'.":true});
					</script>
				</div>
				";
		}
		else
		{
			$outputtext .= "There is no order data.";
		}
	}
	else
	{
		$outputtext .= "There is nothing to add.";
	}
	
}
else
{
	$outputtext .= "No Restaurant ID";
}

/// var_dump($error);
mysqli_close($dbc);

?>