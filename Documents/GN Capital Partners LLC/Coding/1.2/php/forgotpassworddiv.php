<?php

$outputtext .= "
					<div id='login_div' class='login_div content' flash='true' focus='true'>
						<a name='login_div'></a>
						<div id='login_title' class='login_title content'>
							Forgot Password?
						</div>
						<form onSubmit='submitForm(this,".'"forgotpassword"'.");return false;' method='post' class='login_form content'>

							<table id='registrationtable'>
							
							<tr>
							<th colspan='2' class='center'><div id='forgot_password_message'>Enter either your username or your email address to get a new password emailed to you.</div></th>
							</tr>
							
							<tr>
							<th colspan='2' class='optional' id='username_test'><input type='text' id='username' name='username' onkeyup='testUsername(this,{".'"required"'.":false})' placeholder='Username' size='20' /></th>
							</tr>
							
							<tr>
							<th colspan='2' class='optional' id='email_test'>OR</th>
							</tr>

							<tr>
							<th colspan='2' class='optional' id='email_test'><input type='email' id='email' name='email' onkeyup='testEmail(this,{".'"required"'.":false,".'"confirm"'.":false})' placeholder='Email Address' size='20' /></th>
							</tr>

							<tr>
							<th colspan='2' class='center'><input type='submit' id='stylebutton' value='Reset Password' title='Reset Password' /></th>
							</tr>

							<tr>
							<th colspan='2' class='center'>
								<a onclick='submitForm(this,".'"logindiv"'.")'>Login</a> &bull;
								<a onclick='scrollToAnchor(".'"register_div"'.")'>Register</a>
							</th>
							</tr>

							</table>

						</form>
					</div>
					";
?>