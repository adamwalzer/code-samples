<?php

session_start();

foreach($_POST as $key => $value)
{
	if($value=='true')
	{
		$_SESSION[$key] = 'checked';
		//$outputtext .= '<javascript>alert("checked");</javascript>';
	}
	elseif($value=='false')
	{
		$_SESSION[$key] = '';
		//$outputtext .= '<javascript>alert("checked");</javascript>';
	}
	else
	{
		$_SESSION[$key] = $value;
	}
	
	//$outputtext .= '<variable name="'.$key.'" value="'.$_SESSION[$key].'">';
	$outputtext .= '<variable name="'.$key.'">'.$_SESSION[$key].'</variable>';
}

if($user_id = $_SESSION['user_id'])
{
	include ("connecttowrite.php");
	
	$query_update_customer = "UPDATE Customer SET ";
	//del_address='$newaddress'
	$result_update_customer = mysqli_query($dbc, $query_update_customer);
	
	$variable_names = array("on_beach","address","city","state","zip");

	foreach($variable_names as $value)
	{
		if(!$_SESSION['del_'.$value])
		{
			if($query_update_customer != "UPDATE Customer SET ")
			{
				$query_update_customer .= ", ";
			}
			
			$query_update_customer .= "del_".$value."='".$_SESSION['current_'.$value]."' ";
			$_SESSION['del_'.$value] = $_SESSION['current_'.$value];
			$outputtext .= '<variable name="del_'.$value.'">'.$_SESSION['current_'.$value].'</variable>';
		}
	}
	
	$query_update_customer .= "
		WHERE user_id='$user_id'
		";
	$result_update_customer = mysqli_query($dbc, $query_update_customer);
	
	$outputtext .= $query_update_customer;
}

$outputtext .= '
	<div>
		<script>
			location.reload();
		</script>
	</div>
	';

?>