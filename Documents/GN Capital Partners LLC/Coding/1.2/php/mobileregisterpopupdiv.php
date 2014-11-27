<?php

$outputtext .= "
<center>
<form id='userregform' onSubmit='registerUser(this); return false;' method='post' class='updateinfo_form' autocomplete='off'>
	<br>
	<br>
	
	<table id='registrationtable'>
	
	<tr>
	<th colspan='2' class='test' id='username_test'>Username</th>
	<th colspan='2' class='center'><input type='text' id='username' name='username' onkeyup='testUsername(this)' placeholder='Username' size='30' /></th>
	</tr>
	
	<tr>
	<th colspan='2' class='test' id='email_test'>Email Address</th>
	<th colspan='2' class='center'><input type='email' id='email' name='email' onkeyup='testEmail(this)' placeholder='Email Address' size='30' /></th>
	</tr>
	
	<tr>
	<th colspan='2' class='test' id='confirm_email_test'>Confirm Email</th>
	<th colspan='2' class='center'><input type='email' id='confirm_email' name='confirm_email' onkeyup='testConfirmEmail(this)' placeholder='Confirm Email' size='30' /></th>
	</tr>
	
	<tr>
	<th colspan='2' class='test' id='phone_number_test'>Phone Number</th>
	<th colspan='2' class='center'><input type='tel' id='phone_number' name='phone_number' onkeyup='testPhoneNumber(this)' placeholder='xxx-xxx-xxxx' size='30' /></th>
	</tr>
	
	<tr>
	<th colspan='2' class='test' id='first_name_test'>First Name</th>
	<th colspan='2' class='center'><input type='text' id='first_name' name='first_name' onkeyup='testFirstName(this)' placeholder='First Name' size='30' /></th>
	</tr>
	
	<tr>
	<th colspan='2' class='test' id='last_name_test'>Last Name</th>
	<th colspan='2' class='center'><input type='text' id='last_name' name='last_name' onkeyup='testLastName(this)' placeholder='Last Name' size='30' /></th>
	</tr>
	
	<tr>
	<th colspan='2' class='test' id='password_test'>Password</th>
	<th colspan='2' class='center'><input type='password' id='password' name='password' onkeyup='testPassword(this)' placeholder='Password' size='30' /></th>
	</tr>
	
	<tr>
	<th colspan='2' class='test' id='confirm_password_test'>Confirm Password</th>
	<th colspan='2' class='center'><input type='password' id='confirm_password' name='confirm_password' onkeyup='testConfirmPassword(this)' placeholder='Confirm Password' size='30' /></th>
	</tr>
	
	<tr>
	<th colspan='4' class='center'>&nbsp;</th>
	</tr>
	
	<tr>
	<th colspan='3' class='test' id='terms_of_use_test'><input type='checkbox' onClick='testTermsOfUse(this)' id='terms_of_use' name='terms_of_use' value='terms_of_use'>I agree with the <a href='javascript:window.open(".'"http://gottanom.com/termsofuse.php"'.")'><b>terms of use</b></a>.</th>
	<th colspan='1' class='optional' id='registerbutton_test'><input type='submit' id='registerbutton' value='Register' title='Register' disabled /></th>
	</tr>
	
	</table>
	
	<br>
	
</form>
<center>";

?>