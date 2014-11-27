<?php

set_time_limit(0);
ignore_user_abort(1);
//ini_set('memory_limit','16M');

session_start();

include ("connecttowrite.php");

$loc_id = $_SESSION["loc_id"];
$rest_id = $_SESSION["rest_id"];

if($loc_id && $rest_id)
{
	
	$cat_order = 1;
	$subcat_order = 1;
	$item_order = 1;
	
	
	if($input=$_POST["input"])
	{
		//$outputtext .= $loc_id;
		//$outputtext .= $rest_id;
		//$outputtext .= $input[0];
		$date = date("Y-m-d H:i:s");
		//$outputtext .= $date;
		$cat_order = 1;
		$subcat_order = 1;
		$item_order = 1;
		
		/* 
		$i = 0;
		while($i < count($input))
		{
			str_replace(""","\"",$input[$i]);
			$i++;
		}
		 */
		
		while(count($input) > 1)
		{
			//$outputtext .= $input[0];
			
			if(strncmp($input[0],"::",2)==0)
			{
				$cat_id = substr($input[0],2);
				$subcat_order = 1;
			
				$query_get_category = '
					SELECT * FROM Menu_Category 
					WHERE (rest_id="'.$rest_id.'" AND cat_id="'.$cat_id.'")
					';
				$result_get_category = mysqli_query($dbc, $query_get_category);
			
				if (@mysqli_num_rows($result_get_category) > 0)//if Query is successful 
				{
					$query_update_category = '
						UPDATE Menu_Category 
						SET name="'.addslashes($input[1]).'", note="'.addslashes($input[2]).'", cat_order="'.$cat_order.'", updated="'.$date.'" 
						WHERE rest_id="'.$rest_id.'" AND cat_id="'.$cat_id.'" 
						LIMIT 1
						';
					//$outputtext .= $query_update_category;
					$query_row = mysqli_query($dbc, $query_update_category);
					$cat_order++;
					unset($input[0]); // remove item at index 0
					unset($input[1]); // remove item at index 1
					unset($input[2]); // remove item at index 2
					$input = array_values($input); // "reindex" array
				}
				else
				{
					$query_set_category = '
						INSERT INTO Menu_Category 
								(rest_id, cat_id, name, note, cat_order, updated) 
						VALUES ("'.$rest_id.'", "'.$cat_id.'", "'.$input[1].'", "'.$input[2].'", "'.$cat_order.'", "'.$date.'")
						';
					$query_row = mysqli_query($dbc, $query_set_category);
					$cat_order++;
					unset($input[0]); // remove item at index 0
					unset($input[1]); // remove item at index 1
					unset($input[2]); // remove item at index 2
					$input = array_values($input); // "reindex" array
				}
			}
			elseif(strncmp($input[0],";;",2)==0)
			{
				$subcat_id = substr($input[0],2);
				$item_order = 1;
			
				$query_get_subcategory = '
					SELECT * FROM Menu_Subcategory 
					WHERE (rest_id="'.$rest_id.'" AND subcat_id="'.$subcat_id.'")
					';
				$result_get_subcategory = mysqli_query($dbc, $query_get_subcategory);
			
				if (@mysqli_num_rows($result_get_subcategory) > 0)//if Query is successful 
				{
					$query_update_subcategory = '
						UPDATE Menu_Subcategory 
						SET cat_id="'.$cat_id.'", name="'.$input[1].'", note="'.$input[7].'", subcat_order="'.$subcat_order.'", size_1="'.$input[2].'", size_2="'.$input[3].'", size_3="'.$input[4].'", size_4="'.$input[5].'", size_5="'.$input[6].'", updated="'.$date.'" WHERE rest_id="'.$rest_id.'" AND subcat_id="'.$subcat_id.'" 
						LIMIT 1
						';
					mysqli_query($dbc, $query_update_subcategory);
					$subcat_order++;
					unset($input[0]); // remove item at index 0
					unset($input[1]); // remove item at index 1
					unset($input[2]); // remove item at index 2
					unset($input[3]); // remove item at index 3
					unset($input[4]); // remove item at index 4
					unset($input[5]); // remove item at index 5
					unset($input[6]); // remove item at index 6
					unset($input[7]); // remove item at index 7
					$input = array_values($input); // "reindex" array
				}
				else
				{
					$query_set_subcategory = '
						INSERT INTO Menu_Subcategory 
								(rest_id, cat_id, subcat_id, name, note, subcat_order, size_1, size_2, size_3, size_4, size_5, updated) 
						VALUES ("'.$rest_id.'", "'.$cat_id.'", "'.$subcat_id.'", "'.$input[1].'", "'.$input[7].'", "'.$subcat_order.'", "'.$input[2].'", "'.$input[3].'", "'.$input[4].'", "'.$input[5].'", "'.$input[6].'", "'.$date.'")
						';
					mysqli_query($dbc, $query_set_subcategory);
					$subcat_order++;
					unset($input[0]); // remove item at index 0
					unset($input[1]); // remove item at index 1
					unset($input[2]); // remove item at index 2
					unset($input[3]); // remove item at index 3
					unset($input[4]); // remove item at index 4
					unset($input[5]); // remove item at index 5
					unset($input[6]); // remove item at index 6
					unset($input[7]); // remove item at index 7
					$input = array_values($input); // "reindex" array
				}
			}
			elseif(strncmp($input[0],",,",2)==0)
			{
				$item_id = substr($input[0],2);
			
				$query_get_item = '
					SELECT * FROM Menu_Item 
					WHERE (rest_id="'.$rest_id.'" AND item_id="'.$item_id.'")
					';
				$result_get_item = mysqli_query($dbc, $query_get_item);
				
				if (@mysqli_num_rows($result_get_item) > 0)//if Query is successful 
				{
					$query_update_item = '
						UPDATE Menu_Item 
						SET name="'.$input[1].'", descript="'.$input[7].'", item_order="'.$item_order.'", subcat_id="'.$subcat_id.'",  price_1="'.$input[2].'", price_2="'.$input[3].'", price_3="'.$input[4].'", price_4="'.$input[5].'", price_5="'.$input[6].'", allergen="'.$input[8].'", updated="'.$date.'" 
						WHERE rest_id="'.$rest_id.'" AND item_id="'.$item_id.'" 
						LIMIT 1
						';
					mysqli_query($dbc, $query_update_item);
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
					$input = array_values($input); // "reindex" array
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
					$input = array_values($input); // "reindex" array
				}
			}
		}
		$outputtext .= 'Your menu has been succesfully saved.';
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