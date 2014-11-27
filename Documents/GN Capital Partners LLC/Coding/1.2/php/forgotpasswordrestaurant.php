<?php
	include ('connecttowrite.php');
	
	$newpassword = md5(uniqid(rand(), true));
	$salt = md5(uniqid(rand(), true));
	$iterations = 10;
	$hash = crypt($newpassword,$salt);
	for ($i = 0; $i < $iterations; ++$i)
	{
		$hash = crypt($hash . $newpassword,$salt);
	}
	
	if(!empty($_POST['username']))
	{
		$username=$_POST['username'];
		$query_update_password = "
			UPDATE Restaurant 
			SET password='$hash', salt='$salt' 
			WHERE username='$username'
			";
    	$result_update_password = mysqli_query($dbc, $query_update_password);
    	$query_get_email = "
    		SELECT email AS email 
    		FROM Restaurant 
    		WHERE username='$username'
    		";
    	$result_get_email = mysqli_query($dbc, $query_get_email);
		$email_row = mysqli_fetch_array($result_get_email, MYSQLI_ASSOC);
		if($email_row['email'])
		{
			$email = $email_row['email'];
		}
	}
	elseif(!empty($_POST['email']))
	{
		$email=$_POST['email'];
		$query_update_password = "
			UPDATE Restaurant 
			SET password='$hash', salt='$salt' 
			WHERE email='$email'
			";
    	$result_update_password = mysqli_query($dbc, $query_update_password);
    	$query_get_username = "
    		SELECT username AS username 
    		FROM Restaurant 
    		WHERE email='$email'
    		";
    	$result_get_username = mysqli_query($dbc, $query_get_username);
		$username_row = mysqli_fetch_array($result_get_username, MYSQLI_ASSOC);
		if($username_row['username'])
		{
			$username = $username_row['username'];
		}
	}
	else
	{
		$error[] = 'You must enter either your username or your password to reset your password.';
	}
	
	if (empty($error)) //send to Database if there's no error '
    {
		if (mysqli_affected_rows($dbc) == 1)
		{ //If the Insert Query was successfull.
			// Send the email:
			$message = " Your new password is below. Please login using this password and then change your password to something you can remember.	\n\n";
			$message .= 'Username: ' . $username . "	\n";
			$message .= 'Password: ' . $newpassword;
			$mailreturn = mail($email, 'Password Reset', $message, 'From: restaurant@gottanom.com');
		
			// $error[] = $mailreturn;
			// Flush the buffered output.

			// Finish the page:
			$error[] = 'Your password has been reset, and your new password has been emailed to '.$email.'.<br>Please login with your new password and change it to something you can remember. ';
		}
		else
		{ // If it did not run OK.
			$error[] = 'Your password could not be reset. This is most likely because the username or email you provided is not in our database.';
		}
	}

	echo "<center>
		";
	
	foreach ($error as $key => $values)
	{	
		echo $values.'<br>';
	}
	
	echo "
		</center>
		";
  
    mysqli_close($dbc);//Close the DB Connection


?>