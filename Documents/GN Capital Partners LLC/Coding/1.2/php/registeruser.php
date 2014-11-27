<?php
	include ('connecttowrite.php');

    $error = array();//Declare An Array to store any error message
    
    if (!$_POST['terms_of_use'])
    {
    	$error[] = 'You must agree to the terms of use.';
    }
    
    if (empty($_POST['username']))
    {//if no name has been supplied 
        $error[] = 'Please enter a username. ';//add to array "error"
    }
    elseif (strlen($_POST['username']) <= 5)
    {
    	$error[] = 'Your username must be at least 6 characters. ';
    }
    elseif (!ctype_alnum($_POST['username']))
    {
    	$error[] = 'Your username must contain only letters and numbers. ';
    }
    else
    {
        $newusername = $_POST['username'];
        $query_verify_username = "
        	SELECT * FROM Customer 
        	WHERE username ='$newusername'
        	";
        $result_verify_username = mysqli_query($dbc, $query_verify_username);
        
        if (!$result_verify_username)
        {//if the Query Failed ,similar to if($result_verify_email==false)
            $error[] = ' A database rrror occured. ';
        }

        if (mysqli_num_rows($result_verify_username) > 0)
        {
        	$error[] = 'Your username is already registered.';
        }
    }

    if (empty($_POST['email'])) {
        $error[] = 'Please enter your email address. ';
    }
    elseif (!preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", $_POST['email']))
    {
    	$error[] = 'Your email address is invalid.  ';
    }
    elseif ($_POST['email'] != $_POST['confirm_email'])
    {
    	$error[] = "Your email addresses don't match. ";
    }
    else
    {
		//regular expression for email validation
		$newemail = $_POST['email'];
		// Make sure the email address is available:
		$query_verify_email = "
			SELECT * FROM Customer 
			WHERE email ='$newemail'
			";
		$result_verify_email = mysqli_query($dbc, $query_verify_email);
		if (!$result_verify_email)
		{//if the Query Failed ,similar to if($result_verify_email==false)
			$error[] = ' A database error occured. ';
		}

		if (mysqli_num_rows($result_verify_email) > 0)
		{
			$error[] = 'Your email address is already registered.';
		}
    }
    
    if (empty($_POST['phone_number']))
    {
        $error[] = 'Please enter your phone number. ';
    }
    elseif (preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $_POST['phone_number']))
    {
	   //regular expression for email validation
		$newphonenumber = $_POST['phone_number'];
	}
	else
	{
		 $error[] = 'Your phone number is invalid. ';
	}


    if (empty($_POST['password']))
    {
        $error[] = 'Please enter your password. ';
    }
    elseif (strlen($_POST['password']) <= 5)
    {
        $error[] = 'Your password must be at least 6 characters. ';
    }
    elseif ($_POST['password'] != $_POST['confirm_password'])
    {
    	$error[] = 'Your passwords must match. ';
    }
    elseif(!preg_match('/[a-zA-Z0-9!@#$%^&]$/', $_POST['password']))
    {
    	$error[] = 'Your password contains some funny characters. ';
	}
    else 
    {
        $newpassword = $_POST['password'];
        $salt = md5(uniqid(rand(), true));
        $iterations = 10;
		$hash = crypt($newpassword,$salt);
		for ($i = 0; $i < $iterations; ++$i)
		{
			$hash = crypt($hash . $newpassword,$salt);
		}
    }


    if (empty($error)) //send to Database if there's no error '
    { // If everything's OK...

		// Create a unique  activation code:
		$activation = md5(uniqid(rand(), true));
		
		if($_POST['first_name'])
		{
			$newfirstname = $_POST['first_name'];
		}
		else
		{
			$newfirstname = NULL;
		}
		
		if($_POST['last_name'])
		{
			$newlastname = $_POST['last_name'];
		}
		else
		{
			$newlastname = NULL;
		}
		
		$date = date("Y-m-d H:i:s");

		$query_insert_user = "
			INSERT INTO Customer 
					(username, password, salt, email, first_name, last_name, phone, loc_id, date, activation) 
			VALUES ( '$newusername', '$hash', '$salt', '$newemail', '$newfirstname', '$newlastname', '$newphonenumber', 'ac_area', '$date', '$activation')
			";
		
		$result_insert_user = mysqli_query($dbc, $query_insert_user);
		
		if (!$result_insert_user)
		{
			$error[] = 'The attempt to add you to the database failed. ';
		}

		if (mysqli_affected_rows($dbc) == 1)
		{ //If the Insert Query was successfull.
			// Send the email:
			$message = "To activate your account, please click or copy the link below.\n\n";
			$message .= WEBSITE_URL . '/activate.php?email=' . urlencode($newemail) . "&key=$activation";
			$mailreturn = mail($newemail, 'Registration Confirmation', $message, 'From: noreply@gottanom.com');
			
			// Flush the buffered output.

			// Finish the page:
			$error[] = 'Thank you for registering! A confirmation email has been sent to '.$newemail.'.<br>Please click on the activation link to activate your account.<br>Don&#39;t forget to check your spam folder.';
			$error[] = '
				<script>
					$("#userregform").trigger("reset");
				</script>
				';
			
			//$error[] = '<br><br><input type="submit" onClick="$( this ).dialog('."'close'".');" id="stylebutton" value="Thanks" title="Thanks" />';
		}
		else
		{ // If it did not run OK.
			$error[] = 'You could not be registered due to a system error. We apologize for any inconvenience.';
		}

    }
	
	$outputtext .= "<div id='register_error'>";
	
	foreach ($error as $key => $values)
	{	
		$outputtext .= $values."<br>";
	}
	
	$outputtext .= "
					</div>
					<div id='register_div' scrollTo='true' shake='true' focus='true' />
				";
  
    mysqli_close($dbc);//Close the DB Connection


?>