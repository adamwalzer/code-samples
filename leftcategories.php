<?php

if(!$_SESSION) // if there is no session variables then start the session.
{
	session_start();
}

// connect to the database.
include "connect.php";

if($_GET['loc_id']) // if there is a loc_id get variable then set $loc_id as that variables.
{
	$loc_id = $_GET['loc_id'];
}
elseif($_SESSION['loc_id']) // if there is a loc_id session variable then set $loc_id as that variables.
{
	$loc_id = $_SESSION['loc_id'];
}
else // otherwise set the $loc_id variable as 'ac_area'.
{
	$loc_id = 'ac_area';
}

// create query string to find each distict category with the correct loc_id.
$query_get_category = "
	SELECT DISTINCT(category) AS category 
	FROM Category 
	WHERE loc_id = '$loc_id' 
	ORDER BY category ASC
	";

// execute the query in the database.
$result_get_category = mysqli_query($dbc, $query_get_category);
if(!$result_get_category) //If the Query Failed add to outputtext
{
	$outputtext .= 'Query Failed ';
}

if (@mysqli_num_rows($result_get_category) > 0)//if rows in the database match the query 
{
	// start the main_left div and the main_left_content div.
	$outputtext .= "<div id='main_left' class='col span_1_of_4 span_1_to_2_of_4'>\n";
	$outputtext .= "<div id='main_left_content' class='content'>\n";
	
	
	$outputtext .= '
	<script>
	   $(function() {
		$("#must_deliver").click(function()
		{
			// if this checkbox is checked set SESSION["delivery_preference"] to "delivery". if its not set it to "pickup".
			if(this.checked)
			{
				SESSION["delivery_preference"]="delivery";
			}
			else
			{
				SESSION["delivery_preference"]="pickup";
			}
			var postdata = {};
			postdata["delivery_preference"] = SESSION["delivery_preference"];
			// php session variables and execute pages categoriesdiv and leftcategory div with loc_id
			$.post("../php/setvariable.php", postdata, function(responseTxt)
			{
				executePage("categoriesdiv&loc_id='.$loc_id.'");
				executePage("leftcategories&loc_id='.$loc_id.'",{},"positionLeft();");
			});
		});
		$("#must_be_open").click(function()
		{
			// if this checkbox is checked set SESSION["open_preference"] to "open". if its unchecked set it to "none"
			if(this.checked)
			{
				SESSION["open_preference"]="open";
			}
			else
			{
				SESSION["open_preference"]="none";
			}
			var postdata = {};
			postdata["open_preference"] = SESSION["open_preference"];
			// php session variables and execute pages categoriesdiv and leftcategory div with loc_id
			$.post("./php/setvariable.php", postdata, function(responseTxt)
			{
				executePage("categoriesdiv&loc_id='.$loc_id.'");
				executePage("leftcategories&loc_id='.$loc_id.'",{},"positionLeft();");
			});
		});
	  });
	</script>
	';
	
	// add link to categoriesdiv
	$outputtext .= "<center><h2>Search For...</h2>\n";
	
	// add input checkbox for open preference. make it checked if it's perfered to open show open restaurants.
	$outputtext .= "<input type='checkbox' id='must_be_open' ";
	if($_SESSION['open_preference']!='none')
	{
		$outputtext .= "checked='checked' ";
	}
	$outputtext .= "/>open now<br />\n";
	
	// add input checkbox for delivery preference. make it checked if it's prefered to show only restaurants that deliver to current location.
	$outputtext .= "<input type='checkbox' id='must_deliver' ";
	if($_SESSION['delivery_preference']=='delivery')
	{
		$outputtext .= "checked='checked' ";
	}
	$outputtext .= "/>delivers to<br /><div id='current_address_div'><a href='javascript:executePage(".'"editcurrentaddresspopup"'.")'>";
	
	
	if(!$_SESSION['current_address']) // if there is no current address set say there's no address provided.
	{
		$outputtext .= "No Address Provided";
	}
	else // else display the address provided.
	{
		// say if address is on the beach.
		if($_SESSION['current_on_beach']=='checked')
		{
			$outputtext .= "On The Beach At ";
		}
		
		// write address.
		$outputtext .= $_SESSION['current_address']."<br />".$_SESSION['current_city'].", ".$_SESSION['current_state']." ".$_SESSION['current_zip'];
	}

	$outputtext .= "</a></div><br />\n";

	$outputtext .= "<div id='left_categories'>\n";
	
	$outputtext .= "<center><a href='".'javascript:executePage("categoriesdiv&loc_id='.$loc_id.'")'."'><h2>Categories</h2></a>\n";

	// set rest_count and category to All Restaurants
	$rest_count = 0;
	
	$category =  "All Restaurants";
	$catname =  "All_Restaurants";
	
	// query database for all restaurants in location
	$query_get_restaurant = "
		SELECT * FROM Restaurant
		WHERE (loc_id='$loc_id' AND confirmed=1)
		ORDER BY name ASC
		";
	$result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
	
	$rest_rows = Array();
	
	if($_SESSION['delivery_preference']=='delivery') // if restaurants must deliver to location
	{
		$i = 0;
		while($rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC)) // go through each restaurant in location
		{
			// see if the restaurant delivers to current address.
			$row_rest_id = $rest_row['rest_id'];
			$city = $_SESSION['current_city'];
			$state = $_SESSION['current_state'];
			$zip = $_SESSION['current_zip'];
			$query_get_delivery_info = "
				SELECT * FROM Delivery_Info 
				WHERE (rest_id='$row_rest_id' AND state='$state' AND (city='$city' OR zip='$zip'))
				";
			$result_get_delivery_info = mysqli_query($dbc, $query_get_delivery_info);
			if (@mysqli_num_rows($result_get_delivery_info) > 0) // if the restaurant has delivery info for this location
			{
				// add array of restaurant info to the array
				$rest_rows[] = $rest_row;
			}
			$i++;
		}
	}
	else // if the restaurants don't have to deliver
	{
		$i = 0;
		while($rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC)) // go through each restaurant in location
		{
			// add array of restaurant info to the array
			$rest_rows[] = $rest_row;
			$i++;
		}
	}

	if($_SESSION['open_preference']!='none') // if the restaurant must be open
	{
		$count = count($rest_rows);
		for($i = 0; $i < $count; $i++) // go through array of restaurant info
		{
			// see if the restaurant is open right now
			$row_rest_id = $rest_rows[$i]['rest_id'];
			$day_num = date("w");
			$time = date("H:i:s", time());
			
			/*
				search in Hours table for restaurants that
					match the rest_id
					AND
						open today
						before now
						AND
							don't close before now
							OR
							don't close until tomorrow
						OR
						opened yesterday
						AND
							close today
							at a time after now
			*/
			$query_get_open = "
				SELECT * FROM Hours 
				WHERE 
					( 
						rest_id='$row_rest_id' 
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
	
			if(@mysqli_num_rows($result_get_open) == 0) // if it's not open remove it from the array.
			{
				unset($rest_rows[$i]);
			}
		}
	}	
	
	$rest_rows = array_values($rest_rows);
	$row_rest_id = 0;

	if (count($rest_rows) > 0) // if there are still restaurants in the array 
	{	
		// make this category an option
		$selecttext .= "<option slected='selected' value='#".$catname."'>$category</option>\n";
		$divtext .= "<div id='".$catname."' class='restaurant_category' >\n";
		
		for($i = 0; $i< count($rest_rows); $i++) // go through array
		{
			// find restaurant in database
			$row_rest_id = $rest_rows[$i]['rest_id'];
			
			$query_get_name = "
				SELECT name FROM Restaurant 
				WHERE (loc_id='$loc_id' AND rest_id='$row_rest_id' AND confirmed=1)
				";
			$result_get_name = mysqli_query($dbc, $query_get_name);
			$name_row = mysqli_fetch_array($result_get_name, MYSQLI_ASSOC);
			$name = htmlentities($name_row['name']);
			
			// add link to restaurant in the div.
			$divtext .= " <a href='".'javascript:submitForm(this,"viewrestaurantdiv&amp;loc_id='.$loc_id.'&amp;rest_id='.$row_rest_id.'")'."'>$name</a><br />\n";
		
			$rest_count++;
		}
	
		$row_rest_id = 0;
				 
		$divtext .= "</div>\n";
	}
	
	// set rest_count to zero
	$rest_count = 0;
	
	while($cat_row = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC)) // while there are categories in this area
	{
		// find restaurants in this category in this location
		$category = $cat_row['category'];
		$catname = str_replace(" ","_",$cat_row['category']);
		$query_get_restaurant = "
			SELECT * FROM Category 
			WHERE (loc_id='$loc_id' AND category='$category' AND confirmed=1)
			";
		$result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
	
		$rest_rows = Array();
		
		if($_SESSION['delivery_preference']=='delivery') // if restaurants must deliver to location
		{
			$i = 0;
			while($rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC)) // go through each restaurant in location
			{
				// see if the restaurant delivers to current address.
				$row_rest_id = $rest_row['rest_id'];
				$city = $_SESSION['current_city'];
				$state = $_SESSION['current_state'];
				$zip = $_SESSION['current_zip'];
				$query_get_delivery_info = "
					SELECT * FROM Delivery_Info 
					WHERE (rest_id='$row_rest_id' AND state='$state' AND (city='$city' OR zip='$zip'))
					";
				$result_get_delivery_info = mysqli_query($dbc, $query_get_delivery_info);
				if (@mysqli_num_rows($result_get_delivery_info) > 0) // if the restaurant has delivery info for this location
				{
					// add array of restaurant info to the array
					$rest_rows[] = $rest_row;
				}
				$i++;
			}
		}
		else // if the restaurants don't have to deliver
		{
			$i = 0;
			while($rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC)) // go through each restaurant in location
			{
				// add array of restaurant info to the array
				$rest_rows[] = $rest_row;
				$i++;
			}
		}
	
		if($_SESSION['open_preference']!='none') // if the restaurant must be open
		{
			$count = count($rest_rows);
			for($i = 0; $i < $count; $i++) // go through array of restaurant info
			{
				// see if the restaurant is open right now
				$row_rest_id = $rest_rows[$i]['rest_id'];
				$day_num = date("w");
				$time = date("H:i:s", time());
				
				/*
					search in Hours table for restaurants that
						match the rest_id
						AND
							open today
							before now
							AND
								don't close before now
								OR
								don't close until tomorrow
							OR
							opened yesterday
							AND
								close today
								at a time after now
				*/
				$query_get_open = "
					SELECT * FROM Hours 
					WHERE 
						( 
							rest_id='$row_rest_id' 
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
		
				if(@mysqli_num_rows($result_get_open) == 0) // if it's not open remove it from the array.
				{
					unset($rest_rows[$i]);
				}
			}
		}	
	
		$rest_rows = array_values($rest_rows);
		$row_rest_id = 0;
	
		if (count($rest_rows) > 0) // if there are still restaurants in the array 
		{	
			$selecttext .= "<option slected='selected' value='#".$catname."'>$category</option>\n";
			//$selecttext .= "<li><a href='#".$catname."'>$category</a></li>\n";
			$divtext .= "<div id='".$catname."' class='restaurant_category' >\n";
		
			$rest_link = Array();
		
			for($i = 0; $i< count($rest_rows); $i++) // go through the array
			{
				// find restaurant in database
				$row_rest_id = $rest_rows[$i]['rest_id'];
			
				$query_get_name = "
					SELECT name FROM Restaurant 
					WHERE (loc_id='$loc_id' AND rest_id='$row_rest_id' AND confirmed=1)
					";
				$result_get_name = mysqli_query($dbc, $query_get_name);
				$name_row = mysqli_fetch_array($result_get_name, MYSQLI_ASSOC);
				$name = htmlentities($name_row['name']);
				
				// add a link to the restaurant to the rest_link array
				$rest_link[$name] = " <a href='".'javascript:submitForm(this,"viewrestaurantdiv&amp;loc_id='.$loc_id.'&amp;rest_id='.$row_rest_id.'")'."'>$name</a><br/>\n";

				$rest_count++;
			}
			
			// add restaurant links to the div text
			ksort($rest_link);
			foreach($rest_link as $key => $value)
			{
				$divtext .= $value;
			}
		
			$row_rest_id = 0;
					 
			$divtext .= "</div>\n";
		}
	}
	
	if(!$rest_count) // inform user if their search returned no restaurants
	{
		$outputtext .= "<br />Your search returned no restaurants.";
	}
	else // or create select with the options of each category and a write div text to outputtext
	{
		
		$outputtext .= "<select type='text' id='category_select' onchange='changeCategory($(this));' >
							".$selecttext."
						</select>
						".$divtext."
					";
	}
	
	// end html tags
	$outputtext .= "
					</div>
					<br/>
					<br/>
				</center>
				<script>
					changeCategory($('#category_select'));
				</script>
				</div>
				<br/>
				</div>
		";
}
else // otherwise let user know there are no categories in the database for this location
{ 
	$outputtext .= $loc_id." ";
	$outputtext .= "No Categories In Database";
}

// close the database connection
mysqli_close($dbc);

?>