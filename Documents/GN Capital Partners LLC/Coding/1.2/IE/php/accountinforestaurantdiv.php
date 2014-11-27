<?php
	session_start();

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
		<div id='top'>
			<center>
				<h1>
					".$_SESSION['name']." Information
				</h1>
			</center>
		</div>
		";
		
	$outputtext .= "
		<div id='main'>
		";
		
		$outputtext .= "
			<script>
				window.onbeforeunload = function() {
					return 'You have unsaved changes!';
				}
			</script>
			";
			
	//$outputtext .= htmlspecialchars('<html></html>');
	
	$outputtext .= "
			<center>	
				<form onSubmit='updateRestaurantInfo(this);return false;' method='post' class='updateinfo_form' autocomplete='off'>
	
					<table id='registrationtable'>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='usernametest'>Username</th>
					<th colspan='2' class='center'><input type='text' id='newusername' name='newusername' onkeyup='testUsernameUpdate()' placeholder='".htmlspecialchars($_SESSION['username'], ENT_QUOTES)."' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='restaurantnametest'>Restaurant Name</th>
					<th colspan='2' class='center'><input type='text' id='newrestaurantname' name='newrestaurantname' onkeyup='testRestaurantNameUpdate()' placeholder=".'"'.htmlspecialchars($_SESSION['name'], ENT_QUOTES).'"'." size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='emailtest'>Email Address</th>
					<th colspan='2' class='center'><input type='email' id='newemail' name='newemail' onkeyup='testEmailUpdate()' placeholder='".htmlspecialchars($_SESSION['email'], ENT_QUOTES)."' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='confirmemailtest'>Confirm Email</th>
					<th colspan='2' class='center'><input type='email' id='confirmemail' name='confirmemail' onkeyup='testConfirmEmailUpdate()' placeholder='Confirm Email' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='phonenumbertest'>Phone Number</th>
					<th colspan='2' class='center'><input type='tel' id='newphonenumber' name='newphonenumber' onkeyup='testPhoneNumberUpdate()' placeholder='".htmlspecialchars($_SESSION['phone'], ENT_QUOTES)."' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='callinordertest'>Call In Order Number</th>
					<th colspan='2' class='center'><input type='tel' id='callinorder' name='callinorder' onkeyup='testCallInOrderUpdate()' placeholder='".htmlspecialchars($_SESSION['call_in_order'], ENT_QUOTES)."' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='faxtest'>Fax Number</th>
					<th colspan='2' class='center'><input type='tel' id='fax' name='fax' onkeyup='testFaxUpdate()' placeholder='".htmlspecialchars($_SESSION['fax'], ENT_QUOTES)."' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='firstnametest'>Owner&#39;s First Name</th>
					<th colspan='2' class='center'><input type='text' id='newfirstname' name='newfirstname' onkeyup='testOwnerFirstNameUpdate()' placeholder='".htmlspecialchars($_SESSION['owner_name'], ENT_QUOTES)."' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='lastnametest'>Owner&#39;s Last Name</th>
					<th colspan='2' class='center'><input type='text' id='newlastname' name='newlastname' onkeyup='testOwnerLastNameUpdate()' placeholder='".htmlspecialchars($_SESSION['owner_last'], ENT_QUOTES)."' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='addresstest'>Street Address</th>
					<th colspan='2' class='center'><input type='text' id='newaddress' name='newaddress' onkeyup='testAddressUpdate()' placeholder=".'"'.htmlspecialchars($_SESSION['address'], ENT_QUOTES).'"'." size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='citytest'>City</th>
					<th colspan='2' class='center'><input type='text' id='newcity' name='newcity' onkeyup='testCityUpdate()' placeholder='".htmlspecialchars($_SESSION['city'], ENT_QUOTES)."' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='statetest'>State</th>
					<th colspan='2' class='center'>
		";
	
	$states = statesList();
	$stateletters = $_SESSION['state'];

	$outputtext .= "<select type='text' id='newstate' name='newstate' onchange='testStateUpdate()' >";
	$outputtext .= "<option selected='selected' value='".$stateletters."'>".$states[$stateletters]."</option>";

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
					<th colspan='2' class='optional' id='zipcodetest'>Zip Code</th>
					<th colspan='2' class='center'><input type='text' id='newzipcode' name='newzipcode' onkeyup='testZipCodeUpdate()' placeholder='".htmlspecialchars($_SESSION['zip'], ENT_QUOTES)."' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='passwordtest'>Password</th>
					<th colspan='2' class='center'><input type='password' id='newpassword' name='newpassword' onkeyup='testPasswordUpdate()' placeholder='Password' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='confirmpasswordtest'>Confirm Password</th>
					<th colspan='2' class='center'><input type='password' id='confirmpassword' name='confirmpassword' onkeyup='testConfirmPasswordUpdate()' placeholder='Confirm Password' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='deliverypickuptest'>Services Provided</th>
					<th colspan='2' class='left'> &nbsp; &nbsp;<input type='checkbox' onClick='testDeliveryPickup()' id='wedeliver' name='wedeliver' value='wedeliver' ";
					
	if($_SESSION['delivers']==1)
	{
		$outputtext .= "checked='checked'";
	}
					
	$outputtext .= ">Delivery &nbsp; <input type='checkbox' onClick='testDeliveryPickup()' id='weallowpickup' name='weallowpickup' value='weallowpickup' ";
	
	if($_SESSION['pickup']==1)
	{
		$outputtext .= "checked='checked'";
	}
	
	$outputtext .= ">Pickup</th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='deliveryfeetest'>Delivery Fee</th>
					<th colspan='2' class='center'><input type='number' id='deliveryfee' name='deliveryfee' min='0' max='10' step='.01' onkeyup='' placeholder='".htmlspecialchars($_SESSION['delivery_fee'], ENT_QUOTES)."' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='deliveryminimumtest'>Delivery Minimum</th>
					<th colspan='2' class='center'><input type='number' id='deliveryminimum' name='deliveryminimum' min='0' max='20' step='.01' onkeyup='' placeholder='".htmlspecialchars($_SESSION['delivery_minimum'], ENT_QUOTES)."' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='4' class='optional' id='deliveryminimumtest'><a href='javascript:deliveryLocationsPopup()'>Delivery Locations</a></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='4' class='optional' id='deliveryminimumtest'><a href='javascript:cardTypesPopup()'>Card Types</a></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='6' class='center'><center><hr></center></th>
					</tr>
					
					</table>
					
					";
					
	$hours = unserialize($_SESSION['hours']);
					
	$outputtext .= "<table id='registrationtable'>
					
					<tr>
					<th colspan='6' class='center'>Hours</th>
					</tr>
					
					<tr>
					<th colspan='2' class='center'>&nbsp;</th>
					<th colspan='1' class='center'>Open</th>
					<th colspan='1' class='center'>Close</th>
					<th colspan='1' class='center'>Open</th>
					<th colspan='1' class='center'>Close</th>
					</tr>
					
					<tr>
					<th colspan='2' class='center'>Monday</th>
					<th colspan='1' class='center'><input type='time' id='mondayopen1' name='mondayopen1' onkeyup='' value='".$hours[1][0]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='mondayclose1' name='mondayclose1' onkeyup='' value='".$hours[1][1]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='mondayopen2' name='mondayopen2' onkeyup='' value='".$hours[1][2]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='mondayclose2' name='mondayclose2' onkeyup='' value='".$hours[1][3]."' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='2' class='center'>Tuesday</th>
					<th colspan='1' class='center'><input type='time' id='tuesdayopen1' name='tuesdayopen1' onkeyup='' value='".$hours[2][0]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='tuesdayclose1' name='tuesdayclose1' onkeyup='' value='".$hours[2][1]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='tuesdayopen2' name='tuesdayopen2' onkeyup='' value='".$hours[2][2]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='tuesdayclose2' name='tuesdayclose2' onkeyup='' value='".$hours[2][3]."' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='2' class='center'>Wednesday</th>
					<th colspan='1' class='center'><input type='time' id='wednesdayopen1' name='wednesdayopen1' onkeyup='' value='".$hours[3][0]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='wednesdayclose1' name='wednesdayclose1' onkeyup='' value='".$hours[3][1]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='wednesdayopen2' name='wednesdayopen2' onkeyup='' value='".$hours[3][2]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='wednesdayclose2' name='wednesdayclose2' onkeyup='' value='".$hours[3][3]."' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='2' class='center'>Thursday</th>
					<th colspan='1' class='center'><input type='time' id='thursdayopen1' name='thursdayopen1' onkeyup='' value='".$hours[4][0]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='thursdayclose1' name='thursdayclose1' onkeyup='' value='".$hours[4][1]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='thursdayopen2' name='thursdayopen2' onkeyup='' value='".$hours[4][2]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='thursdayclose2' name='thursdayclose2' onkeyup='' value='".$hours[4][3]."' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='2' class='center'>Friday</th>
					<th colspan='1' class='center'><input type='time' id='fridayopen1' name='fridayopen1' onkeyup='' value='".$hours[5][0]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='fridayclose1' name='fridayclose1' onkeyup='' value='".$hours[5][1]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='fridayopen2' name='fridayopen2' onkeyup='' value='".$hours[5][2]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='fridayclose2' name='fridayclose2' onkeyup='' value='".$hours[5][3]."' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='2' class='center'>Saturday</th>
					<th colspan='1' class='center'><input type='time' id='saturdayopen1' name='saturdayopen1' onkeyup='' value='".$hours[6][0]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='saturdayclose1' name='saturdayclose1' onkeyup='' value='".$hours[6][1]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='saturdayopen2' name='saturdayopen2' onkeyup='' value='".$hours[6][2]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='saturdayclose2' name='saturdayclose2' onkeyup='' value='".$hours[6][3]."' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='2' class='center'>Sunday</th>
					<th colspan='1' class='center'><input type='time' id='sundayopen1' name='sundayopen1' onkeyup='' value='".$hours[0][0]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='sundayclose1' name='sundayclose1' onkeyup='' value='".$hours[0][1]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='sundayopen2' name='sundayopen2' onkeyup='' value='".$hours[0][2]."' size='30' /></th>
					<th colspan='1' class='center'><input type='time' id='sundayclose2' name='sundayclose2' onkeyup='' value='".$hours[0][3]."' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='6' class='center'><center><hr></center></th>
					</tr>
					
					</table>
					
					<table id='registrationtable'>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='test' id='currentpasswordtest'>Current Password</th>
					<th colspan='2' class='center'><input type='password' id='currentpassword' name='currentpassword' onkeyup='testPasswordCurrent()' placeholder='Current Password' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='4' class='center'><input type='submit' id='registerbutton' value='Update Info' title='Disabled' disabled='true' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					</table>
	
				</form>
			<center>
		</div>
		";


	
    /// var_dump($error);
    // mysqli_close($dbc);
?>