<?php

include_once "connect.php";

$state = $_GET['state'];

$outputtext .= "
<div id='top'>
	<table id='topbartable'>
		<tr>
			<td valign='middle' align='center' style='font-size:35px;'>
				$state Page
			</td>
		</tr>
	</table>
</div>
";

$outputtext .= "<div id='main'>";
	
$query_get_state = "
	SELECT DISTINCT(state) AS state 
	FROM Location 
	ORDER BY state ASC
	";


$query_get_location = "
	SELECT * FROM Location 
	WHERE state='$state'
	";
$result_get_location = mysqli_query($dbc, $query_get_location);

if (@mysqli_num_rows($result_get_location) > 0)//if Query is successfull 
{
	$outputtext .= "<div>\n";

	while($loc_row = mysqli_fetch_array($result_get_location, MYSQLI_ASSOC))
	{
		$loc_id = $loc_row['loc_id'];
		$location = $loc_row['location'];
		
		$outputtext .= " <a href='".'javascript:setMain("categoriespage.php?loc_id='.$loc_id.'");setSide("leftcategoriespage.php?loc_id='.$loc_id.'")'."'>$location</a><br>\n";
	}
				 
	$outputtext .= "</div>\n";
}
else
{
	$outputtext .= "There are no locations in this state.";
}

$outputtext .= "</div>";

echo $outputtext;

mysqli_close($dbc);

?>