<?php

session_start();

if($_SESSION['loc_id'])
{
	$loc_id = $_SESSION['loc_id'];
}
else if($_GET['loc_id'])
{
	$loc_id = $_GET['loc_id'];
}
else
{
	$loc_id = "ac_area";
}

if($_SESSION['rest_id'])
{
	$rest_id = $_SESSION['rest_id'];
}
else if($_GET['rest_id'])
{
	$rest_id = $_GET['rest_id'];
}
else
{
	$rest_id = 0;
}

//$outputtext .= "order_id=".$_COOKIE['order_id'];

if($_SESSION['order_id'])
{
	$order_id = $_SESSION['order_id'];
	
	/*
	include ('connecttowrite.php');
	$query_get_order = "SELECT * FROM Customer_Order WHERE (order_id='$order_id')";
	$result_get_order = mysqli_query($dbc, $query_get_order);
	$order_row = mysqli_fetch_array($result_get_order, MYSQLI_ASSOC);
	$last_edit = strtotime($order_row['last_edit']);
	$now15 = strtotime(date('Y-m-d H:i:s',strtotime('-15 minutes')));
	$diff = $now15 - $last_edit;
	//$outputtext .= 'now: '.$now15;
	//$outputtext .= 'last_edit: '.$last_edit;
	//$outputtext .= 'diff: '.$diff;

	if($diff > 0)
	{
		//$outputtext .= 'more than 15';
		$order_id = 0;
		$_SESSION['order_id'] = 0;
		$query_update_customer = "UPDATE Customer SET order_id=0 WHERE user_id='$user_id'";
		$result_update_customer = mysqli_query($dbc, $query_update_customer);
	}
	*/
	
	//$outputtext .= "order_id1:".$order_id;
}
else if($_GET['order_id'])
{
	$order_id = $_GET['order_id'];
}
else
{
	$order_id = 0;
}

$_SESSION['order_id'] = $order_id;


$outputtext .= "<html>\n";

	include "head.php";

	$outputtext .= "<body>\n";

		if($_SESSION['username'])
		{
			include "topwelcome.php";
		}
		else
		{
			include "topnav.php";
		}
		
		$outputtext .= "<div id='content'>\n";

			//include "toplogo.php";
			
			$outputtext .= "<div id='topbar'>\n";
			
				if($loc_id && $rest_id)
				{
					include "viewrestauranttop.php";
				}
				else if($_SESSION['current_address'])
				{
					include "categoriestopdiv.php";
				}
				else
				{
					include "topbar.php";
				}
				
			$outputtext .= "</div>\n";
			
			/*
			
			$outputtext .= "<div id='mainleft'>\n";
			
				if($loc_id)
				{
					include "leftcategories.php";
				}
				else
				{
					include "leftlocations.php";
				}
			
			$outputtext .= "</div>\n";
			
			*/
			
			$outputtext .= "<div id='maincontainer'>\n";

				$outputtext .= "<div id='mainbody'>\n";
				
					if($loc_id && $rest_id)
					{
						include "viewrestaurantdiv.php";
					}
					elseif($_SESSION['current_address'])
					{
						include "categoriesdiv.php";
					}
					elseif($_SESSION['username'])
					{
						include "currentaddressdiv.php";
					}
					else
					{
						include "currentaddresslogindiv.php";
					}
				
				$outputtext .= "</div>\n";
				
				//include "mainarea.php";
			
				include "bottombar.php";
			
			$outputtext .= "</div>\n";

		$outputtext .= "</div>\n";

		//include "php/bottombar.php";

	$outputtext .= "</body>\n";

$outputtext .= "</html>\n";

echo $outputtext;

?>