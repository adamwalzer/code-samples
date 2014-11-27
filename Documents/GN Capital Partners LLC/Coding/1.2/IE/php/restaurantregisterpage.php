

<?php

$outputtext .= "
	<div id='top'>
	";

	include "topbarrestaurantregister.php";
	
$outputtext .= "
	</div>
	";
	
$outputtext .= "
	<div id='main'>
	";

	include "restaurantregisterdiv.php";
	
$outputtext .= "
	</div>
	";

echo $outputtext;

?>