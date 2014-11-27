<?php

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
		
	$outputtext .= "
			<center>	
				<form onSubmit='submitForm(this,".'"newlogin.php"'.");return false;' method='post' class='login_form'>
	
					<table id='registrationtable'>
					
					<tr>
					<th colspan='4' class='optional'>Login</th>
					</tr>
					
					<tr>
					<th colspan='2' class='optional' id='usernametest'>Username</th>
					<th colspan='2' class='center'><input type='text' id='username' name='username' placeholder='Username' size='20' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='emailtest'>Password</th>
					<th colspan='2' class='center'><input type='password' id='password' name='password' placeholder='Password' size='20' /></th>
					</tr>
					
					<tr>
					<th colspan='4' class='center'><input type='submit' id='stylebutton' value='Login' title='Login' /></th>
					</tr>
					
					<tr>
					<th colspan='4' class='center'>&nbsp;</th>
					</tr>
					
					</table>
					
				</form>
				
				<form onSubmit='submitForm(this,".'"setcurrentaddress.php"'.");return false;' method='post' class='login_form'>
	
					<table id='registrationtable'>
					
					<tr>
					<th colspan='4' class='center'>OR</th>
					</tr>
					
					<tr>
					<th colspan='4' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='4' class='optional'>Current Address</th>
					</tr>
					
					<tr>
					<th colspan='2' class='optional' id='current_address_test'>Street Address</th>
					<th colspan='2' class='center'><input type='text' id='current_address' name='current_address' onchange='testAddress(this)' onkeyup='testAddress(this)' placeholder='Street Address' value='".$_SESSION['del_address']."' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='current_city_test'>City</th>
					<th colspan='2' class='center'><input type='text' id='current_city' name='current_city' onchange='testCity(this)' onkeyup='testCity(this)' placeholder='City' value='".$_SESSION['del_city']."' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='current_state_test'>State</th>
					<th colspan='2' class='center'>
		";
	
	$states = statesList();
	$stateletters = $_SESSION['del_state'];
	
	if($stateletters)
	{
		$statename = $states[$stateletters];
	}
	else
	{
		$statename = "Select Your State...";
	}

	$outputtext .= "<select type='text' id='current_state' name='current_state' onchange='testState(this)' >";
	$outputtext .= "<option selected='selected' value='".$stateletters."'>".$statename."</option>";
					
	$outputtext .= "</option>";

	foreach($states as $key=>$value)
	{
		$outputtext .= "<option value='".$key."'>".$value."</option>";
	}

	$outputtext .= "</select>";

	//<input type='text' id='newstate' name='newstate' onkeyup='testState()' placeholder='Virginia' size='30' />
	
	$outputtext .= "
					</th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='current_zip_test'>Zip Code</th>
					<th colspan='2' class='center'><input type='text' id='current_zip' name='current_zip' onchange='testZip(this)' onkeyup='testZip(this)' placeholder='Zip Code' value='".$_SESSION['del_zip']."' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='4' class='center'><input type='submit' id='stylebutton' value='Enter Address' title='Enter Address' /></th>
					</tr>
					
					<tr>
					<th colspan='4' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='4' class='center'>
						<a href='javascript:registerPopup()'>Register</a> &bull;
						<a href='javascript:forgotPasswordPopup()'>Forgot Password?</a>
					</th>
					</tr>
	
					</table>
	
				</form>
			<center>
		";
	
    /// var_dump($error);
    // mysqli_close($dbc);
    
    echo $outputtext;
?>