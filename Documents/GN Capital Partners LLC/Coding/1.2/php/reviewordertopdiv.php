<?php

$query_get_restaurant = "
	SELECT * FROM Restaurant 
	WHERE loc_id='$loc_id' AND rest_id='$rest_id'
	";
//$outputtext .= $query_get_location;

$result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
$rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC);
$restaurant = $rest_row['name'];

$outputtext .= "
<div id='main_left' class='col span_1_of_4 span_1_to_2_of_4'>
	<div id='main_left_content'>
		<h2>Your Order at $restaurant</h2>
		<input type='button' id='registerbutton' onClick='executePage(".'"leftorderdiv"'.");executePage(".'"viewrestaurantdiv"'.");' value='Back To Menu' title='Back To Menu' />
	</div>
	<script>
		positionLeft();
	</script>
</div>
";

?>