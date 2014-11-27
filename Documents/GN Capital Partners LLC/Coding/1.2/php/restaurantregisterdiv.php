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
<script>
</script>
<center>
<form onSubmit='registerRestaurant(this); return false;' method='post' class='updateinfo_form' autocomplete='off'>
	<br>
	
	<table id='registrationtable'>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='username_test'>Username</th>
	<th colspan='2' class='center'><input type='text' id='username' name='username' onkeyup='testUsername(this)' placeholder='Username' size='30' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='restaurant_name_test'>Restaurant Name</th>
	<th colspan='2' class='center'><input type='text' id='restaurant_name' name='restaurant_name' onkeyup='testRestaurantName(this)' placeholder='Restaurant Name' size='30' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='email_test'>Email Address</th>
	<th colspan='2' class='center'><input type='email' id='email' name='email' onkeyup='testEmail(this)' placeholder='Email Address' size='30' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='confirm_email_test'>Confirm Email</th>
	<th colspan='2' class='center'><input type='email' id='confirm_email' name='confirm_email' onkeyup='testConfirmEmail(this)' placeholder='Confirm Email' size='30' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='phone_number_test'>Phone Number</th>
	<th colspan='2' class='center'><input type='tel' id='phone_number' name='phone_number' onkeyup='testPhoneNumber(this)' placeholder='xxx-xxx-xxxx' size='30' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='first_name_test'>Owner&#39;s First Name</th>
	<th colspan='2' class='center'><input type='text' id='first_name' name='first_name' onkeyup='testFirstName(this)' placeholder='Owner&#39;s First Name' size='30' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='last_name_test'>Owner&#39;s Last Name</th>
	<th colspan='2' class='center'><input type='text' id='last_name' name='last_name' onkeyup='testLastName(this)' placeholder='Owner&#39;s Last Name' size='30' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='address_test'>Street Address</th>
	<th colspan='2' class='center'><input type='text' id='address' name='address' onkeyup='testAddress(this)' placeholder='4400 University Drive' size='30' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='city_test'>City</th>
	<th colspan='2' class='center'><input type='text' id='city' name='city' onkeyup='testCity(this)' placeholder='Fairfax' size='30' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='state_test'>State</th>
	<th colspan='2' class='center'>
	";
	
$states = statesList();

$outputtext .= "<select type='text' id='state' name='state' onchange='testState(this)' >";
$outputtext .= "<option selected='selected'>Select your state...</option>";

foreach($states as $key=>$value)
{
	$outputtext .= "<option value='".$key."'>".$value."</option>";
}

$outputtext .= "</select>";

//<input type='text' id='newstate' name='newstate' onkeyup='testState()' placeholder='Virginia' size='30' />
	
$outputtext .= "
	</th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='zip_code_test'>Zip Code</th>
	<th colspan='2' class='center'><input type='text' id='zip_code' name='zip_code' onkeyup='testZip(this)' placeholder='22030' size='30' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='password_test'>Password</th>
	<th colspan='2' class='center'><input type='password' id='password' name='password' onkeyup='testPassword(this)' placeholder='Password' size='30' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='confirm_password_test'>Confirm Password</th>
	<th colspan='2' class='center'><input type='password' id='confirm_password' name='confirm_password' onkeyup='testConfirmPassword(this)' placeholder='Confirm Password' size='30' /></th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='1' class='center'>&nbsp;</th>
	<th colspan='2' class='test' id='delivery_pickup_test' id='we_deliver_test' id='we_allow_pickup_test'>Services Provided</th>
	<th colspan='2' class='left'> &nbsp; &nbsp;<input type='checkbox' onClick='testDeliveryPickup(this)' id='delivery_pickup' name='we_deliver' value='we_deliver'>Delivery &nbsp; <input type='checkbox' onClick='testDeliveryPickup(this)' id='delivery_pickup' name='we_allow_pickup' value='we_allow_pickup'>Pickup</th>
	<th colspan='1' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='6' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='4' class='test' id='terms_of_use_test'><input type='checkbox' onClick='testTermsOfUse(this)' id='terms_of_use' name='terms_of_use' value='terms_of_use'>I agree with the <a href='javascript:window.open(".'"http://gottanom.com/termsofuse.php"'.")'><b>terms of use</b></a>.</th>
	<th colspan='2' class='center'><input type='submit' id='registerbutton' value='Register' title='Register' disabled /></th>
	</tr>
	
	</table>
	
</form>
<center>
";


?>