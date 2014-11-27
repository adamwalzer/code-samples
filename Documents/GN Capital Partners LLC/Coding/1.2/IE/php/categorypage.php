<?php
	include_once "connect.php";
	
	$category = $_GET['category'];
	$catname = str_replace("_"," ",$category);
	$loc_id = $_GET['loc_id'];
	
	if($category && $loc_id)
	{
        $query_get_restaurants = "
        	SELECT DISTINCT(rest_id) AS rest_id 
        	FROM Category 
        	WHERE (loc_id='$loc_id' AND category='$category' AND confirmed=1) 
        	ORDER BY rest_id ASC
        	";
   		
		//$outputtext .= $query_get_restaurants;
   
        $result_get_restaurants = mysqli_query($dbc, $query_get_restaurants);
        if(!$result_get_restaurants)
		{//If the QUery Failed 
           $outputtext .= 'Query Failed ';
        }

        if (@mysqli_num_rows($result_get_restaurants) > 0)//if Query is successfull 
        { // A match was made.
            //$_SESSION = mysqli_fetch_array($result_check_credentials, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
           
           $outputtext .= "<div id='top'>
					   <table id='topbartable'>
							<tr>
								<td valign='middle' align='center' style='font-size:35px;'>
									$catname Restaurants
								</td>
							</tr>
						</table>
					</div>
				<div id='main'>
				";
           
			while($cat_row = mysqli_fetch_array($result_get_restaurants, MYSQLI_ASSOC))
			{
				$rest_id = $cat_row['rest_id'];
				$query_get_restaurant = "
					SELECT * FROM Restaurant 
					WHERE (loc_id='$loc_id' AND rest_id='$rest_id' AND confirmed=1)
					";
				$result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
				
				if (@mysqli_num_rows($result_get_restaurant) > 0)//if Query is successfull 
				{
		
					//while($rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC))
					//{
					//	$outputtext .= " ".$rest_id."<br>\n";
					//}
					
					$result_get_name = mysqli_query($dbc, $query_get_restaurant);
					$rest_row = mysqli_fetch_array($result_get_name, MYSQLI_ASSOC);
					$name = $rest_row['name'];
					
					$outputtext .= " <a href='".'javascript:setMain("restaurantpage.php?loc_id='.$loc_id.'&rest_id='.$rest_id.'")'."'>$name</a><br>\n";	 
				}
			}
			$outputtext .= "</div>\n";
        }
		else
		{ 
            $outputtext .= "No Restaurant In Database";
        }
    }
	else
	{
		$outputtext .= "No Category Chosen";
    }
	
	echo $outputtext;
	
    /// var_dump($error);
    mysqli_close($dbc);
?>