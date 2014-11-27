<?php

set_time_limit(0);
ignore_user_abort(1);

session_start();

include ('connecttowrite.php');

$user_id = $_SESSION['user_id'];

if($_SESSION['order_id']!=0 && $_SESSION['order_id'] && $user_id)
{	
	$query_update_customer = "
		UPDATE Customer 
		SET order_id=0, rest_id=0 
		WHERE user_id='$user_id'
		";
	$result_update_customer = mysqli_query($dbc, $query_update_customer);
	
	$_SESSION['order_id'] = 0;
	$_SESSION['rest_id'] = 0;
	$order_id = $_SESSION['order_id'];
	//$outputtext = $order_id;
	setcookie("order_id", false, time() + (60 * 15), '/', 'gottanom.com');
	
	/*
	$query_check_credentials = "SELECT * FROM Customer WHERE user_id='$user_id'";
	$result_check_credentials = mysqli_query($dbc, $query_check_credentials);
	if(!$result_check_credentials)
	{//If the QUery Failed 
		$outputtext .= "Query Failed <br>\n";
	}

	if (@mysqli_num_rows($result_check_credentials) == 1)//if Query is successfull 
	{ // A match was made.
		$_SESSION = mysqli_fetch_array($result_check_credentials, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
		$order_id = $_SESSION['order_id'];
		$outputtext .= $order_id;
	}
	*/
	
	$outputtext .= "
		<div>
			<script>
				executePage('leftcategories');
				popup['delete_order'].dialog('close');
			</script>
		</div>
		";
}
else
{
	$outputtext .= "This order has not been started.";
}

/// var_dump($error);
mysqli_close($dbc);

?>