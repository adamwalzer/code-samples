<?php

$outputtext .= "<html>\n";

	include "php/introhead.php";

	$outputtext .= "<body>\n";
	
		include "php/topnavintro.php";

		$outputtext .= "<div id='content'>\n";

			//include "php/toplogo.php";

			$outputtext .= "<div id='topbar'>";

				include "php/topbarwelcome.php";

			$outputtext .= "</div>\n";

			$outputtext .= "<div id='maincontainer'>\n";
			
				$outputtext .= "<div id='mainbody'>\n";

					include "php/welcomediv.php";

				$outputtext .= "</div>\n";
				
				include "php/bottombar.php";

			$outputtext .= "</div>\n";

		$outputtext .= "</div>\n";

		//include "php/bottombar.php";

	$outputtext .= "</body>\n";

$outputtext .= "</html>\n";

echo $outputtext;

?>