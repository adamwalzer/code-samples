<?php
		
	$outputtext .= "
			<center>	
				<form onSubmit='forgotPasswordRestaurant(this); return false;' method='post' class='updateinfo_form' autocomplete='off'>
	
					<table id='registrationtable'>
					
					<tr>
					<th colspan='6' class='center'>Enter either your username or your email address to get a new password emailed to you.</th>
					</tr>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='usernametest'>Username</th>
					<th colspan='2' class='center'><input type='text' id='usernamebox' name='username' onkeyup='' placeholder='Username' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='emailtest'>Email Address</th>
					<th colspan='2' class='center'><input type='email' id='emailbox' name='email' onkeyup='' placeholder='Email Address' size='30' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='4' class='center'><input type='submit' id='stylebutton' value='Get New Password' title='Get New Password' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
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