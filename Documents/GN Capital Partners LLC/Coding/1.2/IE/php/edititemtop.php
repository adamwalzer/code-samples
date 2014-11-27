<?php
	include "connect.php";
	
	if(!$_SESSION)
	{
		session_start();
	}
	
	$loc_id = $_SESSION['loc_id'];
	$rest_id = $_SESSION['rest_id'];
	$item_id = $_GET['item_id'];
	
	if($loc_id && $rest_id && $item_id)
	{
		$query_get_item = "
			SELECT * FROM Menu_Item 
			WHERE (rest_id='$rest_id' AND item_id='$item_id')
			";
		$result_get_item = mysqli_query($dbc, $query_get_item);
		$item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC);
		
		$outputtext .= "
			<table id='topbartable'>
				<tr>
					<td valign='middle' align='center' style='font-size:35px;'>
						Edit ".$item_row['name']." Item Menu
					</td>
				</tr>
			</table>
			";
	}
	else
	{
		$outputtext .= "
			<center>
				No Restaurant ID
			</center>
			";
    }
	
    /// var_dump($error);
    mysqli_close($dbc);
?>