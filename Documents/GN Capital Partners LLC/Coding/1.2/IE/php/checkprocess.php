<?php

include "connect.php";
	
if($_GET['loc_id'])
{
	$loc_id = $_GET['loc_id'];
}
elseif($_SESSION['loc_id'])
{
	$loc_id = $_SESSION['loc_id'];
}

if($_GET['rest_id'])
{
	$rest_id = $_GET['rest_id'];
}
elseif($_SESSION['rest_id'])
{
	$rest_id = $_SESSION['rest_id'];
}
else
{
	$rest_id = 0;
}

if($loc_id && $rest_id)
{
	$query_get_restaurant = "
		SELECT * FROM Restaurant 
		WHERE (loc_id='$loc_id' AND rest_id='$rest_id') 
		LIMIT 1
		";

	$result_get_restaurant = mysqli_query($dbc, $query_get_restaurant);
	if(!$result_get_restaurant)
	{//If the QUery Failed 
		$process = 'Query Failed ';
	}

	if (@mysqli_num_rows($result_get_restaurant) == 1)//if Query is successfull 
	{ // A match was made.
		$rest_row = mysqli_fetch_array($result_get_restaurant, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
	
		$process = $rest_row['process'];
	}
}

if($process == 0)
{
	$outputtext = "
		<div>
			<script>
				$"."form = $('#".$_GET['formID']."');
				submitForm($"."form[0],'placeorder');
			</script>
		</div>
		";
}
elseif($process == 1)
{
	$outputtext = "
		<div>
			<script>
				$"."form = $('#".$_GET['formID']."');
				placeOrderStripe($"."form);
			</script>
		</div>
		";
}
else
{
	$outputtext = "
		<div type='popup' name='process' title='Unable To Process Your Order'>
			<button>
				{ text: 'OK', click: function() { $( this ).dialog( 'close' ); } }
			</button>
			<text>
				We are unable to process this order. Most likely due to multiple processing requests.
			</text>
		</div>
		";
}

/// var_dump($error);
mysqli_close($dbc);

?>