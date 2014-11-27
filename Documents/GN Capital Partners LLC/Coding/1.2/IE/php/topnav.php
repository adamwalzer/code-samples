<?php
	
$outputtext .= "

<div id='topnav'>
	<div id='topnavcontent'>
		<div id='topnavtext'>
				<a href='javascript:popupLogin()'>Login</a> &bull;
				<a href='javascript:registerPopup()'>Register</a> &bull;
				<a href='javascript:forgotPasswordPopup()'>Forgot Password?</a> &bull;
				<a href='javascript:switchToRestaurant()'>Are You A Restaurant?</a>
			";
			
include "socialmenu.php";

$outputtext .= "
		</div>
		";
		
include "toplogo.php";
	
if($_SESSION['current_address'])
{
	$outputtext .= "<div id='mainleft'>\n";

	include "leftcategories.php";			
		
	$outputtext .= "</div>\n";
}
		
$outputtext .= "
		</div>
	</div>

";

?>