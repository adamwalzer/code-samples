<?php
	session_start();

	$outputtext .= "<div id='main'>
	<center><form action='javascript:updateInfo()' method='post' class='updateinfo_form'>";
	$outputtext .= "<h1>";
	$outputtext .= $_SESSION['Username'];
	$outputtext .= "'s Account Settings</h1><div id='accountsettingmessage'></div><hr>
	Update Information<br>
	<input type='text' id='newusername' name='newusername' placeholder='";
	$outputtext .= $_SESSION['Username'];
	$outputtext .= "' size='30' /><br>";
	$outputtext .= "<input type='text' id='newemail' name='newemail' placeholder='";
	$outputtext .= $_SESSION['Email'];
	$outputtext .= "' size='30' /><br>";
	$outputtext .= "<input type='password' id='newpassword' name='newpassword' placeholder='New Password' size='30' /><br>";
	$outputtext .= "<input type='password' id='confirmpassword' name='confirmpassword' placeholder='Confirm New Password' size='30' /><br><hr>";
	$outputtext .= "<input type='password' id='currentpassword' name='currentpassword' placeholder='Current Password' size='30' /><br>";
	$outputtext .= "<input type='submit' value='Update' />";
	
	$outputtext .= "&nbsp;
	</form>
	<center>
	</div>";
	
    /// var_dump($error);
    // mysqli_close($dbc);
    
    echo $outputtext;
?>