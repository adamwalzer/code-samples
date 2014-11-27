<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

include ('connecttowrite.php');

$loc_id = $_SESSION['loc_id'];
$rest_id = $_SESSION['rest_id'];

if($loc_id && $rest_id)
{
	$group_order = 1;
	$option_order = 1;
	
	
	if($input=$_POST['input'])
	{
		//$outputtext .= $loc_id;
		//$outputtext .= $rest_id;
		//$outputtext .= $input[0];
		$date = date('Y-m-d H:i:s');
		//$outputtext .= $date;
		$group_order = 1;
		$option_order = 1;
		
		while(count($input) > 1)
		{
			if(strncmp($input[0],'::',2)==0)
			{
				$item_id = substr($input[0],2);
				$group_order = 1;
			
				$query_get_item = '
					SELECT * FROM Menu_Item 
					WHERE (rest_id="'.$rest_id.'" AND item_id="'.$item_id.'")
					';
				$result_get_item = mysqli_query($dbc, $query_get_item);
				
				if (@mysqli_num_rows($result_get_item) > 0)//if Query is successful 
				{
					$query_update_item = '
						UPDATE Menu_Item 
						SET name="'.$input[1].'", descript="'.$input[7].'", item_order="'.$item_order.'", subcat_id="'.$subcat_id.'",  price_1="'.$input[2].'", price_2="'.$input[3].'", price_3="'.$input[4].'", price_4="'.$input[5].'", price_5="'.$input[6].'", allergen="'.$input[8].'", 
						WHERE rest_id="'.$rest_id.'" AND item_id="'.$item_id.'" 
						LIMIT 1
						';
					mysqli_query($dbc, $query_update_item);
					unset($input[0]); // remove item at index 0
					unset($input[1]); // remove item at index 1
					unset($input[2]); // remove item at index 2
					unset($input[3]); // remove item at index 3
					unset($input[4]); // remove item at index 4
					unset($input[5]); // remove item at index 5
					unset($input[6]); // remove item at index 6
					unset($input[7]); // remove item at index 7
					unset($input[8]); // remove item at index 8
					$input = array_values($input); // 'reindex' array
				}
				else
				{
					$query_set_item = '
						INSERT INTO Menu_Item 
								(rest_id, subcat_id, item_id, name, descript, item_order, price_1, price_2, price_3, price_4, price_5, allergen, updated) 
						VALUES ("'.$rest_id.'", "'.$subcat_id.'", "'.$item_id.'", "'.$input[1].'", "'.$input[7].'", "'.$item_order.'", "'.$input[2].'", "'.$input[3].'", "'.$input[4].'", "'.$input[5].'", "'.$input[6].'", "'.$input[8].'", "'.$date.'")
						';
					mysqli_query($dbc, $query_set_item);
					$item_order++;
					unset($input[0]); // remove item at index 0
					unset($input[1]); // remove item at index 1
					unset($input[2]); // remove item at index 2
					unset($input[3]); // remove item at index 3
					unset($input[4]); // remove item at index 4
					unset($input[5]); // remove item at index 5
					unset($input[6]); // remove item at index 6
					unset($input[7]); // remove item at index 7
					unset($input[8]); // remove item at index 8
					$input = array_values($input); // 'reindex' array
				}
			}
			elseif(strncmp($input[0],';;',2)==0)
			{
				$group_id = substr($input[0],2);
				$option_order = 1;
			
				$query_get_group = '
					SELECT * FROM Menu_Item_Option_Group 
					WHERE (rest_id="'.$rest_id.'" AND item_id="'.$item_id.'" AND group_id="'.$group_id.'")
					';
				$result_get_group = mysqli_query($dbc, $query_get_group);
			
				if (@mysqli_num_rows($result_get_group) > 0)//if Query is successful 
				{
					if($input[2]=='true')
					{
						$group_type = 'checkbox';
						$group_quantity = NULL;
					}
					
					if($input[3]=='true')
					{
						$group_type = 'radio';
						$group_quantity = NULL;
					}
					
					if($input[4]=='true')
					{
						$group_type = 'quantity';
						$group_quantity = $input[5];
					}
					
					$query_update_group = '
						UPDATE Menu_Item_Option_Group 
						SET group_name="'.$input[1].'", group_order="'.$group_order.'", group_type="'.$group_type.'", group_quantity="'.$group_quantity.'", updated="'.$date.'" WHERE rest_id="'.$rest_id.'" AND item_id="'.$item_id.'" AND group_id="'.$group_id.'" 
						LIMIT 1
						';
					mysqli_query($dbc, $query_update_group);
					$group_order++;
					unset($input[0]); // remove item at index 0
					unset($input[1]); // remove item at index 1
					unset($input[2]); // remove item at index 2
					unset($input[3]); // remove item at index 3
					unset($input[4]); // remove item at index 4
					unset($input[5]); // remove item at index 5
					$input = array_values($input); // 'reindex' array
				}
				else
				{
					if($input[2]=='true')
					{
						$group_type = 'checkbox';
						$group_quantity = NULL;
					}
					
					if($input[3]=='true')
					{
						$group_type = 'radio';
						$group_quantity = NULL;
					}
					
					if($input[4]=='true')
					{
						$group_type = 'quantity';
						$group_quantity = $input[5];
					}
					
					$query_set_group = '
						INSERT INTO Menu_Item_Option_Group 
								(rest_id, item_id, group_id, group_name, group_order, group_type, group_quantity, updated) 
						VALUES ("'.$rest_id.'", "'.$item_id.'", "'.$group_id.'", "'.$input[1].'", "'.$group_order.'", "'.$group_type.'", "'.$group_quantity.'", "'.$date.'")
						';
					mysqli_query($dbc, $query_set_group);
					$group_order++;
					unset($input[0]); // remove item at index 0
					unset($input[1]); // remove item at index 1
					unset($input[2]); // remove item at index 2
					unset($input[3]); // remove item at index 3
					unset($input[4]); // remove item at index 4
					unset($input[5]); // remove item at index 5
					$input = array_values($input); // 'reindex' array
				}
			}
			elseif(strncmp($input[0],',,',2)==0)
			{
				$option_id = substr($input[0],2);
				
				if($input[3]=='true')
				{
					$checked_by_default = 'checked';
				}
				else
				{
					$checked_by_default = '';
				}
				
				$query_get_option = '
					SELECT * FROM Menu_Item_Option 
					WHERE (rest_id="'.$rest_id.'" AND item_id="'.$item_id.'" AND option_id="'.$option_id.'")
					';
				$result_get_option = mysqli_query($dbc, $query_get_option);
				
				if (@mysqli_num_rows($result_get_option) > 0)//if Query is successful 
				{
					$query_update_option = '
						UPDATE Menu_Item_Option 
						SET option_name="'.$input[1].'", option_order="'.$option_order.'", option_cost="'.$input[2].'", group_id="'.$group_id.'", checked="'.$checked_by_default.'", updated="'.$date.'" WHERE rest_id="'.$rest_id.'" AND item_id="'.$item_id.'" AND option_id="'.$option_id.'" 
						LIMIT 1
						';
					mysqli_query($dbc, $query_update_option);
					$option_order++;
					unset($input[0]); // remove item at index 0
					unset($input[1]); // remove item at index 1
					unset($input[2]); // remove item at index 2
					unset($input[3]); // remove item at index 3
					$input = array_values($input); // 'reindex' array
				}
				else
				{
					$query_set_option = '
						INSERT INTO Menu_Item_Option 
								(rest_id, item_id, group_id, option_id, option_order, option_name, option_cost, checked, updated) 
						VALUES ("'.$rest_id.'", "'.$item_id.'", "'.$group_id.'", "'.$option_id.'", "'.$option_order.'", "'.$input[1].'", "'.$input[2].'", "'.$checked_by_default.'", "'.$date.'")
						';
					mysqli_query($dbc, $query_set_option);
					$option_order++;
					unset($input[0]); // remove item at index 0
					unset($input[1]); // remove item at index 1
					unset($input[2]); // remove item at index 2
					unset($input[3]); // remove item at index 3
					$input = array_values($input); // 'reindex' array
				}
			}
		}
		$outputtext .= 'Your item has been succesfully saved.';
	}
	else
	{
		$outputtext .= 'There is nothing to save.';
	}
		
	/*
	INSERT INTO `usage`
	(`thing_id`, `times_used`, `first_time_used`)
	VALUES
	(4815162342, 1, NOW())
	ON DUPLICATE KEY UPDATE
	`times_used` = `times_used` + 1
	*/
	
}
else
{
	$outputtext .= 'No Restaurant ID';
}

/// var_dump($error);
mysqli_close($dbc);

echo $outputtext;

?>