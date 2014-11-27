<?php

include "connect.php";

if(!$loc_id)
{
	$loc_id = $_GET['loc_id'];
}

$query_get_location = "
	SELECT * FROM Location 
	WHERE loc_id='$loc_id'
	";
//$outputtext .= $query_get_location;

$result_get_location = mysqli_query($dbc, $query_get_location);
$loc_row = mysqli_fetch_array($result_get_location, MYSQLI_ASSOC);
$location = $loc_row['location'];

$outputtext .= "
<div id='top'>
	<table id='topbartable'>
		<tr>
			<td valign='middle' align='center' style='font-size:35px;'>
				$location Categories
			</td>
		</tr>
	</table>
</div>
";

mysqli_close($dbc);

?>