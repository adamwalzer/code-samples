<?php

include "connect.php";
	
if($_GET['loc_id'])
{
	$loc_id = $_GET['loc_id'];
}
elseif($_SESSION['loc_id'])
{
	$loc_id = $_SESSION['loc_id'];
}

if($_GET['rest_id'])
{
	$rest_id = $_GET['rest_id'];
}
elseif($_SESSION['rest_id'])
{
	$rest_id = $_SESSION['rest_id'];
}
else
{
	$rest_id = 0;
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
				<div id='main_right' class='col span_3_of_4 content' scrollTo='true'>
					<a name='main_right' />
				";
			
			include "viewrestauranttop.php";
			
			$outputtext .= "
					<br/>
					<br/>
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
					
					$day_num = date("w");
					$time = date("H:i:s", time());
					$query_get_disable = "
						SELECT * FROM Menu_Category_Disable_Hours 
						WHERE 
						( 
							rest_id='$rest_id' 
						AND cat_id='$cat_id' 
						AND 
						( 
							( 
								day_num='$day_num' 
							AND start<='$time' 
							AND 
								( 
									stop>'$time' 
								OR 
								( 
									stop_day_num>'$day_num' 
								OR 
								( 
									day_num='6' 
								AND stop_day_num='0' 
								) 
								) 
								) 
							) 
							OR 
							( 
								( 
									day_num<'$day_num' 
								OR 
								( 
									day_num='6' 
								AND stop_day_num='0' 
								) 
								) 
							AND 
							( 
								stop_day_num='$day_num' 
							AND stop>'$time' 
							) 
							) 
						) 
						)
						";
					$result_get_disable = mysqli_query($dbc, $query_get_disable);
			
					//$outputtext .= $day_num." ";
					//$outputtext .= $time;
					if(@mysqli_num_rows($result_get_disable) > 0)
					{
						$open = "close";
						$category .= " - This portion of the menu is not available at this time";
					}
					else
					{
						$open = "open";
					}
					
					if (@mysqli_num_rows($result_get_subcat) > 0)
					{
						$outputtext .= "
								<div class='category'><h2>".htmlentities($category)."</h2></div>
							";
							
						if($subcat_row['note'])
						{
							$outputtext .= "
									<div class='notes'>".htmlentities($cat_row['note'])."</div>
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
										<h3 class='subcategory $open' name='subcat_".$subcat_id."' onClick='toggleSubcategory(this)'><div><span class='ui-icon ui-icon-circle-arrow-s'></span></div>&nbsp;<div>".htmlentities($subcategory)."</div></h3>
										<div id='subcat_".$subcat_id."' class='section group'>
										";
										
								if($subcat_row['note'])
								{
									$outputtext .= "
											<div class='notes col span_2_of_2'>".htmlentities($subcat_row['note'])."<br/><br/></div>
										";
								}
								
								$outputtext .= "
										<div class='col span_1_of_4'>&nbsp;</div>
										<div class='col span_3_of_4 prices'>
											<div class='col span_1_of_5'>&nbsp;".htmlentities($subcat_row['size_1'])."</div>
											<div class='col span_1_of_5'>&nbsp;".htmlentities($subcat_row['size_2'])."</div>
											<div class='col span_1_of_5'>&nbsp;".htmlentities($subcat_row['size_3'])."</div>
											<div class='col span_1_of_5'>&nbsp;".htmlentities($subcat_row['size_4'])."</div>
											<div class='col span_1_of_5'>&nbsp;".htmlentities($subcat_row['size_5'])."</div>
										</div>
									";
								
								$row = 0;
								while($item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC))
								{
									$item_id = $item_row['item_id'];
									$name = htmlentities($item_row['name']);
									$encodedname = htmlspecialchars($name, ENT_QUOTES);
							
									if($item_row['price_1']!=0.00){$price_1="$".number_format($item_row['price_1'], 2, '.', '');}else{$price_1="";}
									if($item_row['price_2']!=0.00){$price_2="$".number_format($item_row['price_2'], 2, '.', '');}else{$price_2="";}
									if($item_row['price_3']!=0.00){$price_3="$".number_format($item_row['price_3'], 2, '.', '');}else{$price_3="";}
									if($item_row['price_4']!=0.00){$price_4="$".number_format($item_row['price_4'], 2, '.', '');}else{$price_4="";}
									if($item_row['price_5']!=0.00){$price_5="$".number_format($item_row['price_5'], 2, '.', '');}else{$price_5="";}
							
							
									$outputtext .= "
										<div class='section group row_".$row."'>
											<div class='col span_1_of_4 item'>
												<a onclick='executePage(".'"itempopup&loc_id=' . $loc_id . '&rest_id=' . $rest_id . '&item_id=' . $item_id . '"'.")'>$name</a>
											</div>
											<div  class='col span_3_of_4 prices'>
												<div class='col span_1_of_5'>&nbsp;$price_1</div>
												<div class='col span_1_of_5'>&nbsp;$price_2</div>
												<div class='col span_1_of_5'>&nbsp;$price_3</div>
												<div class='col span_1_of_5'>&nbsp;$price_4</div>
												<div class='col span_1_of_5'>&nbsp;$price_5</div>
											</div>
											";
									
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
												<div  class='col span_3_of_4 price_array'>
													$".$minimum_price."
												</div>
												";
										}
										else
										{
											$outputtext .= "
												<div  class='col span_3_of_4 price_array'>
													$".$minimum_price."-$".$maximum_price."
												</div>
												";
										}
									}
									else
									{
										$outputtext .= "
											<div  class='col span_3_of_4 price_array'>
												&nbsp;
											</div>
											";
									}
										
									$outputtext .= "
										</div>
										";
									
									$row++;
									$row = $row % 2;
								}
						
								$outputtext .= "
											<br/>
											<br/>
										</div>
									";
						
							}
						}
					}
				}
			}
			
			$outputtext .= "
						<br/>
						<br/>
					</center>
				</div>
				";
			
        }
		else
		{ 
            $outputtext .= "
            	<div id='main_right' class='col span_3_of_4 content' scrollTo='true'>
	            	No Restaurant Menu In Database
	            </div>	
	            ";
        }
    }
	else
	{
		$outputtext .= "
			<div id='main_right' class='col span_3_of_4 content' scrollTo='true'>
				No Restaurant ID
			</div>";
    }
	
    /// var_dump($error);
    mysqli_close($dbc);

?>