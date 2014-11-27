<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

include ('connecttowrite.php');

$loc_id = $_SESSION['loc_id'];
$rest_id = $_SESSION['rest_id'];
$input = $_POST['input'];

$query_delete_locations = "
	DELETE FROM Delivery_Info 
	WHERE rest_id='$rest_id'
	";
$result_delete_locations = mysqli_query($dbc, $query_delete_locations);

if($loc_id && $rest_id && $input)
{
	while(count($input) > 1)
	{
		if(strncmp($input[0],"::L",3)==0)
		{
			$city = $input[1];
			$state = $input[2];
			$zip = $input[3];
			$delivery_minimum = $input[4];
			$delivery_fee = $input[5];
			
			$query_insert_location = "
				INSERT INTO Delivery_Info
						(rest_id, city, state, zip, delivery_minimum, delivery_fee)
				VALUES ('$rest_id', '$city', '$state', '$zip', '$delivery_minimum', '$delivery_fee')
				";
			$result_insert_location = mysqli_query($dbc, $query_insert_location);
			
			unset($input[0]); // remove item at index 0
			unset($input[1]); // remove item at index 1
			unset($input[2]); // remove item at index 2
			unset($input[3]); // remove item at index 3
			unset($input[4]); // remove item at index 4
			unset($input[5]); // remove item at index 5
		}
		else
		{
			unset($input[0]); // remove item at index 0
		}
		$input = array_values($input); // 'reindex' array
	}
	$outputtext .= "<javascript>deliveryLocationsPopupDialog.dialog('close');</javascript>";
}
else
{
	$outputtext .= "<javascript>alert('An error has occured.');</javascript>";
}

/// var_dump($error);
mysqli_close($dbc);

echo $outputtext;

?>