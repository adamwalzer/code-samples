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
					<div style='font-size: 125%;'>
								";
								
			$hours = unserialize($rest_row['hours']);
			
			$days = array(1=>"Monday",
						2=>"Tuesday",
						3=>"Wednesday",
						4=>"Thursday",
						5=>"Friday",
						6=>"Saturday",
						0=>"Sunday");
			
			
			foreach($days as $key=>$value)
			{
				$outputtext .= "$value: ";
				
				if($hours[$key][0] && $hours[$key][1])
				{
					$outputtext .= date('g:i A', strtotime($hours[$key][0]))."-".date('g:i A', strtotime($hours[$key][1]));
					
					if($hours[$key][2] && $hours[$key][3])
					{
						$outputtext .= ", ".date('g:i A', strtotime($hours[$key][2]))."-".date('g:i A', strtotime($hours[$key][3]))."<br>";
					}
					else
					{
						$outputtext .= "<br>";
					}
				}
				else
				{
					$outputtext .= "Closed<br>";
				}
			}
			
			$outputtext .= "
					</div>
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