<?php

	session_start();
	
	include "connect.php";
	
	$outputtext .= "
	<div id='main_right' class='col span_3_of_4'>
		<div id='top'>
			<center>
				<h1>
					";
					
	if($_SESSION['name'])
	{
		$outputtext .= htmlspecialchars($_SESSION['name'], ENT_QUOTES)."'s";
	}
	else
	{
		$outputtext .= "Your";
	}
	
	$outputtext .= " Information
				</h1>
			</center>
		</div>
		";
		
	$outputtext .= "
		<div id='main'>
			<center>	
				<form onSubmit='submitForm(this,".'"adminupdateinfo"'.");return false' method='post' class='updateinfo_form' autocomplete='off'>
	
					<table id='registrationtable'>
					
					<tr>
					<th colspan='2' class='center' >Account Information</th>
					</tr>
					
					<tr>
					<th colspan='2' class='optional' id='first_name_test'><input type='text' id='first_name' name='first_name' onkeyup='testFirstName(this,{".'"required"'.":false})' placeholder='";
					
	if($_SESSION['first_name'])
	{
		$outputtext .= htmlspecialchars($_SESSION['first_name'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "First Name";
	}
					
	$outputtext .= "' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='last_name_test'><input type='text' id='last_name' name='last_name' onkeyup='testLastName(this,{".'"required"'.":false})' placeholder='";
					
	if($_SESSION['last_name'])
	{
		$outputtext .= htmlspecialchars($_SESSION['last_name'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "Last Name";
	}
					
	$outputtext .= "' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='phone_number_test'><input type='tel' id='phone_number' name='phone_number' onkeyup='testPhoneNumber(this,{".'"required"'.":false})' placeholder='";
					
	if($_SESSION['phone'])
	{
		$outputtext .= htmlspecialchars($_SESSION['phone'], ENT_QUOTES);
	}
	else
	{
		$outputtext .= "XXX-XXX-XXXX";
	}
					
	$outputtext .= "' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='password_test'><input type='password' id='password' name='password' onkeyup='testPassword(this,{".'"required"'.":false})' placeholder='Password' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' class='optional' id='confirm_password_test'><input type='password' id='confirm_password' name='confirm_password' onkeyup='testConfirmPassword(this,{".'"required"'.":false})' placeholder='Confirm Password' size='30' /></th>
					</tr>
					
					<tr>
					<th colspan='2' class='center'><center><hr/></center></th>
					</tr>
					
					<tr>
					<th colspan='2' class='test' id='current_password_test'><input type='password' id='current_password' name='current_password' onkeyup='testPassword(this)' placeholder='Current Password' size='30' /></th>
					</tr>
	
					<tr>
					<th colspan='2' id='registerbutton_test' class='center'><input type='submit' id='registerbutton' value='Update Info' title='Disabled' disabled='true' /></th>
					</tr>
	
					</table>
	
				</form>
			<center>
		</div>
	</div>
	";
	
    /// var_dump($error);
    // mysqli_close($dbc);
    
    echo $outputtext;
?>