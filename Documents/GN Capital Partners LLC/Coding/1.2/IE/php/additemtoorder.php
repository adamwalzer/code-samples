<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

include ('connecttowrite.php');
//include 'sanitize.php';

$loc_id = $_GET['loc_id'];
$rest_id = $_GET['rest_id'];
$item_id = $_GET['item_id'];

if($loc_id && $rest_id && $item_id)
{

	if($input=$_POST['input'])
	{
		$inputstring = implode(';;', $input);
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
		
		$item_name = sanitize($dbc,$item_row['name']);
		$item_cost = $item_row[$price_string];
		$total_item_cost = $quantity * $item_cost;
		
		if($_SESSION['order_id']!=0 && $_SESSION['order_id'])
		{
			$order_id = $_SESSION['order_id'];
			setcookie("order_id", $order_id, time() + (60 * 15), '/', 'gottanom.com');
			
			$query_get_order = "
				SELECT * FROM Customer_Order 
				WHERE (order_id='$order_id')
				";
			$result_get_order = mysqli_query($dbc, $query_get_order);
			$order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC);
			//$item_array = unserialize($order_row['item_array']);
			//$item_count = count($item_array);
			//$item_array[$item_count] = array("item_id" => $item_id, "item_name" => $item_name, "quantity" => $quantity, "size_number" => $size_number, "size_name" => $size_name, "item_cost" => $item_cost, "total_item_cost" => $total_item_cost, "notes" => $notes);
			//$item = serialize($item_array);
			
			$query_get_order_item_id = "
				SELECT MAX(order_item_id) AS order_item_id FROM Customer_Order_Item 
				WHERE order_id='$order_id'
				";
			$result_get_order_item_id = mysqli_query($dbc, $query_get_order_item_id);
			$order_item_id_row = mysqli_fetch_array($result_get_order_item_id, MYSQLI_ASSOC);
			if($order_item_id_row['order_item_id'])
			{
				$order_item_id = $order_item_id_row['order_item_id']+1;
			}
			else
			{
				$order_item_id = 1;
			}
			
			$query_insert_item = "
				INSERT INTO Customer_Order_Item
						(order_id, order_item_id, item_id, cat_id, cat_name, subcat_name, item_name, quantity, size_number, size_name, item_cost, total_item_cost, notes)
				VALUES ('$order_id', $order_item_id, '$item_id', '$cat_id', '$cat_name', '$subcat_name', '$item_name', '$quantity', '$size_number', '$size_name', '$item_cost', '$total_item_cost', '$notes')
				";
			$result_insert_item = mysqli_query($dbc, $query_insert_item);
			
			$option_cost = 0;
			
			$query_get_updated = "
				SELECT MAX(updated) AS updated FROM Menu_Item_Option_Group 
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
			
			$query_update_item = "
				UPDATE Customer_Order_Item 
				SET item_cost='$item_cost', total_item_cost='$total_item_cost' 
				WHERE order_id='$order_id' AND order_item_id='$order_item_id'
				";
			$result_update_item = mysqli_query($dbc, $query_update_item);
			
			$subtotal = $order_row['subtotal'] + $total_item_cost;
			$tax = $subtotal*$order_row['tax_percent']/100;
			if($order_row['delivery']=='delivery')
			{
				$total = $subtotal + $tax + $order_row['delivery_fee'] + $order_row['tip'];
			}
			else
			{
				$total = $subtotal + $tax + $order_row['tip'];
			}
			
			$last_edit = date("Y-m-d H:i:s");
			
			$query_update_order = "
				UPDATE Customer_Order 
				SET subtotal='$subtotal', tax='$tax', total='$total', last_edit='$last_edit' 
				WHERE order_id='$order_id'
				";
		
			$result_update_order = mysqli_query($dbc, $query_update_order);
			
			$outputtext .= $order_id;
		}
		else
		{
			$outputtext .= "The order already exists.";
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

echo $outputtext;

?>