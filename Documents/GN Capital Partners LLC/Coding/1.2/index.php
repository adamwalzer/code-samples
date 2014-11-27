<?php

/*
if($_COOKIE['restaurant'])
{
	$restaurant = true;
}
else if($_GET['restaurant']=='true')
{
	$restaurant = $_GET['restaurant'];
}
*/

if($_COOKIE['admin'])
{
	$admin = true;
}
else if($_GET['admin']=='true')
{
	$admin = $_GET['admin'];
}

if($admin)
{
	include "php/admin.php";
}
else
{
	include "php/customer.php";
}

/*
if($restaurant)
{
	include "php/restaurant.php";
}
else
{
	include "php/customer.php";
}
*/

?>