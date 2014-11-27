<?php

	session_start();
	
	include "connect.php";
	
	include "stateslist.php";
	
	$outputtext .= "
	<div id='main_right' class='col span_3_of_4'>
		<div id='top' class='section group'>
			<div class='col span_1_of_1'>
				<h1>
					";
					
	if($_SESSION['first_name'])
	{
		$outputtext .= htmlspecialchars($_SESSION['first_name'], ENT_QUOTES)."'s";
	}
	else
	{
		$outputtext .= "Your";
	}
	
	$outputtext .= " Information
				</h1>
			</div>
		</div>
		";
		
	$outputtext .= "
		<div id='main'>
			<center>	
				<form onSubmit='updateInfo(this);return false' method='post' class='updateinfo_form' autocomplete='off'>
	
					<div id='registrationtable' class='section group'>
					
					<div class='col span_2_of_2'>
					<th colspan='2' class='center' >Account Information</th>
					</div>
					
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='username_test'><input type='text' id='username' name='username' onkeyup='testUsername(this)' placeholder='";
					
	if($_SESSION['username'])
	{
		$outputtext .= htmlspecialchars($_SESSION['username'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "Username";
	}
					
	$outputtext .= "' size='30' /></th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='email_test'><input type='email' id='email' name='email' onkeyup='testEmail(this)' placeholder='";
					
	if($_SESSION['email'])
	{
		$outputtext .= htmlspecialchars($_SESSION['email'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "Email Address";
	}
					
	$outputtext .= "' size='30' /></th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='confirm_email_test'><input type='email' id='confirm_email' name='confirm_email' onkeyup='testConfirmEmail(this)' placeholder='Confirm Email' size='30' /></th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='phone_number_test'><input type='tel' id='phone_number' name='phone_number' onkeyup='testPhoneNumber(this)' placeholder='";
					
	if($_SESSION['phone'])
	{
		$outputtext .= htmlspecialchars($_SESSION['phone'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "XXX-XXX-XXXX";
	}
					
	$outputtext .= "' size='30' /></th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='first_name_test'><input type='text' id='first_name' name='first_name' onkeyup='testFirstName(this)' placeholder='";
					
	if($_SESSION['first_name'])
	{
		$outputtext .= htmlspecialchars($_SESSION['first_name'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "First Name";
	}
					
	$outputtext .= "' size='30' /></th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='last_name_test'><input type='text' id='last_name' name='last_name' onkeyup='testLastName(this)' placeholder='";
					
	if($_SESSION['last_name'])
	{
		$outputtext .= htmlspecialchars($_SESSION['last_name'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "Last Name";
	}
					
	$outputtext .= "' size='30' /></th>
					</div>
					
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='delivery_preference_test'>
						<select type='text' id='delivery_preference' name='delivery_preference' onchange='' >
					";
	
	if($_SESSION['delivery_preference']=='delivery')
	{
		$outputtext .= "<option selected='selected' value='delivery'>Delivery</option>";
		$outputtext .= "<option value='pickup'>Pick Up</option>";
	}
	else
	{
		$outputtext .= "<option value='delivery'>Delivery</option>";
		$outputtext .= "<option selected='selected' value='pickup'>Pick Up</option>";
	}

	$outputtext .= "	</select>
					</th>
					</div>
					
					<div class='col span_2_of_2'>
					<th colspan='2' class='center' >Delivery Address</th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='address_test'><input type='text' id='address' name='address' onkeyup='testAddress(this)' placeholder=".'"';
					
	if($_SESSION['del_address'])
	{
		$outputtext .= htmlspecialchars($_SESSION['del_address'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "Street Address";
	}
					
	$outputtext .= '"'." size='30' /></th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='city_test'><input type='text' id='city' name='city' onkeyup='testCity(this)' placeholder='";
					
	if($_SESSION['del_city'])
	{
		$outputtext .= htmlspecialchars($_SESSION['del_city'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "City";
	}
					
	$outputtext .= "' size='30' /></th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='state_test'>
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

	$outputtext .= "<select type='text' id='state' name='state' onchange='testState(this)' >";
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
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='zip_code_test'><input type='text' id='zip_code' name='zip_code' onkeyup='testZipCode(this)' placeholder='";
					
	if($_SESSION['del_zip'])
	{
		$outputtext .= htmlspecialchars($_SESSION['del_zip'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "Zip Code";
	}
					
	$outputtext .= "' size='30' /></th>
					</div>
					
					<div class='col span_2_of_2'>
					<th colspan='2' class='center' >Billing Address</th>
					</div>
					
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='bill_name_test'><input type='text' id='bill_name' name='bill_name' onkeyup='testBillName(this)' placeholder='";
					
	if($_SESSION['bill_name'])
	{
		$outputtext .= htmlspecialchars($_SESSION['bill_name'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "Name On Card";
	}
					
	$outputtext .= "' size='30' /></th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='bill_address_test'><input type='text' id='bill_address' name='bill_address' onkeyup='testBillAddress(this)' placeholder=".'"';
					
	if($_SESSION['bill_address'])
	{
		$outputtext .= htmlspecialchars($_SESSION['bill_address'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "Street Address";
	}
					
	$outputtext .= '"'." size='30' /></th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='bill_city_test'><input type='text' id='bill_city' name='bill_city' onkeyup='testBillCity(this)' placeholder='";
					
	if($_SESSION['bill_city'])
	{
		$outputtext .= htmlspecialchars($_SESSION['bill_city'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "City";
	}
					
	$outputtext .= "' size='30' /></th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='bill_state_test'>
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
	
	$outputtext .= "<select type='text' id='bill_state' name='bill_state' onchange='testBillState(this)' >";
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
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='bill_zip_code_test'><input type='text' id='bill_zip_code' name='bill_zip_code' onkeyup='testBillZipCode(this)' placeholder='";
					
	if($_SESSION['bill_zip'])
	{
		$outputtext .= htmlspecialchars($_SESSION['bill_zip'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "Zip Code";
	}
					
	$outputtext .= "' size='30' /></th>
					</div>
					
					<div class='col span_1_of_2'>
					<th colspan='2' class='center' >Charities</th>
					</div>
					
					<div class='col span_1_of_2'>
					<td colspan='2' class='center' >Selecting none will result in donations being split amongst all.</td>
					</div>
					";

	$user_id = $_SESSION['user_id'];
	$query_get_charity = "
		SELECT * FROM Customer_Charity 
		WHERE user_id = '$user_id'
		";
	$result_get_charity = mysqli_query($dbc, $query_get_charity);
	$customer_charity_info = array();

	if (@mysqli_num_rows($result_get_charity) > 0)//if Query is successfull 
	{ // A match was made.
		while($customer_charity_row = mysqli_fetch_array($result_get_charity, MYSQLI_ASSOC))
		{
			$customer_charity_info[$customer_charity_row['charity_id']] = $customer_charity_row['donate'];
		}
	}
	
	$loc_id = $_SESSION['loc_id'];
	$query_get_charity = "
	SELECT * FROM Charity 
	WHERE loc_id = '$loc_id'
	";
	$result_get_charity = mysqli_query($dbc, $query_get_charity);
	while($charity_row = mysqli_fetch_array($result_get_charity, MYSQLI_ASSOC))
	{
		$outputtext .= "
				<div class='col span_1_of_2'>
				<th colspan='1' class='optional' id='charity_test'>".$charity_row['charity_name']."</th>
				<th colspan='1' class='center'><input type='checkbox' id='charity' value='".$charity_row['charity_id']."' name='".$charity_row['charity_id']."' size='30' ";
				
		if($customer_charity_info[$charity_row['charity_id']])
		{
			$outputtext .= "checked='checked' ";
		}
				
		$outputtext .= "/></th>
				</div>
			";
	}
					
	$outputtext .= "
					<div class='col span_2_of_2'>
					<th colspan='2' class='center'>New Password</th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='password_test'><input type='password' id='password' name='password' onkeyup='testPassword(this)' placeholder='Password' size='30' /></th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' class='optional' id='confirm_password_test'><input type='password' id='confirm_password' name='confirm_password' onkeyup='testConfirmPassword(this)' placeholder='Confirm Password' size='30' /></th>
					</div>
					
					<div class='col span_1_of_2'>
					<th colspan='2' class='center'><center><hr/></center></th>
					</div>
					
					<div class='col span_1_of_2'>
					<th colspan='2' class='test' id='current_password_test'><input type='password' id='current_password' name='current_password' onkeyup='testPasswordCurrent(this)' placeholder='Current Password' size='30' /></th>
					</div>
	
					<div class='col span_1_of_2'>
					<th colspan='2' id='registerbutton_test' class='center'><input type='submit' id='registerbutton' value='Update Info' title='Disabled' disabled='true' /></th>
					</div>
	
					</div>
	
				</form>
			<center>
		</div>
	</div>
	";
	
    /// var_dump($error);
    // mysqli_close($dbc);
    
    echo $outputtext;
?>