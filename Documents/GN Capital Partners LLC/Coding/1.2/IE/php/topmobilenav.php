<?php
	
$outputtext .= "

<div id='topnav'>
	<div id='topnavcontent'>
		<div id='topnavtext'>
				<a href='javascript:popupLogin()'>Login</a> &bull;
				<a href='javascript:registerPopup()'>Register</a> &bull;
				<a href='javascript:forgotPasswordPopup()'>Forgot Password?</a>
		</div>
	</div>
</div>

";

$outputtext .= "<div id='content'>\n";
		
	include "mobiletopcontent.php";
	
	$outputtext .= "<div id='mainleftcontainer'>\n";
	
		$outputtext .= "<div id='mainleft'>\n";
			
			if($_SESSION['current_address'])
			{
				include "mobileleftcategories.php";
			}
			else
			{
				include "mobilewelcomediv.php";
			}
		
		$outputtext .= "</div>\n";

		include "bottombar.php";
		
	$outputtext .= "</div>\n";

$outputtext .= "</div>\n";

?>