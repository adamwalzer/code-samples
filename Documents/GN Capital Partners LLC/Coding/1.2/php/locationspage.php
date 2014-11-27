<?php

include_once "connect.php";

$outputtext .= "
<div id='top'>
	<table id='topbartable'>
		<tr>
			<td valign='middle' align='center' style='font-size:35px;'>
				Locations Page
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

$result_get_state = mysqli_query($dbc, $query_get_state);
if(!$result_get_state)
{//If the QUery Failed 
	$outputtext .= 'Query Failed ';
}

if (@mysqli_num_rows($result_get_state) > 0)//if Query is successfull 
{ // A match was made.
	//$_SESSION = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
   
	while($state_row = mysqli_fetch_array($result_get_state, MYSQLI_ASSOC))
	{
		$state = $state_row['state'];
		$query_get_location = "
			SELECT * FROM Location 
			WHERE state='$state'
			";
		$result_get_location = mysqli_query($dbc, $query_get_location);
		
		if (@mysqli_num_rows($result_get_location) > 0)//if Query is successfull 
		{
			$outputtext .= "<h3><a href='".'javascript:setMain("statepage.php?state='.$state.'")'."'>$state</a></h3>
								<div>\n";
		
			while($loc_row = mysqli_fetch_array($result_get_location, MYSQLI_ASSOC))
			{
				$location = $loc_row['location'];
				$loc_id = $loc_row['loc_id'];
				$outputtext .= " <a href='".'javascript:setMain("categoriespage.php?loc_id='.$loc_id.'");setSide("leftcategoriespage.php?loc_id='.$loc_id.'")'."'>$location</a><br>\n";
			}
						 
			$outputtext .= "</div>\n";
		}
	}
}
else
{ 
	$outputtext .= "No Categories In Database";
}

$outputtext .= "</div>";

echo $outputtext;

mysqli_close($dbc);

?>