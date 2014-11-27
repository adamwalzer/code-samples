<?php
	
$outputtext .= "

<div id='topnav'>
	<div id='topnavcontent'>
		<div id='toplogo' class='logo_div'>
			<a href='javascript:location.reload()'><img src='./img/burger.png' class='logo' id='burger' width='100%'></a>
		</div>
		<div id='topnavtext'>
			<div class='slogan_div'>
				FOOD AT YOUR FINGERTIPS
			</div>
			<div class='navigate_div'>
				<a href='#login_div'>Login</a> &bull;
				<a href='#register_div'>Register</a>
			</div>
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