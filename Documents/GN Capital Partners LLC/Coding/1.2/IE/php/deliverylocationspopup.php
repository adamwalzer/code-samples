<?php

session_start();

include "connect.php";

function statesList() {
	$states = array('AL'=>"Alabama",
					'AK'=>"Alaska",
					'AZ'=>"Arizona",
					'AR'=>"Arkansas",
					'CA'=>"California",
					'CO'=>"Colorado",
					'CT'=>"Connecticut",
					'DE'=>"Delaware",
					'DC'=>"District Of Columbia",
					'FL'=>"Florida",
					'GA'=>"Georgia",
					'HI'=>"Hawaii",
					'ID'=>"Idaho",
					'IL'=>"Illinois",
					'IN'=>"Indiana",
					'IA'=>"Iowa",
					'KS'=>"Kansas",
					'KY'=>"Kentucky",
					'LA'=>"Louisiana",
					'ME'=>"Maine",
					'MD'=>"Maryland",
					'MA'=>"Massachusetts",
					'MI'=>"Michigan",
					'MN'=>"Minnesota",
					'MS'=>"Mississippi",
					'MO'=>"Missouri",
					'MT'=>"Montana",
					'NE'=>"Nebraska",
					'NV'=>"Nevada",
					'NH'=>"New Hampshire",
					'NJ'=>"New Jersey",
					'NM'=>"New Mexico",
					'NY'=>"New York",
					'NC'=>"North Carolina",
					'ND'=>"North Dakota",
					'OH'=>"Ohio",
					'OK'=>"Oklahoma",
					'OR'=>"Oregon",
					'PA'=>"Pennsylvania",
					'RI'=>"Rhode Island",
					'SC'=>"South Carolina",
					'SD'=>"South Dakota",
					'TN'=>"Tennessee",
					'TX'=>"Texas",
					'UT'=>"Utah",
					'VT'=>"Vermont",
					'VA'=>"Virginia",
					'WA'=>"Washington",
					'WV'=>"West Virginia",
					'WI'=>"Wisconsin",
					'WY'=>"Wyoming");
	return $states;
}

$states = statesList();

$rest_id = $_SESSION['rest_id'];
$query_get_info = "
	SELECT * FROM Delivery_Info 
	WHERE rest_id = '$rest_id'
	";
$result_get_info = mysqli_query($dbc, $query_get_info);

$outputtext .= "
	<center>
		<form onSubmit='submitInput(this,".'"setdeliverylocations.php"'.");return false;' method='post' class='login_form'>

			<table id='registrationtable'>
	";

if (@mysqli_num_rows($result_get_info) > 0)//if Query is successfull 
{ // A match was made.
	while($info_row = mysqli_fetch_array($result_get_info, MYSQLI_ASSOC))
	{
	
		$outputtext .= "
					<tr class='delivery-location'>
						<td>
							<input type='hidden' value='::L' />
							<i onClick='deleteDeliveryLocation(this)' class='icon-cancel'></i>
							<i onClick='addDeliveryLocation(this)' class='icon-add'></i>
						</td>
						<td>
							<table id='registrationtable'>
								<tr>
									<th colspan='2' class='optional' id='current_city_test'>City</th>
									<th colspan='2' class='center'><input type='text' id='current_city' name='current_city' placeholder='City' value='".$info_row['city']."' size='30' /></th>
								</tr>
								<tr>
									<th colspan='2' class='optional' id='current_state_test'>State</th>
									<th colspan='2' class='center'>
			";
			
		$stateletters = $info_row['state'];
	
		if($stateletters)
		{
			$statename = $states[$stateletters];
		}
		else
		{
			$statename = "Select Your State...";
		}

		$outputtext .= "<select type='text' id='current_state' name='current_state' >";
		$outputtext .= "<option selected='selected' value='".$stateletters."'>".$statename."</option>";
					
		$outputtext .= "</option>";

		foreach($states as $key=>$value)
		{
			$outputtext .= "<option value='".$key."'>".$value."</option>";
		}

		$outputtext .= "</select>";
	
		$outputtext .= "
									</th>
								</tr>
								<tr>
									<th colspan='2' class='optional' id='current_zip_test'>Zip Code</th>
									<th colspan='2' class='center'><input type='text' id='current_zip' name='current_zip' placeholder='Zip Code' value='".$info_row['zip']."' size='30' /></th>
								</tr>
								<tr>
									<th colspan='2' class='optional' id='delivery_minimum_test'>Delivery Minimum</th>
									<th colspan='2' class='center'><input type='number' step='.01' min='0' id='delivery_minimum' name='delivery_minimum' placeholder='Delivery Minimum' value='".$info_row['delivery_minimum']."' size='30' /></th>
								</tr>
								<tr>
									<th colspan='2' class='optional' id='delivery_fee_test'>Delivery Fee</th>
									<th colspan='2' class='center'><input type='number' step='.01' min='0' id='delivery_fee' name='delivery_fee' placeholder='Delivery Fee' value='".$info_row['delivery_fee']."' size='30' /></th>
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
						<input type='hidden' value='::L' />
						<i onClick='deleteDeliveryLocation(this)' class='icon-cancel'></i>
						<i onClick='addDeliveryLocation(this)' class='icon-add'></i>
					</td>
					<td>
						<table id='registrationtable'>
							<tr>
								<th colspan='2' class='optional' id='current_city_test'>City</th>
								<th colspan='2' class='center'><input type='text' id='current_city' name='current_city' placeholder='City' value='".$_SESSION['city']."' size='30' /></th>
							</tr>
							<tr>
								<th colspan='2' class='optional' id='current_state_test'>State</th>
								<th colspan='2' class='center'>
		";
		
	$stateletters = $_SESSION['state'];

	if($stateletters)
	{
		$statename = $states[$stateletters];
	}
	else
	{
		$statename = "Select Your State...";
	}

	$outputtext .= "<select type='text' id='current_state' name='current_state' >";
	$outputtext .= "<option selected='selected' value='".$stateletters."'>".$statename."</option>";
				
	$outputtext .= "</option>";

	foreach($states as $key=>$value)
	{
		$outputtext .= "<option value='".$key."'>".$value."</option>";
	}

	$outputtext .= "</select>";

	$outputtext .= "
								</th>
							</tr>
							<tr>
								<th colspan='2' class='optional' id='current_zip_test'>Zip Code</th>
								<th colspan='2' class='center'><input type='text' id='current_zip' name='current_zip' placeholder='Zip Code' value='".$_SESSION['zip']."' size='30' /></th>
							</tr>
							<tr>
								<th colspan='2' class='optional' id='delivery_minimum_test'>Delivery Minimum</th>
								<th colspan='2' class='center'><input type='number' step='.01' min='0' id='delivery_minimum' name='delivery_minimum' placeholder='Delivery Minimum' value='".$_SESSION['delivery_minimum']."' size='30' /></th>
							</tr>
							<tr>
								<th colspan='2' class='optional' id='delivery_fee_test'>Delivery Fee</th>
								<th colspan='2' class='center'><input type='number' step='.01' min='0' id='delivery_fee' name='delivery_fee' placeholder='Delivery Fee' value='".$_SESSION['delivery_fee']."' size='30' /></th>
							</tr>
						</table>
					</td>
				</tr>
		";
}
$outputtext .= "
				<tr>
					<th colspan='4' class='optional' ><input type='submit' id='stylebutton' name='Save Locations' value='Save Locations' /></th>
				</tr>

			</table>

		</form>
	</center>
	";
	
mysqli_close($dbc);
    
echo $outputtext;

?>