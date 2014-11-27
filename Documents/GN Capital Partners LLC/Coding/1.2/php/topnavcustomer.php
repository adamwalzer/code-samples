<?php
	
$outputtext .= "

<div id='topnav'>
	<div id='topnavcontent'>
		<form action='javascript:login()' method='post' class='login_form'>
			Username <input type='text' id='username' name='username' placeholder='Username' size='20' />
			Password <input type='password' id='password' name='password' placeholder='Password' size='20' />
			<input type='submit' id='loginbutton' value='Login' title='Login' /> &nbsp;
			<a href='javascript:setMain(".'"restaurantsignuppage.php"'.")'>Register</a> &bull;
			<a href='javascript:setMain(".'"restaurantforgotpasswordpage.php"'.")'>Forgot Password?</a> &bull;
			<a href='javascript:switchToRestaurant()'>Are You A Restaurant?</a>
		</form>
	</div>
</div>\n";

?>