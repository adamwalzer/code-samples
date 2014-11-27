<?php
	include "connect.php";
	
	$loc_id = $_SESSION['loc_id'];
	$rest_id = $_SESSION['rest_id'];
	
	if($loc_id && $rest_id)
	{		
		$outputtext .= "
			<div id='main_right' class='col span_3_of_4'>
				<center>
			";
        
        $query_get_order = "
        	SELECT * FROM Placed_Order 
        	WHERE (loc_id='$loc_id' AND rest_id='$rest_id' AND filled='0') 
        	ORDER BY date DESC
        	";
        $result_get_order = mysqli_query($dbc, $query_get_order);

        if (@mysqli_num_rows($result_get_order) > 0)//if Query is successfull 
        { // A match was made.
            
            $outputtext .= "
            	<table id='ordertable'>
            		<tr>
            			<th>Order Info</th>
            			<th>User Info</th>
            			<th>Items</th>
            			<th>Notes</th>
            		</tr>
            		<tr>";
            
            while($order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC))
			{
				$order_id = $order_row['order_id'];
				$order_date = $order_row['date'];
				$username = $order_row['username'];
				
				
				if($order_row['first_name'] && $order_row['last_name'])
				{
					$user_info .= $order_row['first_name']." ".$order_row['last_name']."<br>\n";
				}
				elseif($order_row['first_name'])
				{
					$user_info .= $order_row['first_name']."<br>\n";
				}
				elseif($order_row['last_name'])
				{
					$user_info .= $order_row['last_name']."<br>\n";
				}
				
				$user_info .= $username."<br>\n";
				$user_info .= $order_row['del_address']."<br>\n";
				$user_info .= $order_row['del_city'].", ";
				$user_info .= $order_row['del_state']."<br>\n";
				$user_info .= $order_row['del_zip']."<br>\n";
				
				$query_get_item = "
					SELECT * FROM Order_Item 
					WHERE (order_id='$order_id')
					";
				$result_get_item = mysqli_query($dbc, $query_get_item);
				$num_items = @mysqli_num_rows($result_get_item);
				
				$outputtext .= "
            		<td rowspan='$num_items'>$order_id<br><br>$order_date</td>
            		<td rowspan='$num_items'>$user_info</td>
            		";
				
				if ($num_items > 0)//if Query is successfull 
				{ // A match was made.
				
					while($item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC))
					{
						$item_id = $item_row['item_id'];
						$notes = $item_row['notes'];
						
						$query_get_name = "
							SELECT name FROM Menu_Item 
							WHERE (loc_id='$loc_id' AND rest_id='$rest_id' AND item_id='$item_id')
							";
						$result_get_name = mysqli_query($dbc, $query_get_name);
						$name_row = mysqli_fetch_array($result_get_name, MYSQLI_ASSOC);
						$item_name = $name_row['name'];
						
						$outputtext .= "
            				<td>$item_name</td>
            				<td>$notes</td>
            				</tr>
            				<tr>";
					}				
				}
			}
			
			$outputtext .= "
						</tr>
					</table>
				</center>
				";
        }
		else
		{ 
            $outputtext .= "No Orders In Database <br>";
        }
        
        $outputtext .= "</div>";
    }
	else
	{
		$outputtext .= "
			<div id='main_right' class='col span_3_of_4'>
				<center>
					No Restaurant ID
				</center>
			</div>
			";
    }
	
    /// var_dump($error);
    mysqli_close($dbc);
?>