<?php

$outputtext .= "
					<div id='register_div' class='register_div' scrollTo='true' flash='true' focus='true'>
						<a name='register_div'></a>
						<div id='register_title' class='register_title content'>
							Register
						</div>
						<form id='user_registration_form' onSubmit='submitForm(this,".'"registeruser"'."); return false;' method='post' class='updateinfo_form content' autocomplete='off'>
						
							<div id='register_error'></div>
							
							<table id='registrationtable'>

							<tr>
							<th colspan='2' class='test' id='email_test'><input type='email' id='email' name='email' onkeyup='testEmail(this)' placeholder='Email Address' size='30' /><div></div></th>
							</tr>

							<tr>
							<th colspan='2' class='test' id='confirm_email_test'><input type='email' id='confirm_email' name='confirm_email' onkeyup='testConfirmEmail(this)' placeholder='Confirm Email' size='30' /><div></div></th>
							</tr>

							<tr>
							<th colspan='2' class='test' id='phone_number_test'><input type='tel' id='phone_number' name='phone_number' onkeyup='testPhoneNumber(this)' placeholder='xxx-xxx-xxxx' size='30' /><div></div></th>
							</tr>

							<tr>
							<th colspan='2' class='test' id='first_name_test'><input type='text' id='first_name' name='first_name' onkeyup='testFirstName(this)' placeholder='First Name' size='30' /><div></div></th>
							</tr>

							<tr>
							<th colspan='2' class='test' id='last_name_test'><input type='text' id='last_name' name='last_name' onkeyup='testLastName(this)' placeholder='Last Name' size='30' /><div></div></th>
							</tr>

							<tr>
							<th colspan='2' class='test' id='username_test'><input type='text' id='username' name='username' onchange='testUsername(this)' onkeyup='testUsername(this)' placeholder='Username' size='30' /><div></div></th>
							</tr>

							<tr>
							<th colspan='2' class='test' id='password_test'><input type='password' id='password' name='password' onchange='testPassword(this)' onkeyup='testPassword(this)' placeholder='Password' size='30' autocomplete='off' /><div></div></th>
							</tr>

							<tr>
							<th colspan='2' class='test' id='confirm_password_test'><input type='password' id='confirm_password' name='confirm_password' onkeyup='testConfirmPassword(this)' placeholder='Confirm Password' size='30' /><div></div></th>
							</tr>

							<tr>
							<th colspan='2' class='test' id='terms_of_use_test'><input type='checkbox' onClick='testTermsOfUse(this)' id='terms_of_use' name='terms_of_use'>I agree with the <a target='_blank' href='http://gottanom.com/termsofuse.php'><b>terms of use</b></a>.</th>
							</tr>

							<tr>
							<th colspan='2' class='optional' id='registerbutton_test'><input type='submit' id='registerbutton' value='Register' title='Register' disabled /></th>
							</tr>

							</table>

						</form>
						<script>
							testForm('#user_registration_form');
							checkForm('#user_registration_form');
						</script>
					</div>
					";
					
?>