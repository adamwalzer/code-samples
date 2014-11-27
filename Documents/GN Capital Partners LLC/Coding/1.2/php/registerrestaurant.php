<?php
	include ('connecttowrite.php');
	
	$loc_id = 'ac_area';

    $error = array();//Declare An Array to store any error message
     
    if (empty($_POST['newusername']))
    {//if no name has been supplied 
        $error[] = 'Please enter a username. ';//add to array "error"
    }
    elseif (strlen($_POST['newusername']) <= 5)
    {
    	$error[] = 'Your username must be at least 6 characters. ';
    }
    elseif (!ctype_alnum($_POST['newusername']))
    {
    	$error[] = 'Your username must contain only letters and numbers. ';
    }
    else
    {
        $newusername = $_POST['newusername'];
        $query_verify_username = "
        	SELECT * FROM Restaurant 
        	WHERE username ='$newusername'
        	";
        $result_verify_username = mysqli_query($dbc, $query_verify_username);
        
        if (!$result_verify_username)
        {//if the Query Failed ,similar to if($result_verify_email==false)
            $error[] = ' A database error occured. ';
        }

        if (mysqli_num_rows($result_verify_username) > 0)
        {
        	$error[] = 'Your username is already registered.';
        }
    }
    
    if (empty($_POST['newrestaurantname']))
    {
        $error[] = "Please enter your restaurant's name. ";
    }
    elseif(!preg_match('/[a-zA-Z0-9!@#$%^&]$/', $_POST['newrestaurantname']))
    {
    	$error[] = "Your restaurant's name contains some funny characters. ";
	}
    else 
    {
        $newrestaurantname = $_POST['newrestaurantname'];
    }

    if (empty($_POST['newemail'])) {
        $error[] = 'Please enter your email address. ';
    }
    elseif (!preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", $_POST['newemail']))
    {
    	$error[] = 'Your email address is invalid.  ';
    }
    elseif ($_POST['newemail'] != $_POST['confirmemail'])
    {
    	$error[] = "Your email addresses don't match. ";
    }
    else
    {
		//regular expression for email validation
		$newemail = $_POST['newemail'];
		// Make sure the email address is available:
		$query_verify_email = "
			SELECT * FROM Restaurant 
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
    
    if (empty($_POST['newphonenumber']))
    {
        $error[] = 'Please enter your phone number. ';
    }
    elseif (preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $_POST['newphonenumber']))
    {
	   //regular expression for email validation
		$newphonenumber = $_POST['newphonenumber'];
	}
	else
	{
		 $error[] = 'Your phone number is invalid. ';
	}
	
	if (empty($_POST['newaddress']))
    {
        $error[] = 'Please enter your address. ';
    }
    elseif (!preg_match("/[0-9]+ [0-9a-z-.' ]+/i", $_POST['newaddress']))
    {
	   //regular expression for address validation
		$error[] = 'Your address is invalid. ';
	}
	else
	{
		 $newaddress = $_POST['newaddress'];
	}
	
	if (empty($_POST['newcity']))
    {
        $error[] = 'Please enter your city. ';
    }
    elseif (!preg_match("/[0-9a-z][0-9a-z-.' ]+/i", $_POST['newcity']))
    {
	   //regular expression for address validation
		$error[] = 'Your city is invalid. ';
	}
	else
	{
		 $newcity = $_POST['newcity'];
	}
	
	if (empty($_POST['newstate']))
    {
        $error[] = 'Please enter your state. ';
    }
    elseif (!preg_match("/[0-9a-z][0-9a-z-.' ]+/i", $_POST['newstate']))
    {
	   //regular expression for address validation
		$error[] = 'Your state is invalid. ';
	}
	else
	{
		 $newstate = $_POST['newstate'];
	}
	
	if (empty($_POST['newzipcode']))
    {
        $error[] = 'Please enter your zipcode. ';
    }
    elseif (!preg_match("/^\d{5}$/", $_POST['newzipcode']))
    {
	   //regular expression for address validation
		$error[] = 'Your zip code is invalid. ';
	}
	else
	{
		 $newzipcode = $_POST['newzipcode'];
	}
	
	$wedeliverchecked = $_POST['wedeliverchecked'];
	$weallowpickupchecked = $_POST['weallowpickupchecked'];
	
	if(!($wedeliverchecked || $weallowpickupchecked))
	{
		$error[] = 'You must allow either delivery or pickup. ';
	}
	
	if(!$_POST['termsofusechecked'])
	{
		$error[] = 'You must agree to the terms of use. ';
	}

    if (empty($_POST['newpassword']))
    {
        $error[] = 'Please enter your password. ';
    }
    elseif (strlen($_POST['newpassword']) <= 5)
    {
        $error[] = 'Your password must be at least 6 characters. ';
    }
    elseif ($_POST['newpassword'] != $_POST['confirmpassword'])
    {
    	$error[] = 'Your passwords must match. ';
    }
    elseif(!preg_match('/[a-zA-Z0-9!@#$%^&]$/', $_POST['newpassword']))
    {
    	$error[] = 'Your password contains some funny characters. ';
	}
    else 
    {
        $newpassword = $_POST['newpassword'];
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
		
		if($_POST['newfirstname'])
		{
			$newfirstname = $_POST['newfirstname'];
		}
		else
		{
			$newfirstname = NULL;
		}
		
		if($_POST['newlastname'])
		{
			$newlastname = $_POST['newlastname'];
		}
		else
		{
			$newlastname = NULL;
		}
		
		$date = date("Y-m-d H:i:s");
		$tax_percent = 7;

		$query_insert_user = "
			INSERT INTO Restaurant 
					(username, password, salt, loc_id, email, name, owner_name, owner_last, phone, address, city, state, zip, delivers, pickup, tax_percent, date, activation) 
			VALUES ( '$newusername', '$hash', '$salt', '$loc_id', '$newemail', '$newrestaurantname', '$newfirstname', '$newlastname', '$newphonenumber', '$newaddress', '$newcity', '$newstate', '$newzipcode', $wedeliverchecked, $weallowpickupchecked, '$tax_percent', '$date', '$activation')
			";
		
		$result_insert_user = mysqli_query($dbc, $query_insert_user);
		
		if (!$result_insert_user)
		{
			$error[] = 'The attempt to add you to the database failed. ';
		}

		if (mysqli_affected_rows($dbc) == 1)
		{ //If the Insert Query was successfull.
			// Send the email:
			$message = " To activate your account, please click or copy the link below.	\n\n";
			$message .= WEBSITE_URL . '/activaterestaurant.php?email=' . urlencode($newemail) . "&key=$activation";
			$mailreturn = mail($newemail, 'Registration Confirmation', $message, 'From: restaurant@gottanom.com');
			
			// Flush the buffered output.

			// Finish the page:
			$error[] = 'Thank you for registering! A confirmation email has been sent to '.$newemail.'.<br>Please click on the activation link to activate your account. ';
		}
		else
		{ // If it did not run OK.
			$error[] = 'You could not be registered due to a system error. We apologize for any inconvenience.';
		}

    }

	foreach ($error as $key => $values)
	{	
		echo $values.'<br>';
	}
  
    mysqli_close($dbc);//Close the DB Connection


?>