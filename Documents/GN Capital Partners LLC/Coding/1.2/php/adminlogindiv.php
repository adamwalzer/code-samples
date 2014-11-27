<?php
		
$outputtext .= "
					<div id='login_div' class='login_div' scrollTo='true' flash='true' focus='true'>
						<a name='login_div'></a>
						<div id='login_title' class='login_title content'>
							Login
						</div>
						<form id='login_form' onSubmit='submitForm(this,".'"adminlogin"'.");return false;' method='post' class='login_form content'>

							<div id='login_error'></div>
							
							<table id='registrationtable'>

							<tr>
							<th colspan='2' class='test' id='email_test'><input type='text' id='email' name='email' onchange='testEmail(this)' onkeyup='testEmail(this)' placeholder='Email' size='20' /><div></div></th>
							</tr>

							<tr>
							<th colspan='2' class='test' id='password_test'><input type='password' id='password' name='password' onchange='testPassword(this)' onkeyup='testPassword(this)' placeholder='Password' size='20' /><div></div></th>
							</tr>

							<tr>
							<th colspan='2' class='center'><input type='submit' id='stylebutton' value='Login' title='Login' disabled /></th>
							</tr>

							<tr>
							<th colspan='2' class='center'>
								<a href='javascript:submitForm(this,".'"adminforgotpassworddiv"'.")'>Forgot Password?</a>
							</th>
							</tr>

							</table>

						</form>
						<script>
							testForm('#login_form');
							checkForm('#login_form');
						</script>
					</div>
					";
					
?>