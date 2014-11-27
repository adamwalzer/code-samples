<?php

session_start();

if($_SESSION['user_id'])
{
	session_destroy();
}

if($_GET['loc_id'])
{
	$loc_id = $_GET['loc_id'];
}
elseif($_SESSION['loc_id'])
{
	$loc_id = $_SESSION['loc_id'];
}

if($_GET['rest_id'])
{
	$rest_id = $_GET['rest_id'];
}
elseif($_SESSION['rest_id'])
{
	$rest_id = $_SESSION['rest_id'];
}
else
{
	$rest_id = 0;
}

if($_GET['order_id'])
{
	$order_id = $_GET['order_id'];
}
elseif($_SESSION['order_id'])
{
	$order_id = $_SESSION['order_id'];
}
else
{
	$order_id = 0;
}

$_SESSION['order_id'] = $order_id;

$outputtext .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /> <!--320-->'."\n\n";

$outputtext .= "<html>\n";

	include "adminhead.php";

	$outputtext .= "<body>\n";

			include "admintopnav.php";
					
			$outputtext .= "
						<div id='page_container' class='page_container'>
						";
		
				if($_SESSION['admin_id'])
				{
					$outputtext .= "
						<div id='main_body' class='bottom_bar'>
							<div class='section group'>
							";

					include "adminleftmenu.php";
					
					if($admin_id)
					{
						include "viewrestaurantdiv.php";
					}
					else
					{
						include "categoriesdiv.php";
					}
						
					$outputtext .= "
							</div>
						</div>
						";
				}
				else
				{
					$outputtext .= "
								<div class='section group'>
									<div id='login_container' class='col span_2_of_2'>
								";
						
					include "adminlogindiv.php";
							
					$outputtext .= "
									</div>
									";
				}
			
				include "bottombar.php";
			
			$outputtext .= "</div>\n";

	$outputtext .= "</body>\n";

$outputtext .= "</html>\n";

echo $outputtext;

?>