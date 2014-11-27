<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

include ('connecttowrite.php');

$loc_id = $_POST['loc_id'];
$rest_id = $_POST['rest_id'];
$item_id = $_POST['item_id'];
$copy_item_id = $_POST['copy_item_id'];
$date = date('Y-m-d H:i:s');

$outputtext = $rest_id." ".$item_id." ".$copy_item_id."\n";

$query_get_updated = "
	SELECT MAX(updated) AS updated FROM Menu_Item_Option_Group 
	WHERE (rest_id='$rest_id' AND item_id='$copy_item_id')
	";
$result_get_updated = mysqli_query($dbc, $query_get_updated);
$updated_row = mysqli_fetch_array($result_get_updated, MYSQLI_ASSOC);
if($updated_row['updated'])
{
	$updated = $updated_row['updated'];
}
else
{
	$updated = '0000-00-00 00:00:00';
}

$outputtext .= " ".$updated;

$query_insert_group = "
	INSERT INTO Menu_Item_Option_Group 
			(rest_id, item_id, group_id, group_name, group_order, group_type, group_quantity, updated)
	SELECT rest_id, '$item_id', group_id, group_name, group_order, group_type, group_quantity, '$date'
	FROM Menu_Item_Option_Group
	WHERE rest_id='".$rest_id."' AND item_id='".$copy_item_id."' AND updated='".$updated."'
	";
$outputtext .= $query_insert_group;
$result_insert_group = mysqli_query($dbc, $query_insert_group);

$outputtext .= " ".@mysqli_num_rows($result_insert_group);
$outputtext .= " ".$result_insert_group;

$query_insert_option = "
	INSERT INTO Menu_Item_Option
			(rest_id, item_id, group_id, option_id, option_order, option_name, option_cost, checked, updated)
	SELECT rest_id, '$item_id', group_id, option_id, option_order, option_name, option_cost, checked, '$date'
	FROM Menu_Item_Option
	WHERE rest_id='".$rest_id."' AND item_id='".$copy_item_id."' AND updated='".$updated."'
	";
$result_insert_option = mysqli_query($dbc, $query_insert_option);

mysqli_close($dbc);

echo $outputtext;

?>