<?php

$outputtext .= "<html>\n";

	include "php/head.php";

	$outputtext .= "<body>\n";

		$outputtext .= "<div id='content'>\n";

			include "php/toplogo.php";

			$outputtext .= "<div id='topbar'>";

				include "php/topbarregister.php";

			$outputtext .= "</div>\n";

			$outputtext .= "<div id='maincontainer'>\n";
			
				$outputtext .= "<div id='mainbody'>\n";

					include "php/registerdiv.php";

				$outputtext .= "</div>\n";
				
				include "php/bottombar.php";

			$outputtext .= "</div>\n";

		$outputtext .= "</div>\n";

		//include "php/bottombar.php";

	$outputtext .= "</body>\n";

$outputtext .= "</html>\n";

echo $outputtext;

?>