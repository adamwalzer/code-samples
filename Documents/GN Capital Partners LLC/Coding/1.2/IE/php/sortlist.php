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
					include "sorttesttop.php";
				}
				else
				{
					include "topbarrestaurantregister.php";
				}
			
			$outputtext .= "</div>";
			
			/*
			$outputtext .= "<div id='mainleft'>\n";
				
				include "leftsorttest.php";
			
			$outputtext .= "</div>\n";
			*/
			
			$outputtext .= "<div id='maincontainer'>\n";
				
				$outputtext .= "<div id='mainbody'>\n";
				
					include "sorttest.php";
				
				$outputtext .= "</div>\n";
				
				include "bottombar.php";

			$outputtext .= "</div>\n";

		$outputtext .= "</div>\n";

		//include "php/bottombar.php";
		
		$outputtext .= "
			<script>
				loadMenuEdit();
			</script>
			";

	$outputtext .= "</body>\n";

$outputtext .= "</html>\n";

echo $outputtext;

?>