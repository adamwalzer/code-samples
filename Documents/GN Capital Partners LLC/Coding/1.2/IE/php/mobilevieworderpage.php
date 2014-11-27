<?php
	include "connect.php";
	
	include "mobileviewordertop.php";
	
	include "mobilevieworderdiv.php";
	
	echo $outputtext;
	
	mysqli_close($dbc);
?>