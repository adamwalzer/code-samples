<?php

include "connect.php";

if(!$_SESSION)
{
	session_start();
}

if(!$loc_id)
{
	if($_SESSION['loc_id'])
	{
		$loc_id = $_SESSION['loc_id'];
	}
	else
	{
		$loc_id = 'ac_area';
	}
}

$outputtext .= "
	<div id='main'>
		<table>
			<tr>
				<td>
	";
	
$query_get_category = "
	SELECT DISTINCT category AS category 
	FROM Category 
	WHERE loc_id='$loc_id' 
	ORDER BY category ASC
	";
//$outputtext .= $query_get_category;

$result_get_category = mysqli_query($dbc, $query_get_category);
if(!$result_get_category)
{//If the QUery Failed 
	$outputtext .= 'Query Failed ';
}

if (@mysqli_num_rows($result_get_category) > 0)//if Query is successfull 
{ // A match was made.
	//$_SESSION = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
   
   $rest_count = 0;
	
		$catname = "All Restaurants";
		$query_get_restaurant = "
			SELECT * FROM Restaurant
			WHERE (loc_id='$loc_id' AND confirmed=1)
			ORDER BY name ASC
			";
		$result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
		
		$rest_rows = Array();
		
		if($_SESSION['delivery_preference']=='delivery')
   		{
   			$i = 0;
   			while($rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC))
			{
				$rest_id = $rest_row['rest_id'];
				$city = $_SESSION['current_city'];
				$state = $_SESSION['current_state'];
				$zip = $_SESSION['current_zip'];
				$query_get_delivery_info = "
					SELECT * FROM Delivery_Info 
					WHERE (rest_id='$rest_id' AND state='$state' AND (city='$city' OR zip='$zip'))
					";
				$result_get_delivery_info = mysqli_query($dbc, $query_get_delivery_info);
				if (@mysqli_num_rows($result_get_delivery_info) > 0)
				{
					$rest_rows[] = $rest_row;
				}
				$i++;
			}
   		}
   		else
   		{
   			$i = 0;
   			while($rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC))
			{
				$rest_rows[] = $rest_row;
				$i++;
			}
   		}
   		
   		if($_SESSION['open_preference']!='none')
   		{
			$count = count($rest_rows);
			for($i = 0; $i < $count; $i++)
			{
				$rest_id = $rest_rows[$i]['rest_id'];
				$day_num = date("w");
				$time = date("H:i:s", time());
			
				$query_get_open = "
					SELECT * FROM Hours 
					WHERE 
						( 
							rest_id='$rest_id' 
						AND 
							( 
								( 
									day_num='$day_num' 
								AND open<='$time' 
								AND 
									( 
										close>'$time' 
									OR 
										( 
											close_day_num>'$day_num' 
										OR 
											( 
												day_num='6' 
											AND close_day_num='0' 
											) 
										) 
									) 
								) OR 
									( 
										( 
											day_num<'$day_num' 
										OR 
											( 
												day_num='6' 
											AND close_day_num='0' 
											) 
										) AND 
											( 
												close_day_num='$day_num' 
											AND close>'$time' 
											) 
									) 
							) 
						)
					";
				$result_get_open = mysqli_query($dbc, $query_get_open);
			
				if(@mysqli_num_rows($result_get_open) == 0)
				{
					unset($rest_rows[$i]);
				}
			}
		}	
		
		$rest_rows = array_values($rest_rows);
		$rest_id = 0;
		
		if (count($rest_rows) > 0)//if Query is successfull 
		{	
			$outputtext .= "<h2>$catname</h2>\n";
			$outputtext .= "<div>\n";
			
			for($i = 0; $i< count($rest_rows); $i++)
			{
				$rest_id = $rest_rows[$i]['rest_id'];
				
				$query_get_name = "
					SELECT name FROM Restaurant 
					WHERE (loc_id='$loc_id' AND rest_id='$rest_id' AND confirmed=1)
					";
				$result_get_name = mysqli_query($dbc, $query_get_name);
				$name_row = mysqli_fetch_array($result_get_name, MYSQLI_ASSOC);
				$name = $name_row['name'];

				//$restaurant = $cat_row['category'];
				//<a href="javascript:setMain(`categoriespage.php?loc_id=$loc_row['loc_id']`)">$loc_row['location']</a><br>\n
				$outputtext .= " <a href='".'javascript:setMain("restaurantpage.php?loc_id='.$loc_id.'&rest_id='.$rest_id.'")'."'>$name</a><br>\n";
				
				$rest_count++;
				
				//$outputtext .= " ".$rest_row['rest_id']."<br>\n";
			}
			
			$rest_id = 0;
						 
			$outputtext .= "</div>\n";
		}
   
   $rest_count = 0;
   
	while($cat_row = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC))
	{
		$category = $cat_row['category'];
		$catname = str_replace("_"," ",$category);
		$query_get_restaurant = "
			SELECT * FROM Category 
			WHERE (loc_id='$loc_id' AND category='$category' AND confirmed=1)
			";
		$result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
		
		$rest_rows = Array();
		
		if($_SESSION['delivery_preference']=='delivery')
   		{
   			$i = 0;
   			while($rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC))
			{
				$rest_id = $rest_row['rest_id'];
				$city = $_SESSION['current_city'];
				$state = $_SESSION['current_state'];
				$zip = $_SESSION['current_zip'];
				$query_get_delivery_info = "
					SELECT * FROM Delivery_Info 
					WHERE (rest_id='$rest_id' AND state='$state' AND (city='$city' OR zip='$zip'))
					";
				$result_get_delivery_info = mysqli_query($dbc, $query_get_delivery_info);
				if (@mysqli_num_rows($result_get_delivery_info) > 0)
				{
					$rest_rows[] = $rest_row;
				}
				$i++;
			}
   		}
   		else
   		{
   			$i = 0;
   			while($rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC))
			{
				$rest_rows[] = $rest_row;
				$i++;
			}
   		}
   		
   		if($_SESSION['open_preference']!='none')
   		{
			$count = count($rest_rows);
			for($i = 0; $i< $count; $i++)
			{
				$rest_id = $rest_rows[$i]['rest_id'];
				$day_num = date("w");
				$time = date("H:i:s", time());
			
				$query_get_open = "
					SELECT * FROM Hours 
					WHERE ( rest_id='$rest_id' 
						AND ( ( day_num='$day_num' 
							AND open<='$time' 
							AND ( close>'$time' 
								OR ( close_day_num>'$day_num' 
									OR ( day_num='6' 
										AND close_day_num='0' 
										)
									)
								)
							) OR ( ( day_num<'$day_num' 
								OR ( day_num='6' 
									AND close_day_num='0'
									)
									) AND ( close_day_num='$day_num' 
											AND close>'$time' 
											)
									) 
								) 
							)
					";
				$result_get_open = mysqli_query($dbc, $query_get_open);
			
				if(@mysqli_num_rows($result_get_open) == 0)
				{
					unset($rest_rows[$i]);
				}
			}
		}	
		
		$rest_rows = array_values($rest_rows);
		$rest_id = 0;
		
		if (count($rest_rows) > 0)//if Query is successfull 
		{
			//$outputtext .= "<h2><a href='".'javascript:setMain("categorypage.php?loc_id='.$loc_id.'&category='.$category.'")'."'>$catname</a></h2>
			//					<div>\n";
			$outputtext .= "<h2>$catname</h2>
								<div>\n";
			
			$rest_link = Array();
			
			for($i = 0; $i< count($rest_rows); $i++)
			{
				$rest_id = $rest_rows[$i]['rest_id'];
				
				$query_get_info = "
					SELECT * FROM Restaurant 
					WHERE (loc_id='$loc_id' AND rest_id='$rest_id' AND confirmed=1)
					";
				$result_get_info = mysqli_query($dbc, $query_get_info);
				$info_row = mysqli_fetch_array($result_get_info, MYSQLI_ASSOC);
				$name = $info_row['name'];

				$rest_link[$name] = " <a href='".'javascript:setMain("restaurantpage.php?loc_id='.$loc_id.'&rest_id='.$rest_id.'")'."'>$name</a><br>\n";

				//$restaurant = $cat_row['category'];
				//<a href="javascript:setMain(`categoriespage.php?loc_id=$loc_row['loc_id']`)">$loc_row['location']</a><br>\n
				//$outputtext .= " <a href='".'javascript:setMain("restaurantpage.php?loc_id='.$loc_id.'&rest_id='.$rest_id.'")'."'>$name</a><br>\n";
				
				$rest_count++;
				
				//$outputtext .= " ".$rest_row['rest_id']."<br>\n";
			}
			
			ksort($rest_link);
			foreach($rest_link as $key => $value)
			{
				$outputtext .= $value;
			}
			
			$rest_id = 0;
						 
			$outputtext .= "
				<br>
				<br>
				</div>
				";
		}
	}
	
	if(!$rest_count)
	{
		$outputtext .= "<center>Your search returned no restaurants.</center>";
	}
}
else
{ 
	$outputtext .= "No Categories In Database";
}

$outputtext .= "
				</td>
				<td>
					<div id='map-canvas' style='height:400px; width:400px;' />
				</td>
			</tr>
		</table>
	</div>
	";

mysqli_close($dbc);

?>