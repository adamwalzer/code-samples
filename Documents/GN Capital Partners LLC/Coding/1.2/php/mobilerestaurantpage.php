<?php
	include "connect.php";
	
	include "mobileviewrestauranttop.php";
	
	include "mobileviewrestaurantdiv.php";
	
	echo $outputtext;
	
	mysqli_close($dbc);
?>