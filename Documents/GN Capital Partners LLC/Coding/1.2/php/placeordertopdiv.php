<?php

include "connect.php";

if(!$loc_id)
{
	$loc_id = $_SESSION['loc_id'];
}

if(!$rest_id)
{
	$rest_id = $_SESSION['rest_id'];
}

$query_get_restaurant = "
	SELECT * FROM Restaurant 
	WHERE loc_id='$loc_id' AND rest_id='$rest_id'
	";
//$outputtext .= $query_get_location;

$result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
$rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC);
$restaurant = $rest_row['name'];

$outputtext .= "
<div id='top'>
	<table id='topbartable'>
		<tr>
			<td valign='middle' align='center' style='font-size:35px;'>
				Your Order at $restaurant
			</td>
		</tr>
	</table>
</div>
";

mysqli_close($dbc);

?>