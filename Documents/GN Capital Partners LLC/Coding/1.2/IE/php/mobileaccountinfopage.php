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
				<table id='topbartable'>
					<tr>
						<td colspan='2'>
							<h3>";
					
	if($_SESSION['first_name'])
	{
		$outputtext .= $_SESSION['first_name']."'s";
	}
	else
	{
		$outputtext .= "Your";
	}
	
	$outputtext .= " Information
							</h3>
						</td>
					</tr>
				</table>
			</center>
		</div>
		";
		
	$outputtext .= "
		<div id='main'>
			<center>	
				<form onSubmit='updateInfo(this);return false' method='post' class='updateinfo_form' autocomplete='off'>
	
					<table id='registrationtable'>
					
					<tr>
					<th colspan='4' class='center' >Account Information</th>
					</tr>
					
					<tr>
					<th colspan='2' class='optional' id='usernametest'>Username</th>
					<th colspan='2' class='center'><input type='text' id='newusername' name='newusername' onkeyup='testUsernameUpdate()' placeholder='";
					
	if($_SESSION['username'])
	{
		$outputtext .= $_SESSION['username'];
	}
	else
	{
		$outputtext .= "Username";
	}
					
	$outputtext .= "' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='emailtest'>Email Address</th>
					<th colspan='2' class='center'><input type='email' id='newemail' name='newemail' onkeyup='testEmailUpdate()' placeholder='";
					
	if($_SESSION['email'])
	{
		$outputtext .= $_SESSION['email'];
	}
	else
	{
		$outputtext .= "Email Address";
	}
					
	$outputtext .= "' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='confirmemailtest'>Confirm Email</th>
					<th colspan='2' class='center'><input type='email' id='confirmemail' name='confirmemail' onkeyup='testConfirmEmailUpdate()' placeholder='Confirm Email' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='phonenumbertest'>Phone Number</th>
					<th colspan='2' class='center'><input type='tel' id='newphonenumber' name='newphonenumber' onkeyup='testPhoneNumberUpdate()' placeholder='";
					
	if($_SESSION['phone'])
	{
		$outputtext .= $_SESSION['phone'];
	}
	else
	{
		$outputtext .= "XXX-XXX-XXXX";
	}
					
	$outputtext .= "' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='firstnametest'>First Name</th>
					<th colspan='2' class='center'><input type='text' id='newfirstname' name='newfirstname' onkeyup='testFirstNameUpdate()' placeholder='";
					
	if($_SESSION['first_name'])
	{
		$outputtext .= $_SESSION['first_name'];
	}
	else
	{
		$outputtext .= "First Name";
	}
					
	$outputtext .= "' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='lastnametest'>Last Name</th>
					<th colspan='2' class='center'><input type='text' id='newlastname' name='newlastname' onkeyup='testLastNameUpdate()' placeholder='";
					
	if($_SESSION['last_name'])
	{
		$outputtext .= $_SESSION['last_name'];
	}
	else
	{
		$outputtext .= "Last Name";
	}
					
	$outputtext .= "' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='2' class='optional' id='deliverypreferencetest'>Delivery Preference</th>
					<th colspan='2' class='center'>
						<select type='text' id='newdeliverypreference' name='newdeliverypreference' onchange='' >
					";
	
	if($_SESSION['delivery_preference']=='pickup')
	{
		$outputtext .= "<option value='delivery'>Delivery</option>";
		$outputtext .= "<option selected='selected' value='pickup'>Pick Up</option>";
	}
	else
	{
		$outputtext .= "<option selected='selected' value='delivery'>Delivery</option>";
		$outputtext .= "<option value='pickup'>Pick Up</option>";
	}

	$outputtext .= "	</select>
					</th>
					</tr>
					
					<tr>
					<th colspan='4' class='center' >Delivery Address</th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='addresstest'>Street Address</th>
					<th colspan='2' class='center'><input type='text' id='newaddress' name='newaddress' onkeyup='testAddressUpdate()' placeholder=".'"';
					
	if($_SESSION['del_address'])
	{
		$outputtext .= $_SESSION['del_address'];
	}
	else
	{
		$outputtext .= "Street Address";
	}
					
	$outputtext .= '"'." size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='citytest'>City</th>
					<th colspan='2' class='center'><input type='text' id='newcity' name='newcity' onkeyup='testCityUpdate()' placeholder='";
					
	if($_SESSION['del_city'])
	{
		$outputtext .= $_SESSION['del_city'];
	}
	else
	{
		$outputtext .= "City";
	}
					
	$outputtext .= "' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='statetest'>State</th>
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

	$outputtext .= "<select type='text' id='newstate' name='newstate' onchange='testStateUpdate()' >";
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
					<th colspan='2' class='optional' id='zipcodetest'>Zip Code</th>
					<th colspan='2' class='center'><input type='text' id='newzipcode' name='newzipcode' onkeyup='testZipCodeUpdate()' placeholder='";
					
	if($_SESSION['del_zip'])
	{
		$outputtext .= $_SESSION['del_zip'];
	}
	else
	{
		$outputtext .= "Zip Code";
	}
					
	$outputtext .= "' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='4' class='center' >Billing Address</th>
					</tr>
					
					<tr>
					<th colspan='2' class='optional' id='billnametest'>Name On Card</th>
					<th colspan='2' class='center'><input type='text' id='newbillname' name='newbillname' onkeyup='testBillNameUpdate()' placeholder='";
					
	if($_SESSION['bill_name'])
	{
		$outputtext .= $_SESSION['bill_name'];
	}
	else
	{
		$outputtext .= "Name On Card";
	}
					
	$outputtext .= "' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='billaddresstest'>Street Address</th>
					<th colspan='2' class='center'><input type='text' id='newbilladdress' name='newbilladdress' onkeyup='testBillAddressUpdate()' placeholder=".'"';
					
	if($_SESSION['bill_address'])
	{
		$outputtext .= $_SESSION['bill_address'];
	}
	else
	{
		$outputtext .= "Street Address";
	}
					
	$outputtext .= '"'." size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='billcitytest'>City</th>
					<th colspan='2' class='center'><input type='text' id='newbillcity' name='newbillcity' onkeyup='testBillCityUpdate()' placeholder='";
					
	if($_SESSION['bill_city'])
	{
		$outputtext .= $_SESSION['bill_city'];
	}
	else
	{
		$outputtext .= "City";
	}
					
	$outputtext .= "' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='billstatetest'>State</th>
					<th colspan='2' class='center'>
		";
	
	$billstateletters = $_SESSION['bill_state'];

	if($billstateletters)
	{
		$billstatename = $states[$stateletters];
	}
	else
	{
		$billstatename = "Select Your State...";
	}
	
	$outputtext .= "<select type='text' id='newbillstate' name='newbillstate' onchange='testBillStateUpdate()' >";
	$outputtext .= "<option selected='selected' value='".$billstateletters."'>".$billstatename."</option>";
					
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
					<th colspan='2' class='optional' id='billzipcodetest'>Zip Code</th>
					<th colspan='2' class='center'><input type='text' id='newbillzipcode' name='newbillzipcode' onkeyup='testBillZipCodeUpdate()' placeholder='";
					
	if($_SESSION['bill_zip'])
	{
		$outputtext .= $_SESSION['bill_zip'];
	}
	else
	{
		$outputtext .= "Zip Code";
	}
					
	$outputtext .= "' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='4' class='center' >New Password</th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='passwordtest'>Password</th>
					<th colspan='2' class='center'><input type='password' id='newpassword' name='newpassword' onkeyup='testPasswordUpdate()' placeholder='Password' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='confirmpasswordtest'>Confirm Password</th>
					<th colspan='2' class='center'><input type='password' id='confirmpassword' name='confirmpassword' onkeyup='testConfirmPasswordUpdate()' placeholder='Confirm Password' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='4' class='center'><center><hr></center></th>
					</tr>
					
					</table>
					";
					
	$outputtext .= "
					<table id='registrationtable'>
					
					<tr>
					<th colspan='2' class='test' id='currentpasswordtest'>Current Password</th>
					<th colspan='2' class='center'><input type='password' id='currentpassword' name='currentpassword' onkeyup='testPasswordCurrent()' placeholder='Current Password' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='4' class='center'><input type='submit' id='registerbutton' value='Update Info' title='Disabled' disabled='true' /></th>
					</tr>
	
					</table>
	
				</form>
			<center>
		</div>
		";


	
    /// var_dump($error);
    // mysqli_close($dbc);
    
    echo $outputtext;
?>