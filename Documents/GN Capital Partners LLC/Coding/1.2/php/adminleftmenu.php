<?php

require_once 'connect.php';

if($_POST['loc_id'])
{
	$_SESSION['loc_id']=$_POST['loc_id'];
}

if($_POST['rest_id'])
{
	$_SESSION['rest_id']=$_POST['rest_id'];
}

$query_get_restaurant = "
	SELECT rest_id FROM Restaurant
	WHERE rest_id=".$_SESSION['rest_id']." AND loc_id='".$_SESSION['loc_id']."'
	";
$result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
if (@mysqli_num_rows($result_get_restaurant) == 0) // if the restaurant has delivery info for this location
{
	$_SESSION['rest_id'] = 0;
}

$loc_id=$_SESSION['loc_id'];
$rest_id=$_SESSION['rest_id'];

$outputtext .= "
	<div id='main_left' class='col span_1_of_4 span_1_to_2_of_4'>
		<div id='main_left_content' class='content'>
			<select type='text' id='location_select' onchange='executePage(".'"adminleftmenu"'.",{".'"loc_id"'.":this.value});' >
				<option selected='selected' value='0'>Select The Location</option>
		";
		
$query_get_locations = "
	SELECT * FROM Location
	";
$result_get_locations = mysqli_query($dbc, $query_get_locations);
if (@mysqli_num_rows($result_get_locations) > 0) // if the restaurant has delivery info for this location
{
	while($loc_row = mysqli_fetch_array($result_get_locations, MYSQLI_ASSOC)) // go through each restaurant in location
	{
		$outputtext .= "
				<option".($loc_id==$loc_row['loc_id']?" selected='selected'":"")." value='".$loc_row['loc_id']."'>".$loc_row['location']."</option>
			";
	}
}
		
$outputtext .= "
			</select>
			<br/>
			<select type='text' id='restaurant_select' onchange='executePage(".'"adminleftmenu"'.",{".'"rest_id"'.":this.value});' >
				<option selected='selected' value='0'>Select The Restaurant</option>
			";
			
$query_get_restaurants = "
	SELECT * FROM Restaurant
	WHERE loc_id='$loc_id'
	ORDER BY `name` ASC
	";
$result_get_restaurants = mysqli_query($dbc, $query_get_restaurants);
if (@mysqli_num_rows($result_get_restaurants) > 0) // if the restaurant has delivery info for this location
{
	while($rest_row = mysqli_fetch_array($result_get_restaurants, MYSQLI_ASSOC)) // go through each restaurant in location
	{
		$outputtext .= "
				<option".($rest_id==$rest_row['rest_id']?" selected='selected'":"")." value='".$rest_row['rest_id']."'>".$rest_row['name']."</option>
			";
	}
}
			
$outputtext .= "
			</select>
			<br/>
			&nbsp; &bull; <a onclick='executePage(".'"adminorders"'.")'>View Restaurant Orders</a><br>
			&nbsp; &bull; <a onclick='executePage(".'"viewrestaurantdiv"'.")'>View Restaurant Page</a><br>
			&nbsp; &bull; <a onclick='executePage(".'"accountinforestaurantdiv"'.")'>Edit Restaurant Info</a><br>
			&nbsp; &bull; <a onclick='executePage(".'"editmenudiv"'.")'>Edit Restaurant Menu</a><br>
			&nbsp; &bull; <a href='http://gottanom.com/edititems.php'>Edit Restaurant Items</a><br>
		</div>
	</div>
	";

?>