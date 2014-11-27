<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

include ('connecttowrite.php');

$loc_id = $_SESSION['loc_id'];
$rest_id = $_SESSION['rest_id'];
$input = $_POST['input'];

if($loc_id && $rest_id && $input)
{
	while(count($input) > 1)
	{
		if(strncmp($input[0],"::H",3)==0)
		{
			
			$query_insert_location = "
				INSERT INTO Menu_Category_Disable_Hours
						(rest_id, cat_id, day_num, start, stop_day_num, stop)
				VALUES ('$rest_id', '$cat_id', '$input[1]', '$input[2]', '$input[3]', '$input[4]')
				";
			$result_insert_location = mysqli_query($dbc, $query_insert_location);
			
			unset($input[0]); // remove item at index 0
			unset($input[1]); // remove item at index 1
			unset($input[2]); // remove item at index 2
			unset($input[3]); // remove item at index 3
			unset($input[4]); // remove item at index 4
		}
		elseif(strncmp($input[0],"::C",3)==0)
		{
			$cat_id = $input[1];
			
			$query_delete_locations = "
				DELETE FROM Menu_Category_Disable_Hours 
				WHERE rest_id='$rest_id'
				AND cat_id='$cat_id'
				";
			$result_delete_locations = mysqli_query($dbc, $query_delete_locations);
			
			unset($input[0]); // remove item at index 0
			unset($input[1]); // remove item at index 1
		}
		else
		{
			unset($input[0]); // remove item at index 0
		}
		$input = array_values($input); // 'reindex' array
	}
	$outputtext .= "<action type='javascript'>popup['editCategoryDisableHours'].dialog('close');</action>";
}
else
{
	$outputtext .= "<action type='javascript'>alert('An error has occured.');</action>";
}

/// var_dump($error);
mysqli_close($dbc);

echo $outputtext;

?>