<?php

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
			$hours = unserialize($rest_row['hours']);
			$name = rawUrlEncode($rest_row['name']);
			
			$outputtext .= "
				<div id='top'>
					<center>
						<div class='section group'>
							<div class='col span_2_of_2'>
								<h1>".htmlentities($rest_row['name'])."</h1>
							</div>
							<div class='col span_1_of_2 span_1_to_2_of_2'>
								";
								
			if($rest_row['delivers'])
			{
				if($_SESSION['current_city'])
				{
					$current_city = $_SESSION['current_city'];
					$current_zip = $_SESSION['current_zip'];
					$query_get_delivers = "
						SELECT * FROM Delivery_Info 
						WHERE (rest_id='$rest_id' AND (city='$current_city' OR zip='$current_zip')) 
						LIMIT 1
						";
   					$result_get_delivers = mysqli_query($dbc, $query_get_delivers);
   					if (@mysqli_num_rows($result_get_delivers) == 1)
   					{
   						$delivers = 1;
   						$delivers_row = mysqli_fetch_array($result_get_delivers, MYSQLI_ASSOC);
   						$delivery_minimum = $delivers_row['delivery_minimum'];
   						$delivery_fee = $delivers_row['delivery_fee'];
   					}
   					else
   					{
   						$delivers = 0;
   					}
				}
				else
				{
					$delivers = 1;
					$delivery_minimum = $rest_row['delivery_minimum'];
					$delivery_fee = $rest_row['delivery_fee'];
				}
			}
			else
			{
				$delivers = 0;
			}
			
			if($delivers && $rest_row['pickup'])
			{
				$outputtext .= "
					Pick Up &amp; Delivery
					<br/>
					Delivery Fee: ";
				
				if($delivery_fee!=0.00)
				{
					$outputtext .= "$".$delivery_fee;
				}
				else
				{
					$outputtext .= "Free";
				}
				
				$outputtext .= ", Delivery Minimum: $".$delivery_minimum."
					";
			}
			elseif($delivers)
			{
				$outputtext .= "
					Delivery
					<br/>
					Delivery Fee: ";
				
				if($delivery_fee!=0.00)
				{
					$outputtext .= "$".$delivery_fee;
				}
				else
				{
					$outputtext .= "Free";
				}
				
				$outputtext .= ", Delivery Minimum: $".$delivery_minimum."
					";
			}
			elseif($rest_row['pickup'])
			{
				$outputtext .= "Pick Up	\n";
			}
			
			include "todayshours.php";
			
			$outputtext .= "
								</div>
								<div class='col span_1_of_2 span_1_to_2_of_2'>
									".htmlentities($rest_row['phone'])."
									<br/>
									".htmlentities($rest_row['address'])."
									<br/>
									".htmlentities($rest_row['city']).", ".htmlentities($rest_row['state'])." ".htmlentities($rest_row['zip'])."
									<br/>
				";
				
			$outputtext .= "	
								</div>
						</div>
					</center>
				</div>
				";
        }
		else
		{ 
            $outputtext .= "
            	<div id='top'>
					<center>
            			No Restaurant In Database
            		</center>
            	</div>";
        }
        
        
    }
	else
	{
		$outputtext .= "
			<div id='top'>
				<center>
					No Restaurant In Database
				</center>
			</div>";
    }
    
?>