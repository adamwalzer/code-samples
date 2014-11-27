<?php

include "connect.php";
	
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
   
   $outputtext .= "<center><a href='".'javascript:setMain("locationspage.php")'."'><h2>Locations</h2></a></center>\n";
   $outputtext .= "<div id='accordion'>\n";
   
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
			$outputtext .= "<h3><a href='".'javascript:setMain("statepage.php?state='.$state.'")'."'>$state</a></h3>\n";
			$outputtext .= "<div>\n";
		
			while($loc_row = mysqli_fetch_array($result_get_location, MYSQLI_ASSOC))
			{
				$loc_id = $loc_row['loc_id'];
				$location = $loc_row['location'];
				//<a href="javascript:setMain(`categoriespage.php?loc_id=$loc_row['loc_id']`)">$loc_row['location']</a><br>\n
				$outputtext .= "<a href='".'javascript:setMain("categoriespage.php?loc_id='.$loc_id.'");setSide("leftcategoriespage.php?loc_id='.$loc_id.'")'."'>$location</a><br>\n";
			}
						 
			$outputtext .= "</div>\n";
		}
	}
	
	$outputtext .= "</div>\n";
}
else
{ 
	$outputtext .= "No Categories In Database";
}

//mysqli_close($dbc);

?>