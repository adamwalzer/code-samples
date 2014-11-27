<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

include ('connecttowrite.php');

$order_id = $_SESSION['order_id'];
$loc_id = $_SESSION['loc_id'];
$rest_id = $_SESSION['rest_id'];
$order_item_id = $_GET['order_item_id'];

$outputtext .= $loc_id.",".$rest_id.",".$order_item_id.",";

if($order_id && $order_item_id)
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
		$item_id = $item_row['item_id'];
		
		$query_get_menu_item = "
			SELECT * FROM Menu_Item 
			WHERE (rest_id='$rest_id' AND item_id='$item_id')
			";
		$result_get_menu_item = mysqli_query($dbc, $query_get_menu_item);
		$menu_item_row = mysqli_fetch_array($result_get_menu_item, MYSQLI_ASSOC);
		
		$subtotal = $order_row['subtotal'] - $item_row['total_item_cost'];
		
		$price_string = "price_".$size_number;
		
		$subcat_id = $menu_item_row['subcat_id'];
		
		$query_get_subcat = "
			SELECT * FROM Menu_Subcategory 
			WHERE (rest_id='$rest_id' AND subcat_id='$subcat_id')
			";
        $result_get_subcat = mysqli_query($dbc, $query_get_subcat);
		$subcat_row = mysqli_fetch_array($result_get_subcat, MYSQLI_ASSOC);
		
		$size_string = "size_".$size_number;
		$size_name = $subcat_row[$size_string];
		
		$item_name = $item_row['name'];
		$subcat_id = $item_row['subcat_id'];
		$allergen = $item_row['allergen'];
		$descript = $item_row['descript'];
		$item_cost = $menu_item_row[$price_string];
		
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
			
			$option_cost = 0;
			
			$query_get_group = "
				SELECT * FROM Customer_Order_Item_Option_Group 
				WHERE (order_id='$order_id' AND order_item_id='$order_item_id') 
				ORDER BY group_order
				";
			$result_get_group = mysqli_query($dbc, $query_get_group);
			
			while($group_row = mysqli_fetch_array($result_get_group, MYSQLI_ASSOC))
			{
				$query_get_option = "
					SELECT * FROM Customer_Order_Item_Option 
					WHERE (order_id='$order_id' AND order_item_id='$order_item_id' AND group_id='".$group_row['group_id']."') 
					ORDER BY option_order
					";
				$result_get_option = mysqli_query($dbc, $query_get_option);
				
				while($option_row = mysqli_fetch_array($result_get_option, MYSQLI_ASSOC))
				{
					$option_id = $option_row['option_id'];
					$query_update_option = "
						UPDATE Customer_Order_Item_Option 
						SET option_quantity='".$option_quantity[$option_id]."', checked='".$option_checked[$option_id]."' 
						WHERE order_id='$order_id' AND order_item_id='$order_item_id' AND option_id='$option_id'
						";
					$result_update_option = mysqli_query($dbc, $query_update_option);
					
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
				SET quantity='$quantity', size_number='$size_number', size_name='$size_name', item_cost='$item_cost', total_item_cost='$total_item_cost', notes='$notes' 
				WHERE order_id='$order_id' AND order_item_id='$order_item_id'
				";
			$result_update_item = mysqli_query($dbc, $query_update_item);
			
			$subtotal = $subtotal + $total_item_cost;
			$tax = $subtotal*$order_row['tax_percent']/100;
			if($order_row['delivery']=='delivery')
			{
				$total = $subtotal + $tax + $order_row['delivery_fee'] + $order_row['tip'];
			}
			else
			{
				$total = $subtotal + $tax + $order_row['tip'];
			}
			
			
			$query_update_order = "
				UPDATE Customer_Order 
				SET subtotal='$subtotal', tax='$tax', total='$total' 
				WHERE order_id='$order_id'
				";
		
			$result_update_order = mysqli_query($dbc, $query_update_order);
			
			$outputtext .= $order_id;
		}
		else
		{
			$outputtext .= "This order already exists.";
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