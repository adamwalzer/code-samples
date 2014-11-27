<?php

if($_COOKIE['restaurant'])
{
	$restaurant = true;
}
else if($_GET['restaurant']=='true')
{
	$restaurant = $_GET['restaurant'];
}

if($restaurant)
{
	include "php/restaurant.php";
}
else
{
	include "php/customer.php";
}

?>