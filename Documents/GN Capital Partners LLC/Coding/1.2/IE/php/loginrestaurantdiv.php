<?php
		
	$outputtext .= "
			<center>	
				<form onSubmit='loginRestaurant(this); return false;' method='post' class='login_form'>
	
					<table id='registrationtable'>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='usernametest'>Username</th>
					<th colspan='2' class='center'><input type='text' id='loginusername' name='username' placeholder='Username' size='20' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='emailtest'>Password</th>
					<th colspan='2' class='center'><input type='password' id='loginpassword' name='password' placeholder='Password' size='20' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='4' class='center'><input type='submit' id='stylebutton' value='Login' title='Login' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='4' class='center'>&nbsp;</th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='6' class='center'>
						<a href='javascript:registerRestaurantPopup()'>Register</a> &bull;
						<a href='javascript:forgotPasswordRestaurantPopup()'>Forgot Password?</a>
					</th>
					</tr>
	
					</table>
	
				</form>
			<center>
		";
	
    /// var_dump($error);
    // mysqli_close($dbc);
    
    echo $outputtext;
?>