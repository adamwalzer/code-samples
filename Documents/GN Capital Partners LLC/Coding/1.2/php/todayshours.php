<?php
	
	if(!$dbc)
	{
		include "connect.php";
	}
	
	if($_GET['loc_id'])
	{
		$loc_id = $_GET['loc_id'];
	}
	elseif($_SESSION['loc_id'])
	{
		$loc_id = $_SESSION['loc_id'];
	}

	if($_GET['rest_id'])
	{
		$rest_id = $_GET['rest_id'];
	}
	elseif($_SESSION['rest_id'])
	{
		$rest_id = $_SESSION['rest_id'];
	}
	else
	{
		$rest_id = 0;
	}
	
	if($loc_id && $rest_id)
	{
        if(!$rest_row)
        {
			$query_get_restaurant = "
				SELECT * FROM Restaurant 
				WHERE (loc_id='$loc_id' AND rest_id='$rest_id') 
				LIMIT 1
				";
   
			$result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
			if(!$result_get_restaurant)
			{//If the QUery Failed 
				$outputtext .= 'Query Failed ';
			}

			if (@mysqli_num_rows($result_get_restaurant) == 1)//if Query is successfull 
			{ // A match was made.
				$rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
			}
		}
		
		$hours = unserialize($rest_row['hours']);
			
		$outputtext .= "
							<div id='hours_div'>
								Today's Hours: ";
		
		$dayoftheweek = date("w");
		
		if($hours[$dayoftheweek][0] && $hours[$dayoftheweek][1])
		{
			$outputtext .= date('g:i A', strtotime($hours[$dayoftheweek][0]))."-".date('g:i A', strtotime($hours[$dayoftheweek][1]));
			
			if($hours[$dayoftheweek][2] && $hours[$dayoftheweek][3])
			{
				$outputtext .= ", ".date('g:i A', strtotime($hours[$dayoftheweek][2]))."-".date('g:i A', strtotime($hours[$dayoftheweek][3]));
			}
		}
		else
		{
			$outputtext .= "Closed ";
		}
		
		$outputtext .= " <a onclick='executePage(".'"restauranthours&loc_id='.$loc_id.'&rest_id='.$rest_id.'"'.")'>view all</a><br/></div>";
	}
    
?>