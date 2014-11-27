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
			UPDATE Customer 
			SET password='$hash', salt='$salt' 
			WHERE username='$username'
			";
    	$result_update_password = mysqli_query($dbc, $query_update_password);
    	$query_get_user = "
    		SELECT * FROM Customer 
    		WHERE username='$username'
    		";
    	$result_get_user = mysqli_query($dbc, $query_get_user);
		$user_row = mysqli_fetch_array($result_get_user, MYSQLI_ASSOC);
		if($user_row['email'])
		{
			$email = $user_row['email'];
			$activation = $user_row['activation'];
		}
	}
	elseif(!empty($_POST['email']))
	{
		$email=$_POST['email'];
		$query_update_password = "
			UPDATE Customer 
			SET password='$hash', salt='$salt' 
			WHERE email='$email'
			";
    	$result_update_password = mysqli_query($dbc, $query_update_password);
    	$query_get_user = "SELECT * FROM Customer WHERE email='$email'";
    	$result_get_user = mysqli_query($dbc, $query_get_user);
		$user_row = mysqli_fetch_array($result_get_user, MYSQLI_ASSOC);
		if($user_row['username'])
		{
			$username = $user_row['username'];
			$activation = $user_row['activation'];
		}
	}
	else
	{
		$error[] = 'You must enter either your username or your email address to reset your password.';
	}
	
	if (empty($error)) //send to Database if there's no error '
    {
		if (mysqli_affected_rows($dbc) == 1)
		{ //If the Insert Query was successfull.
			// Send the email:
			if($activation)
			{
				$message = "Your account was never activated. To activate your account, please click or copy the link below.\n\n";
				$message .= WEBSITE_URL . '/activate.php?email=' . urlencode($email) . "&key=$activation" . "\n\n";
				$message = "Your new password is below. Please login using this password and then change your password to something you can remember." . "\n\n";
				$message .= 'Username: ' . $username . "\n";
				$message .= 'Password: ' . $newpassword . "\n\n";
			}
			else
			{
				$message = "Your new password is below. Please login using this password and then change your password to something you can remember." . "\n\n";
				$message .= 'Username: ' . $username . "\n";
				$message .= 'Password: ' . $newpassword;
			}
			
			$mailreturn = mail($email, 'Password Reset', $message, 'From: security@gottanom.com');
		
			// Flush the buffered output.

			// Finish the page:
			$error[] = 'Your password has been reset, and your new password has been emailed to '.$email.'.<br/>Please login with your new password and change it to something you can remember. ';
		}
		else
		{ // If it did not run OK.
			$error[] = 'Your password could not be reset. This is most likely because the username or email you provided is not in our database.';
		}
	}

	echo "<div id='forgot_password_message'>
		";
	
	foreach ($error as $key => $values)
	{	
		echo $values.'<br/>';
	}
	
	echo "
		</div>
		<div>
			<script>
				$('#forgot_password_message').addClass('test');
				$('#forgot_password_message').addClass('content');
			</script>
		</div>
		";
  
    mysqli_close($dbc);//Close the DB Connection


?>