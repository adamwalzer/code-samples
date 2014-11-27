<?php
		
$outputtext .= "
	<action type='popup' name='login' title='Login'>
		<text>
			<div id='login_popup_div' class='login_div' scrollTo='true' flash='true' focus='true'>
				<form id='login_form' onSubmit='submitForm(this,".'"login&new_order=true&loc_id='.$_GET['loc_id'].'&rest_id='.$_GET['rest_id'].'&item_id='.$_GET['item_id'].'"'.");return false;' method='post' class='login_form'>

					<div id='login_popup_error'></div>
			
					<table id='registrationtable'>

					<tr>
					<th colspan='2' class='test' id='username_test'><input type='text' id='username' name='username' onchange='testUsername(this)' onkeyup='testUsername(this)' placeholder='Username' size='20' /><div></div></th>
					</tr>

					<tr>
					<th colspan='2' class='test' id='password_test'><input type='password' id='password' name='password' onchange='testPassword(this)' onkeyup='testPassword(this)' placeholder='Password' size='20' /><div></div></th>
					</tr>

					<tr>
					<th colspan='2' class='center'><input type='submit' id='stylebutton' value='Login' title='Login' disabled /></th>
					</tr>

					<tr>
					<th colspan='2' class='center'>
						<a href='javascript:popup[".'"login"'."].dialog(".'"close"'.");popup[".'"item"'."].dialog(".'"close"'.");executePage(".'"forgotpassworddiv"'.")'>Forgot Password?</a> &bull;
						<a href='javascript:popup[".'"login"'."].dialog(".'"close"'.");popup[".'"item"'."].dialog(".'"close"'.");scrollToAnchor(".'"register_div"'.")'>Register</a>
					</th>
					</tr>

					</table>

				</form>
				<script>
					testForm('#login_form');
					checkForm('#login_form');
				</script>
			</div>
		</text>
	</action>
	";
					
?>