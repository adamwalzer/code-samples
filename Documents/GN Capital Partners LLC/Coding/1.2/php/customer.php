<?php

include "stateslist.php";

session_start();

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

//$outputtext .= '<!DOCTYPE html>'."\n\n";

$outputtext .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /> <!--320-->'."\n\n";

$outputtext .= "<html>\n";

	include "head.php";

	$outputtext .= "<body>\n";

			include "topnav.php";
		
			if(!$_SESSION['current_address'] && !$loc_id)
			{
				include "ribbon.php";
			}
					
			$outputtext .= "
						<div id='page_container' class='page_container'>
						";
		
				if($_SESSION['current_address'] || $loc_id)
				{
					$outputtext .= "
						<div id='main_body' class='bottom_bar'>
							<div class='section group'>
							";
			
					if($order_id)
					{
						include "leftorderdiv.php";
						include "viewrestaurantdiv.php";
					}
					else
					{
						include "leftcategories.php";
						
						if($rest_id)
						{
							include "viewrestaurantdiv.php";
						}
						else
						{
							include "categoriesdiv.php";
						}
					}
						
					$outputtext .= "
							</div>
						</div>
						";
				}
				else
				{	
					include "iconrow.php";
				}
		
				if(!$_SESSION['username'])
				{
					$outputtext .= "
								<div class='section group'>
									<div id='login_container' class='col span_1_of_2 span_1_to_2_of_2'>
								";
						
					include "logindiv.php";
							
					include "socialmenu.php";
							
					$outputtext .= "
									</div>
									<div id='register_container' class='col span_1_of_2 span_1_to_2_of_2'>
									";
						
					include "registerdiv.php";
							
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