<?php

function statesList()
{
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

set_time_limit(0);
ignore_user_abort(1);

session_start();

//include ('connect.php');

$outputtext .= "
<center>
<form id='currentaddressform' onSubmit='submitForm(this,".'"setcurrentaddress.php","closeEditCurrentAddressPopupDialog();"'.");return false;' method='post' class='updateinfo_form'>
	<br>
	
	<table id='registrationtable'>
					
	<tr>
	<th colspan='2' class='optional' id='current_on_beach_test'>On The Beach</th>
	<th colspan='2' class='center'><input type='checkbox' id='current_on_beach' name='current_on_beach' onchange='testOnBeach(this)' placeholder='On The Beach' size='30' ".$_SESSION['current_on_beach']." /></th>
	</tr>
	
	<tr>
	<th colspan='2' class='optional' id='current_address_test'>Street Address</th>
	<th colspan='2' class='center'><input type='text' id='current_address' name='current_address' onkeyup='testAddress(this);' placeholder='Street Address' value='".$_SESSION['current_address']."' /></th>
	</tr>
	
	<tr>
	<th colspan='2' class='optional' id='current_city_test'>City</th>
	<th colspan='2' class='center'><input type='text' id='current_city' name='current_city' onkeyup='testCity(this)' placeholder='City' value='".$_SESSION['current_city']."' /></th>
	</tr>
	
	<tr>
	<th colspan='2' class='optional' id='current_state_test'>State</th>
	<th colspan='2' class='center'>
	";
	
$states = statesList();

$outputtext .= "<select type='text' id='current_state' name='current_state' onchange='testState(this)' >";

if($_SESSION['current_state'])
{
	$stateletters = $_SESSION['current_state'];
	$outputtext .= "<option selected='selected' value='".$stateletters."'>".$states[$stateletters]."</option>";
}
else
{
	$outputtext .= "<option selected='selected'>Select your state...</option>";
}

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
	<th colspan='2' class='center'><input type='text' id='current_zip' name='current_zip' onkeyup='testZip(this)' placeholder='Zip Code' value='".$_SESSION['current_zip']."' /></th>
	</tr>
	
	<tr>
	<th colspan='4' id='registerbuttontest' class='optional'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='center'><input type='submit' id='registerbutton' value='Save Changes' title='Save Changes' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	</table>
	
</form>
</center>
<script>
	//alert('testFormCurrentAddress();');
</script>
";
	


/// var_dump($error);
//mysqli_close($dbc);

echo $outputtext;

?>