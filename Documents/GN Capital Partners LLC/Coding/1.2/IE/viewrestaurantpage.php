<?php

session_start();

$outputtext .= "<html>\n";

	include "php/head.php";

	$outputtext .= "<body>\n";
	
		if($_SESSION['name'])
		{
			include "php/topwelcomerestaurant.php";
		}
		else
		{
			include "php/topnavrestaurant.php";
		}

		$outputtext .= "<div id='content'>\n";
			
			$outputtext .= "<div id='topbar'>";
			
				if($_SESSION['name'])
				{
					include "php/viewrestauranttop.php";
				}
				else
				{
					include "php/topbarrestaurantregister.php";
				}
			
			$outputtext .= "</div>";
			
			$outputtext .= "<div id='maincontainer'>\n";
			
				$outputtext .= "<div id='mainbody'>\n";

					if($_SESSION['name'])
					{
						include "php/viewrestaurantdiv.php";
					}
					else
					{
						include "php/restaurantregisterdiv.php";
					}
			
				$outputtext .= "</div>\n";
				
				include "php/bottombar.php";

			$outputtext .= "</div>\n";

		$outputtext .= "</div>\n";

		//include "php/bottombar.php";
		if($_GET['page'])
		{
			$page = $_GET['page'];
			
			$outputtext .= "
				<script>
					setMain('$page');
				</script>
				";
		}

	$outputtext .= "</body>\n";

$outputtext .= "</html>\n";

echo $outputtext;

?>