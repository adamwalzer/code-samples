<?php
	include "connect.php";
	
	if($_GET['loc_id'])
	{
		$loc_id = $_GET['loc_id'];
		$rest_id = $_GET['rest_id'];
	}
	elseif($_SESSION['rest_id'])
	{
		$loc_id = $_SESSION['loc_id'];
		$rest_id = $_SESSION['rest_id'];
	}
	
	if($loc_id && $rest_id)
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
           
			$outputtext .= "
					<center>
								";
								
			$outputtext .= "
				Today's Hours: ";
				
			$hours = unserialize($rest_row['hours']);
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
				$outputtext .= "Closed";
			}
			
			$outputtext .= " <a href='javascript:hoursPopup(".'"'.$name.'","'.$loc_id.'","'.$rest_id.'"'.")'>view all</a><br>";
			
			if($rest_row['delivers'] && $rest_row['pickup'])
			{
				$outputtext .= "
					Pick Up & Delivery
					<br>
					Delivery Fee: ";
				
				if($rest_row['delivery_fee']!=0.00)
				{
					$outputtext .= "$".$rest_row['delivery_fee'];
				}
				else
				{
					$outputtext .= "Free";
				}
				
				$outputtext .= ", Delivery Minimum: $".$rest_row['delivery_minimum']."
					<br>
					";
			}
			elseif($rest_row['delivers'])
			{
				$outputtext .= "Delivery\n";
			}
			elseif($rest_row['pickup'])
			{
				$outputtext .= "Pick Up	\n";
			}
			
			$outputtext .= "
				".$rest_row['phone']."
				<br>
				".$rest_row['address']."
				<br>
				".$rest_row['city'].", ".$rest_row['state']." ".$rest_row['zip']."
				<br>
				";
				
			if($rest_row['yelp'])
			{
				$outputtext .= $rest_row['yelp']."<br>\n";
			}
			
			$outputtext .= "
					</center>
				";
        }
		else
		{ 
            $outputtext .= "
					<center>
            			No Restaurant In Database
            		</center>
            	";
        }
        
        
    }
	else
	{
		$outputtext .= "
				<center>
					No Restaurant In Database
				</center>
			";
    }
	
    /// var_dump($error);
    mysqli_close($dbc);
	
	echo $outputtext;
?>