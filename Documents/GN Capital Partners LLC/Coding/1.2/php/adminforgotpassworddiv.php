<?php

$outputtext .= "
					<div id='login_div' class='login_div content' flash='true' focus='true'>
						<a name='login_div'></a>
						<div id='login_title' class='login_title content'>
							Forgot Password?
						</div>
						<form id='admin_login_form' onSubmit='submitForm(this,".'"adminforgotpassword"'.");return false;' method='post' class='login_form content'>

							<table id='registrationtable'>
							
							<tr>
							<th colspan='2' class='center'><div id='forgot_password_message'>Enter your email address to get a new password emailed to you.</div></th>
							</tr>

							<tr>
							<th colspan='2' class='test' id='email_test'><input type='email' id='email' name='email' onkeyup='testEmail(this,{".'"required"'.":true,".'"confirm"'.":false})' placeholder='Email Address' size='20' /></th>
							</tr>

							<tr>
							<th colspan='2' class='center'><input type='submit' id='stylebutton' value='Reset Password' title='Reset Password' /></th>
							</tr>

							<tr>
							<th colspan='2' class='center'>
								<a href='javascript:submitForm(this,".'"adminlogindiv"'.")'>Login</a>
							</th>
							</tr>

							</table>

						</form>
						<script>
							testForm('#admin_login_form');
							checkForm('#admin_login_form');
						</script>
					</div>
					";
?>