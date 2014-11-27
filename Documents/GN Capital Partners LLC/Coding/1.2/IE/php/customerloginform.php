<?php
	
$outputtext .= "<form action='javascript:login()' method='post' class='login_form'>
					<center>
					<div id='topform'>
					Customer Login
					<br>
					Username <input type='text' id='email' name='email' placeholder='Username' size='20' />
					<br>
					Password <input type='password' id='password' name='password' placeholder='Password' size='20' />
					<br>
					<div align='right'><input type='submit' value='Login' /></div>
					<a href='javascript:setMain(".'"forgotpasswordpage.php"'.")'>Forgot Password?</a>
					</div>
					</center>
				</form>";
				
echo $outputtext;

?>