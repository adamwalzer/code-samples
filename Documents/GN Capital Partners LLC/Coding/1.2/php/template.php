<?php

session_start();

$outputtext .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /> <!--320-->'."\n\n";

$outputtext .= "
	<html>
	";

	include "head.php";

	$outputtext .= "
		<body>
			<div id='top_nav' class='top_nav'>
				<div id='top_nav_content' class='top_nav_content'>
					<div id='top_logo' class='logo_div'>
						<a href='/'><img src='./img/burger.png' class='logo' id='logo''></a>
					</div>
				</div>
			</div>
		</div>
		<div id='content' class='page_container'>
			<div id='main_body' class='bottom_bar content'>
				<div class='section group'>
				";
				
				include $page_name;
					
	$outputtext .= "
				</div>
			</div>
		";
					
	include "bottombar.php";
			
$outputtext .= "
			</div>
		</body>
	</html>
	";

echo $outputtext;

?>