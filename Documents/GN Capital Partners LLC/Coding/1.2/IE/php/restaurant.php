<?php

session_start();

$outputtext .= "<html>\n";

	include "head.php";

	$outputtext .= "<body>\n";
	
		if($_SESSION['name'])
		{
			include "topwelcomerestaurant.php";
		}
		else
		{
			include "topnavrestaurant.php";
		}

		$outputtext .= "<div id='content'>\n";
			
			$outputtext .= "<div id='topbar'>";
			
				if($_SESSION['name'])
				{
					include "restaurantorderstop.php";
				}
				else
				{
					include "topbarrestaurantregister.php";
				}
			
			$outputtext .= "</div>";
			
			$outputtext .= "<div id='maincontainer'>\n";
			
				$outputtext .= "<div id='mainbody'>\n";

					if($_SESSION['name'])
					{
						include "restaurantordersmain.php";
					}
					else
					{
						include "restaurantregisterdiv.php";
					}
			
				$outputtext .= "</div>\n";
				
				include "bottombar.php";

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