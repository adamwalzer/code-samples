<?php
	
$outputtext .= "
<form action='javascript:loginrestaurant()' method='post' class='login_form'>
	<center>
	Restaurant Login
	<br>
	<br>
	Username <input type='text' id='username' name='username' placeholder='Username' size='20' />
	<br>
	Password <input type='password' id='password' name='password' placeholder='Password' size='20' />
	<br>
	<div align='right'><input type='submit' value='Login' /></div>
	<a href='javascript:setMain(".'"restaurantsignuppage.php"'.")'>Sign Up</a><br>
	<a href='javascript:setMain(".'"restaurantforgotpasswordpage.php"'.")'>Forgot Password?</a>
	</center>
</form>
";

?>