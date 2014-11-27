<?php

session_start();

include "connect.php";

//$loc_id = $_GET['loc_id'];

$loc_id = $_SESSION['loc_id'];
$rest_id = $_SESSION['rest_id'];
$item_id = $_POST['item_id'];


$outputtext .= "
	<div id='main'>
		<br>
		<form id='placeorderform' onSubmit='copyOptions(this);return false;' method='post'>
		<table id='fullorder'>
			<colgroup>
				<col width='50%'>
			</colgroup>
			<colgroup>
				<col width='50%'>
			</colgroup>
			<tr>
				<td colspan='2'>Select which item you would like to copy the options from.</td>
			</tr>
			<tr>
				<td colspan='2'>Copying these options may overwrite your current options.</td>
			</tr>
			<tr>
				<td colspan='2'>
					<input type='hidden' id='loc_id' value='".$loc_id."' >
					<input type='hidden' id='rest_id' value='".$rest_id."' >
					<input type='hidden' id='item_id' value='".$item_id."' >
					<select type='text' id='copy_item_id' name='copy_item_id' >
					<option value=''>Select the item...</option>
					";
					
$query_get_updated = "
	SELECT MAX(updated) AS updated 
	FROM Menu_Category 
	WHERE (rest_id='$rest_id')
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

$query_get_item = "
	SELECT * FROM Menu_Item 
	WHERE rest_id='".$rest_id."' AND updated='".$updated."' 
	ORDER BY name
	";
$result_get_item = mysqli_query($dbc, $query_get_item);
while($item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC))
{
	$outputtext .= "<option value='".$item_row['item_id']."'>".$item_row['name']."</option>";
}

$outputtext .= "
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2'>&nbsp</td>
			</tr>
			<tr>
				<td colspan='2'>
					<input type='submit' id='stylebutton' name='Copy Options' value='Copy Options' tabindex=19 />
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					&nbsp;
				</td>
			</tr>
		</table>
		</form>
	</div>
	";

echo $outputtext;
mysqli_close($dbc);

?>