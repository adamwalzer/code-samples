<?php

include ('connecttowrite.php');

$outputtext .= '<center><h1>User Activation</h1></center>';

if (isset($_GET['email']) && preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $_GET['email']))
{
	$email = $_GET['email'];
}
if (isset($_GET['key']) && (strlen($_GET['key']) == 32))//The Activation key will always be 32 since it is MD5 Hash
{
	$key = $_GET['key'];
}

if (isset($email) && isset($key))
{
	// Update the database to set the "activation" field to null
	$query_activate_account = "
		UPDATE Customer 
		SET activation=NULL
		WHERE(email ='$email' AND activation='$key')
		LIMIT 1
		"; 
	$result_activate_account = mysqli_query($dbc, $query_activate_account);
	// Print a customized message:
	if (mysqli_affected_rows($dbc) == 1)//if update query was successfull
	{
		$outputtext .= '<br>
						<center>Your account is now active.</center>';
	}
	else
	{
		// Select from the database to see if already activated
		$query_select_account = "
			SELECT * FROM Customer
			WHERE (email ='$email')
			LIMIT 1
			"; 
		$result_select_account = mysqli_query($dbc, $query_select_account);
		$_SESSION = mysqli_fetch_array($result_select_account, MYSQLI_ASSOC);
		if($_SESSION['activate'] == NULL)//if select query was successfull
		{
			$outputtext .= '<br>
							<center>Your account is already active.</center>';
		}
		else
		{
			$outputtext .= '<br>
						<center>Oops! Your account could not be activated. Please recheck the link or contact the system administrator.</center>';
		}
	}
	mysqli_close($dbc);
}
else
{
		$outputtext .= '<br>
						<center>Either your email or your activation key is not set.</center>';
}

?>