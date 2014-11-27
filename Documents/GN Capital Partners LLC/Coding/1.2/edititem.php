<?php

session_start();

$outputtext .= "<html>\n";

	include "php/edithead.php";

	$outputtext .= "<body>\n";
	
		if($_SESSION['name'])
		{
			include "php/topwelcomerestaurantedititem.php";
		}
		else
		{
			include "php/topnavrestaurant.php";
		}

		$outputtext .= "<div id='content'>\n";
			
			$outputtext .= "<div id='topbar'>";
			
				if($_SESSION['name'])
				{
					include "php/edititemtop.php";
				}
				else
				{
					include "php/topbarrestaurantregister.php";
				}
			
			$outputtext .= "</div>";
			
			/*
			$outputtext .= "<div id='mainleft'>\n";
				
				include "leftsorttest.php";
			
			$outputtext .= "</div>\n";
			*/
			
			$outputtext .= "<div id='maincontainer'>\n";
				
				$outputtext .= "<div id='mainbody'>\n";
					
					include "php/edititemdiv.php";
				
				$outputtext .= "</div>\n";
				
				include "php/bottombar.php";

			$outputtext .= "</div>\n";

		$outputtext .= "</div>\n";

		//include "php/bottombar.php";
		
		$outputtext .= "
			<script>
				jQuery('div#pallet').show();
			</script>
			";

	$outputtext .= "</body>\n";

$outputtext .= "</html>\n";

echo $outputtext;

?>