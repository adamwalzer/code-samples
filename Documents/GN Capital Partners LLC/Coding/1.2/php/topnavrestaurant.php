<?php
	
$outputtext .= "

<div id='topnav'>
	<div id='topnavcontent'>
		<div id='topnavtext'>
				<a href='javascript:popupRestaurantLogin()'>Login</a> &bull;
				<a href='javascript:restaurantRegisterPage()'>Register</a> &bull;
				<a href='javascript:forgotPasswordRestaurantPopup()'>Forgot Password?</a> &bull;
				<a href='javascript:switchToCustomer()'>Not A Restaurant?</a>
		";
		
include "socialmenu.php";
		
$outputtext .= "
		</div>
		";
		
include "toplogo.php";
		
$outputtext .= "	
	</div>
</div>

";

?>