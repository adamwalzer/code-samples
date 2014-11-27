<?php

include "connect.php";

if(!$loc_id)
{
	$loc_id = $_GET['loc_id'];
}

$outputtext .= "
	<div id='main'>
		<script type='text/javascript'>
			var locations = new Array();
		</script>
		<table>
			<tr>
				<td>
	";
	
$query_get_category = "SELECT DISTINCT category AS category FROM Category WHERE loc_id='$loc_id' ORDER BY category ASC";
//$outputtext .= $query_get_category;

$result_get_category = mysqli_query($dbc, $query_get_category);
if(!$result_get_category)
{//If the QUery Failed 
	$outputtext .= 'Query Failed ';
}

if (@mysqli_num_rows($result_get_category) > 0)//if Query is successfull 
{ // A match was made.
	//$_SESSION = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
   
	while($cat_row = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC))
	{
		$category = $cat_row['category'];
		$catname = str_replace("_"," ",$category);
		$query_get_restaurant = "SELECT * FROM Category WHERE (loc_id='$loc_id' AND category='$category' AND confirmed=1)";
		$result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
		
		if (@mysqli_num_rows($result_get_restaurant) > 0)//if Query is successfull 
		{
			$outputtext .= "<h2><a href='".'javascript:setMain("categorypage.php?loc_id='.$loc_id.'&category='.$category.'")'."'>$catname</a></h2>
								<div>\n";
		
			while($rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC))
			{
				$rest_id = $rest_row['rest_id'];
				
				$query_get_info = "SELECT * FROM Restaurant WHERE (loc_id='$loc_id' AND rest_id='$rest_id' AND confirmed=1)";
				$result_get_info = mysqli_query($dbc, $query_get_info);
				$info_row = mysqli_fetch_array($result_get_info, MYSQLI_ASSOC);
				$name = $info_row['name'];
				$lat = $info_row['latitude'];
				$lng = $info_row['longitude'];

				//$restaurant = $cat_row['category'];
				//<a href="javascript:setMain(`categoriespage.php?loc_id=$loc_row['loc_id']`)">$loc_row['location']</a><br>\n
				$outputtext .= " <a href='".'javascript:setMain("restaurantpage.php?loc_id='.$loc_id.'&rest_id='.$rest_id.'")'."'>$name</a><br>\n";
				
				
				$outputtext .= "
					<script type='text/javascript'>
						//alert(locations.length);
						locations.push([".'"'.$name.'"'.", $lat, $lng, 1]);
						//alert(locations.length);
						/*
						alert(1);
						var myLatlng = new google.maps.LatLng($lat,$lng);
						var marker = new google.maps.Marker({
							position: myLatlng,
							map: map,
							title:".'"'.$name.'"'."
						});
						alert(2);
						*/
					</script>
					";
				
				
				//$outputtext .= " ".$rest_row['rest_id']."<br>\n";
			}
			
			$rest_id = 0;
						 
			$outputtext .= "
				<br>
				<br>
				</div>
				";
		}
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
				<td>
			</tr>
		</table>
		<script type='text/javascript'
		  src='https://maps.googleapis.com/maps/api/js?key=AIzaSyCRwKNhD9__snhH1uHrOkZMP0xObyuhggM&sensor=true'>
		</script>
		<script type='text/javascript'>
		  
		  function initialize() {
			var mapOptions = {
			  center: new google.maps.LatLng(39.379916, -74.579098),
			  zoom: 12
			};
			var map = new google.maps.Map(document.getElementById('map-canvas'),
				mapOptions);
			
			/*
			var myLatlng = new google.maps.LatLng(39.379916,-74.579098);
			var marker = new google.maps.Marker({
				position: myLatlng,
				map: map,
				title:'center'
			});
			*/
			
			//alert(locations.length);
			for (var i = 0; i < locations.length; i++) {
				//alert(i);
				var location = locations[i];
				var myLatLng = new google.maps.LatLng(location[1], location[2]);
				var marker = new google.maps.Marker({
					position: myLatLng,
					map: map,
					title: location[0],
					zIndex: location[3]
				});
			}
			
			/*
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function (position) {
					initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
					map.setCenter(initialLocation);
				});
			}
			*/
			
			/*
			alert(1);
			setMarkers(map, locations);
			alert(2);
			*/
		  }
		  google.maps.event.addDomListener(window, 'load', initialize);
		</script>
	</div>
	";

mysqli_close($dbc);

?>