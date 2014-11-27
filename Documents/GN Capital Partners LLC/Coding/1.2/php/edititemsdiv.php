<?php
	include "connect.php";
	
	if($_SESSION['rest_id'])
	{
		$loc_id = $_SESSION['loc_id'];
		$rest_id = $_SESSION['rest_id'];
	}
	else
	{
		$loc_id = $_GET['loc_id'];
		$rest_id = $_GET['rest_id'];
	}
	
	if($loc_id && $rest_id)
	{
        $query_get_restaurant = "
        	SELECT * FROM Restaurant 
        	WHERE (loc_id='$loc_id' AND rest_id='$rest_id') 
        	LIMIT 1
        	";
   
        $result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
        if(!$result_get_restaurant)
		{//If the QUery Failed 
            $outputtext .= 'Query Failed ';
        }

        if (@mysqli_num_rows($result_get_restaurant) == 1)//if Query is successfull 
        { // A match was made.
            $rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
        
			$outputtext .= "
				<div id='main'>
					<center>
				";
			
			$query_get_updated = "
				SELECT MAX(updated) AS updated 
				FROM Menu_Category WHERE (rest_id='$rest_id')
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
			
			$query_get_category = "
				SELECT * FROM Menu_Category 
				WHERE (rest_id='$rest_id' AND updated='$updated') 
				ORDER BY cat_order
				";
	   
			$result_get_category = mysqli_query($dbc, $query_get_category);
			if(!$result_get_category)
			{//If the QUery Failed 
				$outputtext .= 'Query Failed ';
			}

			if (@mysqli_num_rows($result_get_category) > 0)//if Query is successfull 
			{ // A match was made.
				
				$outputtext .= "
					<table id='menutable'>
						<colgroup>
							<col width='100%'>
							<col span='5' nowrap>
						</colgroup>
						";
				
				while($cat_row = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC))
				{
					$cat_id = $cat_row['cat_id'];
					$category = $cat_row['name'];
					$query_get_subcat = "
						SELECT * FROM Menu_Subcategory 
						WHERE (rest_id='$rest_id' AND cat_id='$cat_id' AND updated='$updated') 
						ORDER BY subcat_order
						";
					$result_get_subcat = mysqli_query($dbc, $query_get_subcat);
					
					if (@mysqli_num_rows($result_get_subcat) > 0)
					{
						$outputtext .= "
							<tr>
								<th class='firstcol'>$category <form id='edit' onSubmit='submitForm(this,".'"editCategoryDisableHoursPopup.php"'.");return false;'><input type='hidden' id='cat_id' value='".$cat_id."'><input type='hidden' id='category_name' value='".$category."'><input type='submit' id='registerbutton' value='Disable Hours' title='Disable Hours' /></form></th>
							</tr>
							";
						
						while($subcat_row = mysqli_fetch_array($result_get_subcat, MYSQLI_ASSOC))
						{
							$subcat_id = $subcat_row['subcat_id'];
							$subcategory = $subcat_row['name'];
							$query_get_item = "
								SELECT * FROM Menu_Item 
								WHERE (rest_id='$rest_id' AND subcat_id='$subcat_id' AND updated='$updated') 
								ORDER BY item_order
								";
							$result_get_item = mysqli_query($dbc, $query_get_item);
					
					
							if (@mysqli_num_rows($result_get_item) > 0)
							{
								$outputtext .= "
									<tr>
										<th class='firstcol'>$subcategory</th>
										<th>".$subcat_row['size_1']."</th>
										<th>".$subcat_row['size_2']."</th>
										<th>".$subcat_row['size_3']."</th>
										<th>".$subcat_row['size_4']."</th>
										<th>".$subcat_row['size_5']."</th>
									</tr>
									";
						
								while($item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC))
								{
									$item_id = $item_row['item_id'];
									$name = $item_row['name'];
							
									if($item_row['allergen'])
									{
										$allergen = "
											<br>
											allergen: ".$item_row['allergen']."
											";
									}
									else
									{
										$allergen = "";
									}
							
									if($item_row['price_1']!=0.00){$price_1="$".number_format($item_row['price_1'], 2, '.', '');}else{$price_1="";}
									if($item_row['price_2']!=0.00){$price_2="$".number_format($item_row['price_2'], 2, '.', '');}else{$price_2="";}
									if($item_row['price_3']!=0.00){$price_3="$".number_format($item_row['price_3'], 2, '.', '');}else{$price_3="";}
									if($item_row['price_4']!=0.00){$price_4="$".number_format($item_row['price_4'], 2, '.', '');}else{$price_4="";}
									if($item_row['price_5']!=0.00){$price_5="$".number_format($item_row['price_5'], 2, '.', '');}else{$price_5="";}
							
							
									$outputtext .= "
										<tr>
											<td class='firstcol'>
												<a href='http://gottanom.com/edititem.php?loc_id=".$loc_id.'&rest_id='.$rest_id.'&item_id='.$item_id."'>$name</a>
											</td>
											<td>$price_1</td>
											<td>$price_2</td>
											<td>$price_3</td>
											<td>$price_4</td>
											<td>$price_5</td>
										</tr>
										";
								}
						
								$outputtext .= "
									<tr><td><br></td></tr>
									<tr><td><br></td></tr>
									";
						
							}
						}
					}
				}
			}
        }
		else
		{ 
            $outputtext .= "No Restaurant Menu In Database";
        }
        
        $outputtext .= "
					</table>
            		<br>
            		<br>
				</center>
			</div>
			";
    }
	else
	{
		$outputtext .= "
			<div id='main'>
				No Restaurant ID
			</div>";
    }
	
    /// var_dump($error);
    mysqli_close($dbc);

?>