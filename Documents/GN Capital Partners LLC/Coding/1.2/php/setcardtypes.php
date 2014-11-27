<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

include ('connecttowrite.php');

$loc_id = $_SESSION['loc_id'];
$rest_id = $_SESSION['rest_id'];
$input = $_POST['input'];

$query_delete_card_types = "
	DELETE FROM Card_Type 
	WHERE rest_id='$rest_id'
	";
$result_delete_card_types = mysqli_query($dbc, $query_delete_card_types);

if($loc_id && $rest_id && $input)
{
	while(count($input) > 1)
	{
		if(strncmp($input[0],"::C",3)==0)
		{
			$card_type = $input[1];
			
			$query_insert_card_type = "
				INSERT INTO Card_Type
						(rest_id, card_type)
				VALUES ('$rest_id', '$card_type')
				";
			$result_insert_card_type = mysqli_query($dbc, $query_insert_card_type);
			
			unset($input[0]); // remove item at index 0
			unset($input[1]); // remove item at index 1
		}
		else
		{
			unset($input[0]); // remove item at index 0
		}
		$input = array_values($input); // 'reindex' array
	}
	$outputtext .= "<javascript>cardTypesPopupDialog.dialog('close');</javascript>";
}
else
{
	$outputtext .= "<javascript>alert('An error has occured.');</javascript>";
}

/// var_dump($error);
mysqli_close($dbc);

echo $outputtext;

?>