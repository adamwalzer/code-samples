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
			$hours = unserialize($rest_row['hours']);
			$name = rawUrlEncode($rest_row['name']);
			
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
							";
							
			if($_SESSION['order_id'])
			{
				$outputtext .= "
							<td class='center'>
								<a href='javascript:setMobile(".'"mobilevieworderpage.php"'.")'>order</a>
							</td>
					";
			}
							
			$outputtext .= "
							</tr>
						</table>
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
	
    /// var_dump($error);
    mysqli_close($dbc);
?>