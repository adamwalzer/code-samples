<?php
	include "connect.php";
	
	if($_GET['loc_id'])
	{
		$loc_id = $_GET['loc_id'];
		$rest_id = $_GET['rest_id'];
	}
	elseif($_SESSION['rest_id'])
	{
		$loc_id = $_SESSION['loc_id'];
		$rest_id = $_SESSION['rest_id'];
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
				FROM Menu_Category 
				WHERE (rest_id='$rest_id')
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
							<col width='30%'>
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
								<th class='category'>$category</th>
							</tr>
							";
							
						if($subcat_row['note'])
						{
							$outputtext .= "
								<tr>
									<td class='notes' colspan='6'>".$cat_row['note']."</td>
								</tr>
								";
						}
						
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
										<th class='subcategory'>$subcategory</th>
										<th>&nbsp;</th>
									</tr>
									";
									
								if($subcat_row['note'])
								{
									$outputtext .= "
										<tr>
											<td class='notes' colspan='6'>".$subcat_row['note']."</td>
										</tr>
										";
								}
						
								while($item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC))
								{
									$item_id = $item_row['item_id'];
									$name = $item_row['name'];
									$encodedname = rawUrlEncode($name);

									$price_array = array();

									for ($i = 1; $i <= 5; $i++)
									{
										$price_text = "price_".$i;
										if($item_row[$price_text] != 0.00)
										{
											$price_array[] = $item_row[$price_text];
										}
									}
									
									if($price_array)
									{
										$minimum_price = min($price_array);
										$maximum_price = max($price_array);
										
										if($minimum_price==$maximum_price)
										{
										
											$outputtext .= "
												<tr>
													<td class='firstcol'>
														<a href='javascript:popupItem(".'"'.$loc_id.'","'.$rest_id.'","'.$item_id.'","'.$encodedname.'"'.")'>$name</a>
													</td>
													<td>$".$minimum_price."</td>
												</tr>
												";
										}
										else
										{
											$outputtext .= "
												<tr>
													<td class='firstcol'>
														<a href='javascript:popupItem(".'"'.$loc_id.'","'.$rest_id.'","'.$item_id.'","'.$encodedname.'"'.")'>$name</a>
													</td>
													<td>$".$minimum_price."-$".$maximum_price."</td>
												</tr>
												";
										}
									}
									else
									{
										$outputtext .= "
											<tr>
												<td class='firstcol'>
													<a href='javascript:popupItem(".'"'.$loc_id.'","'.$rest_id.'","'.$item_id.'","'.$encodedname.'"'.")'>$name</a>
												</td>
												<td>&nbsp;</td>
											</tr>
											";
									}
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
    //mysqli_close($dbc);

?>