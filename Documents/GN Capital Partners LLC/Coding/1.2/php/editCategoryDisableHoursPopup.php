<?php

session_start();

include "connect.php";

function dayList() {
	$days = array(0=>"Sunday",
					1=>"Monday",
					2=>"Tuesday",
					3=>"Wednesday",
					4=>"Thursday",
					5=>"Friday",
					6=>"Saturday");
	return $days;
}

$days = dayList();

$rest_id = $_SESSION['rest_id'];
$cat_id = $_POST['cat_id'];
$query_get_info = "
	SELECT * FROM Menu_Category_Disable_Hours 
	WHERE rest_id = '$rest_id'
	AND cat_id = '$cat_id'
	";
$result_get_info = mysqli_query($dbc, $query_get_info);

$outputtext .= "
	<div type='popup' name='editCategoryDisableHours' title='Edit Disable Hours for ".$_POST['category_name']."'>
		<text>
		<center>
			<form onSubmit='submitInput(this,".'"setcategorydisablehours.php"'.");return false;' method='post' class='login_form'>
				
				<input type='hidden' value='::C' />
				<input type='hidden' value='".$cat_id."' />
				<table id='registrationtable'>
	";

if (@mysqli_num_rows($result_get_info) > 0)//if Query is successfull 
{ // A match was made.
	while($info_row = mysqli_fetch_array($result_get_info, MYSQLI_ASSOC))
	{
	
		$outputtext .= "
					<tr class='delivery-location'>
						<td>
							<input type='hidden' value='::H' />
							<i onClick='deleteRow(this)' class='icon-cancel'></i>
							<i onClick='addCategoryDisableHours(this)' class='icon-add'></i>
						</td>
						<td>
							<table id='registrationtable'>
								<tr>
									<th colspan='2' class='optional' id='start_day_test'>Start Day</th>
									<th colspan='2' class='center'>
			";
			
		$day_num = $info_row['day_num'];

		$outputtext .= "<select type='text' id='start_day' name='start_day' >";

		foreach($days as $key=>$value)
		{
			$outputtext .= "<option value='".$key."' ";
			if($key==$day_num)
			{
				$outputtext .= "selected='selected' ";
			}
			$outputtext .= ">".$value."</option>";
		}

		$outputtext .= "</select>";
	
		$outputtext .= "
									</th>
								</tr>
								<tr>
									<th colspan='2' class='optional' id='start_time_test'>Zip Code</th>
									<th colspan='2' class='center'><input type='time' id='start_time' name='start_time' value='".$info_row['start']."' size='30' /></th>
								</tr>
								<tr>
									<th colspan='2' class='optional' id='start_day_test'>Start Day</th>
									<th colspan='2' class='center'>
			";
			
		$stop_day_num = $info_row['stop_day_num'];

		$outputtext .= "<select type='text' id='stop_day' name='stop_day' >";

		foreach($days as $key=>$value)
		{
			$outputtext .= "<option value='".$key."' ";
			if($key==$stop_day_num)
			{
				$outputtext .= "selected='selected' ";
			}
			$outputtext .= ">".$value."</option>";
		}

		$outputtext .= "</select>";
	
		$outputtext .= "
									</th>
								</tr>
								<tr>
									<th colspan='2' class='optional' id='stop_time_test'>Zip Code</th>
									<th colspan='2' class='center'><input type='time' id='stop_time' name='stop_time' value='".$info_row['stop']."' size='30' /></th>
								</tr>
							</table>
						</td>
					</tr>
			";
	}
}
else
{
	$outputtext .= "
				<tr class='delivery-location'>
					<td>
						<input type='hidden' value='::H' />
						<i onClick='deleteRow(this)' class='icon-cancel'></i>
						<i onClick='addCategoryDisableHours(this)' class='icon-add'></i>
					</td>
					<td>
						<table id='registrationtable'>
							<tr>
								<th colspan='2' class='optional' id='start_day_test'>Start Day</th>
								<th colspan='2' class='center'>
		";
		
	$day_num = 0;

	$outputtext .= "<select type='text' id='start_day' name='start_day' >";

	foreach($days as $key=>$value)
	{
		$outputtext .= "<option value='".$key."' ";
		if($key==$day_num)
		{
			$outputtext .= "selected='selected' ";
		}
		$outputtext .= ">".$value."</option>";
	}

	$outputtext .= "</select>";

	$outputtext .= "
								</th>
							</tr>
							<tr>
								<th colspan='2' class='optional' id='start_time_test'>Zip Code</th>
								<th colspan='2' class='center'><input type='time' id='start_time' name='start_time' size='30' /></th>
							</tr>
							<tr>
								<th colspan='2' class='optional' id='start_day_test'>Start Day</th>
								<th colspan='2' class='center'>
		";
		
	$stop_day_num = 1;

	$outputtext .= "<select type='text' id='stop_day' name='stop_day' >";

	foreach($days as $key=>$value)
	{
		$outputtext .= "<option value='".$key."' ";
		if($key==$stop_day_num)
		{
			$outputtext .= "selected='selected' ";
		}
		$outputtext .= ">".$value."</option>";
	}

	$outputtext .= "</select>";

	$outputtext .= "
								</th>
							</tr>
							<tr>
								<th colspan='2' class='optional' id='stop_time_test'>Zip Code</th>
								<th colspan='2' class='center'><input type='time' id='stop_time' name='stop_time' size='30' /></th>
							</tr>
						</table>
					</td>
				</tr>
		";
}
$outputtext .= "
					<tr>
						<th colspan='4' class='optional' ><input type='submit' id='stylebutton' name='Save Hours' value='Save Hours' /></th>
					</tr>

				</table>

			</form>
		</center>
		</text>
	</div>
	";
	
mysqli_close($dbc);
    
echo $outputtext;

?>