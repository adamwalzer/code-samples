<?php

if(!$_SESSION)
{
	session_start();
}

include "connect.php";

//$loc_id = $_GET['loc_id'];
if($_GET['loc_id'])
{
	$loc_id = $_GET['loc_id'];
}
elseif($_SESSION['loc_id'])
{
	$loc_id = $_SESSION['loc_id'];
}
else
{
	$loc_id = 'ac_area';
}
	
$query_get_category = "
	SELECT DISTINCT(category) AS category 
	FROM Category 
	WHERE loc_id = '$loc_id' 
	ORDER BY category ASC
	";

$result_get_category = mysqli_query($dbc, $query_get_category);
if(!$result_get_category) //If the Query Failed 
{
	$outputtext .= 'Query Failed ';
}

if (@mysqli_num_rows($result_get_category) > 0)//if Query is successfull 
{ // A match was made.
	//$_SESSION = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
   
   $outputtext .= "<div id='main_left' class='col span_1_of_4 span_1_to_2_of_4'>\n";
   $outputtext .= "<div id='main_left_content'>\n";
   
   $outputtext .= '
   <script>
	   //alert();
	   $(function() {
		$("#must_deliver").click(function()
		{
			//alert("#must_deliver");
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
			$.post("../php/setvariable.php", postdata, function(responseTxt)
			{
				//alert(responseTxt);
				
				executePage("categoriesdiv&loc_id='.$loc_id.'");
				executePage("leftcategories&loc_id='.$loc_id.'",{},"positionLeft();");
			});
		});
		$("#must_be_open").click(function()
		{
			//alert("#must_be_open");
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
			$.post("./php/setvariable.php", postdata, function(responseTxt)
			{
				executePage("categoriesdiv&loc_id='.$loc_id.'");
				executePage("leftcategories&loc_id='.$loc_id.'",{},"positionLeft();");
			});
		});
	  });
  </script>
  ';
   
   
   // $outputtext .= "session order_id: ".$_SESSION['order_id'];
   //$outputtext .= "<div id='main_left' class='col span_1_of_4'>\n";
   $outputtext .= "<center><a href='".'javascript:executePage("categoriesdiv")'."'><h2>Categories</h2></a>\n";
   $outputtext .= "<input type='checkbox' id='must_be_open' ";
   if($_SESSION['open_preference']!='none')
   {
   		$outputtext .= "checked='checked' ";
   }
   $outputtext .= "/>open now<br />\n";
   $outputtext .= "<input type='checkbox' id='must_deliver' ";
   if($_SESSION['delivery_preference']=='delivery')
   {
   		$outputtext .= "checked='checked' ";
   }
   $outputtext .= "/>delivers to<br /><div id='current_address_div'><a href='javascript:executePage(".'"editcurrentaddresspopup"'.")'>";
   
   if(!$_SESSION['current_address'])
   {
		$outputtext .= "No Address Provided";
   }
   else
   {
	   if($_SESSION['current_on_beach']=='checked')
	   {
			$outputtext .= "On The Beach At ";
	   }
   
	   $outputtext .= $_SESSION['current_address']."<br />".$_SESSION['current_city'].", ".$_SESSION['current_state']." ".$_SESSION['current_zip'];
   }
   
   $outputtext .= "</a></div><br />\n";
   
   $outputtext .= "<div id='left_categories'>\n";
	
	$rest_count = 0;
	
		$category =  "All Restaurants";
		$catname =  "All_Restaurants";
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
				$row_rest_id = $rest_row['rest_id'];
				$city = $_SESSION['current_city'];
				$state = $_SESSION['current_state'];
				$zip = $_SESSION['current_zip'];
				$query_get_delivery_info = "
					SELECT * FROM Delivery_Info 
					WHERE (rest_id='$row_rest_id' AND state='$state' AND (city='$city' OR zip='$zip'))
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
				$row_rest_id = $rest_rows[$i]['rest_id'];
				$day_num = date("w");
				$time = date("H:i:s", time());
			
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
			
				if(@mysqli_num_rows($result_get_open) == 0)
				{
					unset($rest_rows[$i]);
				}
			}
		}	
		
		$rest_rows = array_values($rest_rows);
		$row_rest_id = 0;
		
		if (count($rest_rows) > 0)//if Query is successfull 
		{	
			// "<option selected='selected' value='".$stateletters."'>".$states[$stateletters]."</option>";
			$selecttext .= "<option slected='selected' value='#".$catname."'>$category</option>\n";
			//$selecttext .= "<li><a href='#".$catname."'>$category</a></li>\n";
			$divtext .= "<div id='".$catname."'>\n";
			
			for($i = 0; $i< count($rest_rows); $i++)
			{
				$row_rest_id = $rest_rows[$i]['rest_id'];
				
				$query_get_name = "
					SELECT name FROM Restaurant 
					WHERE (loc_id='$loc_id' AND rest_id='$row_rest_id' AND confirmed=1)
					";
				$result_get_name = mysqli_query($dbc, $query_get_name);
				$name_row = mysqli_fetch_array($result_get_name, MYSQLI_ASSOC);
				$name = htmlentities($name_row['name']);

				//$restaurant = $cat_row['category'];
				//<a href="javascript:setMain(`categoriespage.php?loc_id=$loc_row['loc_id']`)">$loc_row['location']</a><br />\n
				$divtext .= " <a href='".'javascript:submitForm(this,"viewrestaurantdiv&amp;loc_id='.$loc_id.'&amp;rest_id='.$row_rest_id.'")'."'>$name</a><br />\n";
				
				$rest_count++;
				
				//$outputtext .= " ".$rest_row['rest_id']."<br />\n";
			}
			
			$row_rest_id = 0;
						 
			$divtext .= "</div>\n";
		}
   
   $rest_count = 0;
   
	while($cat_row = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC))
	{
		$category = $cat_row['category'];
		$catname = str_replace(" ","_",$cat_row['category']);
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
				$row_rest_id = $rest_row['rest_id'];
				$city = $_SESSION['current_city'];
				$state = $_SESSION['current_state'];
				$zip = $_SESSION['current_zip'];
				$query_get_delivery_info = "
					SELECT * FROM Delivery_Info 
					WHERE (rest_id='$row_rest_id' AND state='$state' AND (city='$city' OR zip='$zip'))
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
				$row_rest_id = $rest_rows[$i]['rest_id'];
				$day_num = date("w");
				$time = date("H:i:s", time());
			
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
			
				if(@mysqli_num_rows($result_get_open) == 0)
				{
					unset($rest_rows[$i]);
				}
			}
		}	
		
		$rest_rows = array_values($rest_rows);
		$row_rest_id = 0;
		
		if (count($rest_rows) > 0)//if Query is successfull 
		{	
			$selecttext .= "<option slected='selected' value='#".$catname."'>$category</option>\n";
			//$selecttext .= "<li><a href='#".$catname."'>$category</a></li>\n";
			$divtext .= "<div id='".$catname."'>\n";
			
			$rest_link = Array();
			
			for($i = 0; $i< count($rest_rows); $i++)
			{
				$row_rest_id = $rest_rows[$i]['rest_id'];
				
				$query_get_name = "
					SELECT name FROM Restaurant 
					WHERE (loc_id='$loc_id' AND rest_id='$row_rest_id' AND confirmed=1)
					";
				$result_get_name = mysqli_query($dbc, $query_get_name);
				$name_row = mysqli_fetch_array($result_get_name, MYSQLI_ASSOC);
				$name = htmlentities($name_row['name']);
				
				$rest_link[$name] = " <a href='".'javascript:submitForm(this,"viewrestaurantdiv&amp;loc_id='.$loc_id.'&amp;rest_id='.$row_rest_id.'")'."'>$name</a><br/>\n";

				//$restaurant = $cat_row['category'];
				//<a href="javascript:setMain(`categoriespage.php?loc_id=$loc_row['loc_id']`)">$loc_row['location']</a><br />\n
				//$outputtext .= " <a href='".'javascript:setMain("restaurantpage.php?loc_id='.$loc_id.'&rest_id='.$row_rest_id.'")'."'>$name</a><br />\n";
				
				$rest_count++;
				
				//$outputtext .= " ".$rest_row['rest_id']."<br />\n";
			}
			
			ksort($rest_link);
			foreach($rest_link as $key => $value)
			{
				$divtext .= $value;
			}
			
			$row_rest_id = 0;
						 
			$divtext .= "</div>\n";
		}
	}
	
	if(!$rest_count)
	{
		$outputtext .= "<br />Your search returned no restaurants.";
	}
	else
	{
		
		$outputtext .= "<select type='text' id='category_select' onchange='changeCategory($(this));' >
							".$selecttext."
						</select>
						".$divtext."
					";
		
		/*			
		$outputtext .= "<ul>
							".$selecttext."
						</ul>
						".$divtext."
					";
		*/
	}
	
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
else
{ 
	$outputtext .= $loc_id." ";
	$outputtext .= "No Categories In Database";
}

//mysqli_close($dbc);

?>