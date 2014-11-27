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
		<center>
			<table id='topbartable'>
				<tr>
					<td class='center' colspan='3'>
						<h3>".$rest_row['name']."</h3>
					</td>
				</tr>
				<tr>
					<td class='center'><a href='javascript:hoursPopup(".'"'.$name.'","'.$loc_id.'","'.$rest_id.'"'.")'>hours</a></td>
					<td class='center'><a href='javascript:restaurantInfoPopup(".'"'.$name.'","'.$loc_id.'","'.$rest_id.'"'.")'>info</a></td>
					<td class='center' colspan='1'>
						<a href='javascript:setMobile(".'"mobilevieworderpage.php"'.")'>order</a>
					</td>
				</tr>
			</table>
		</center>
	</div>
	";

mysqli_close($dbc);

?>