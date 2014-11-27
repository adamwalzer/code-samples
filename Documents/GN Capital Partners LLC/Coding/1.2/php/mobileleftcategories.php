<?php

if(!$_SESSION)
{
	session_start();
}

include "connect.php";

//$loc_id = $_GET['loc_id'];
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
	
$query_get_category = "
	SELECT DISTINCT(category) AS category 
	FROM Category 
	WHERE loc_id = '$loc_id' 
	ORDER BY category ASC
	";

$result_get_category = mysqli_query($dbc, $query_get_category);
if(!$result_get_category)
{//If the QUery Failed 
	$outputtext .= 'Query Failed ';
}

if (@mysqli_num_rows($result_get_category) > 0)//if Query is successfull 
{ // A match was made.
	//$_SESSION = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
  
  $outputtext .= '
   <script>
	   $(function() {
		var icns = {
		  header: "ui-icon-circle-arrow-e",
		  activeHeader: "ui-icon-circle-arrow-s"
		};
		$("#accordion").accordion({ icons: icns, header: "h3", collapsible: true, active: false, heightStyle: "content" });
		//$(".ui-accordion-container").accordion({ active: "a.default", ...,  header: "a.accordion-label" });
		$("#accordion h3 a").click(function() {
			window.location = $(this).attr("href");
			$("#accordion").accordion({ event: "click" }).activate(2);
			return false;
		});
		$( "#tabs" ).tabs();
		$("#must_deliver").click(function()
		{
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
				$("#mainleft").load("./php/mobileleftcategoriespage.php");
			});
		});
		$("#must_be_open").click(function()
		{
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
			$.post("../php/setvariable.php", postdata, function(responseTxt)
			{
				//alert(responseTxt);
				$("#mainleft").load("./php/mobileleftcategoriespage.php");
			});
		});
	  });
  </script>
  ';
   
   // $outputtext .= "session order_id: ".$_SESSION['order_id'];
   $outputtext .= "<center><br><input type='checkbox' id='must_be_open' ";
   if($_SESSION['open_preference']!='none')
   {
   		$outputtext .= "checked ";
   }
   $outputtext .= ">open now<br></center>\n";
   $outputtext .= "<center><input type='checkbox' id='must_deliver' ";
   if($_SESSION['delivery_preference']=='delivery')
   {
   		$outputtext .= "checked ";
   }
   $outputtext .= ">delivers to<br><a href='javascript:popupEditCurrentAddress()'>";
   
   if($_SESSION['current_on_beach']=='checked')
   {
   		$outputtext .= "On The Beach At ";
   }
   
   $outputtext .= $_SESSION['current_address']."<br>".$_SESSION['current_city'].", ".$_SESSION['current_state']." ".$_SESSION['current_zip']."</a><br></center>\n";
   
	$outputtext .= "<div id='accordion'>\n";
	
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
			$outputtext .= "<h3>$catname</h3>\n";
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
				$outputtext .= " <a href='".'javascript:setMobile("mobilerestaurantpage.php?loc_id='.$loc_id.'&rest_id='.$rest_id.'")'."'>$name</a><br>\n";
				
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
			$outputtext .= "<h3>$catname</h3>\n";
			$outputtext .= "<div>\n";
			
			$rest_link = Array();
			
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
				
				$rest_link[$name] = " <a href='".'javascript:setMobile("mobilerestaurantpage.php?loc_id='.$loc_id.'&rest_id='.$rest_id.'")'."'>$name</a><br>\n";

				//$restaurant = $cat_row['category'];
				//<a href="javascript:setMain(`categoriespage.php?loc_id=$loc_row['loc_id']`)">$loc_row['location']</a><br>\n
				//$outputtext .= " <a href='".'javascript:setMobile("mobilerestaurantpage.php?loc_id='.$loc_id.'&rest_id='.$rest_id.'")'."'>$name</a><br>\n";
				
				$rest_count++;
				
				//$outputtext .= " ".$rest_row['rest_id']."<br>\n";
			}
			
			ksort($rest_link);
			foreach($rest_link as $key => $value)
			{
				$outputtext .= $value;
			}
			
			$rest_id = 0;
						 
			$outputtext .= "</div>\n";
		}
	}
	
	if(!$rest_count)
	{
		$outputtext .= "<br><center>Your search returned no restaurants.</center>";
	}
	
	$outputtext .= "</div>
		<br>
		<br>
		";
}
else
{ 
	$outputtext .= $loc_id." ";
	$outputtext .= "No Categories In Database";
}

//mysqli_close($dbc);

?>