<?php
	include ('connecttowrite.php');
	
	$loc_id = $_SESSION['loc_id'];
	$rest_id = $_SESSION['rest_id'];

    $error = array();//Declare An Array to store any error message
    
    //$error[] = get_magic_quotes_gpc();
    
    //$error[] = $loc_id.','.$rest_id;
    
    if (empty($_POST['currentpassword']))
    {
        $error[] = 'Please enter your password. ';
    }
    else 
    {
        $currentpassword = $_POST['currentpassword'];
        $currentsalt = $_SESSION['salt'];
        $iterations = 10;
		$currenthash = crypt($currentpassword,$currentsalt);
		for ($i = 0; $i < $iterations; ++$i)
		{
			$currenthash = crypt($currenthash . $currentpassword,$currentsalt);
		}
    }
    
    if($currenthash == $_SESSION['password'])
    {
		if (empty($_POST['newusername']))
		{//if no name has been supplied 
			//do nothing
		}
		elseif (strlen($_POST['newusername']) <= 5)
		{
			$error[] = 'Your new username must be at least 6 characters. ';
		}
		elseif (!ctype_alnum($_POST['newusername']))
		{
			$error[] = 'Your new username must contain only letters and numbers. ';
		}
		else
		{
			$newusername = $_POST['newusername'];
			// Make sure the email address is available:
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
				$error[] = 'Your new username is already registered.';
			}
			else
			{
				$query_update_username = "
					UPDATE Restaurant 
					SET username='$newusername' 
					WHERE loc_id='$loc_id' AND rest_id='$rest_id'
					";
				$result_update_username = mysqli_query($dbc, $query_update_username);
				$_SESSION['username'] = stripslashes($_POST['newusername']);
				$error[] = 'Your username has been updated.';
			}
		}
	
		if (empty($_POST['newrestaurantname']))
		{
			//do nothing
		}
		elseif(!preg_match('/[a-zA-Z0-9!@#$%^&]$/', $_POST['newrestaurantname']))
		{
			$error[] = "Your restaurant's new name contains some funny characters. ";
		}
		else 
		{
			$newrestaurantname = $_POST['newrestaurantname'];
			$query_update_restaurantname = "
				UPDATE Restaurant 
				SET name='$newrestaurantname' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_restaurantname = mysqli_query($dbc, $query_update_restaurantname);
			$_SESSION['name'] = stripslashes($_POST['newrestaurantname']);
			$error[] = 'Your restaurant name has been updated.';
		}
	
		if (empty($_POST['newfirstname']))
		{
			//do nothing
		}
		elseif(!preg_match('/[a-zA-Z0-9!@#$%^&]$/', $_POST['newfirstname']))
		{
			$error[] = "Your owner's new first name contains some funny characters. ";
		}
		else 
		{
			$newfirstname = $_POST['newfirstname'];
			$query_update_firstname = "
				UPDATE Restaurant 
				SET owner_name='$newfirstname' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_firstname = mysqli_query($dbc, $query_update_firstname);
			$_SESSION['owner_name'] = stripslashes($_POST['newfirstname']);
			$error[] = "Your owner's first name has been updated.";
		}
	
		if (empty($_POST['newlastname']))
		{
			//do nothing
		}
		elseif(!preg_match('/[a-zA-Z0-9!@#$%^&]$/', $_POST['newlastname']))
		{
			$error[] = "Your owner's new last name contains some funny characters. ";
		}
		else 
		{
			$newlastname = $_POST['newlastname'];
			$query_update_lastname = "
				UPDATE Restaurant 
				SET owner_last='$newlastname' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_lastname = mysqli_query($dbc, $query_update_lastname);
			$_SESSION['owner_last'] = stripslashes($_POST['newlastname']);
			$error[] = "Your owner's last name has been updated.";
		}

		if (empty($_POST['newemail'])) {
			//do nothing
		}
		elseif (!preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", $_POST['newemail']))
		{
			$error[] = 'Your new email address is invalid.  ';
		}
		elseif ($_POST['newemail'] != $_POST['confirmemail'])
		{
			$error[] = "Your new email addresses don't match. ";
		}
		else
		{
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
				$error[] = 'Your new email address is already registered.';
			}
			else
			{
				$query_update_email = "
					UPDATE Restaurant 
					SET email='$newemail' 
					WHERE loc_id='$loc_id' AND rest_id='$rest_id'
					";
				$result_update_email = mysqli_query($dbc, $query_update_email);
				$_SESSION['email'] = stripslashes($_POST['newemail']);
				$error[] = 'Your email has been updated.';
			}
		}
	
		if (empty($_POST['newphonenumber']))
		{
			//do nothing
		}
		elseif (preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $_POST['newphonenumber']))
		{
		   //regular expression for email validation
			$newphonenumber = $_POST['newphonenumber'];
			$query_update_phone = "
				UPDATE Restaurant 
				SET phone='$newphonenumber' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_phone = mysqli_query($dbc, $query_update_phone);
			$_SESSION['phone'] = stripslashes($_POST['newphonenumber']);
			$error[] = 'Your phone number has been updated.';
		}
		else
		{
			 $error[] = 'Your new phone number is invalid. ';
		}
		
		$_POST['callinorder'] = preg_replace("/\D/", "", $_POST['callinorder']); 
		if (empty($_POST['callinorder']))
		{
			//do nothing
		}
		elseif (preg_match("/^[0-9]{10}$/i", $_POST['callinorder']))
		{
		   //regular expression for email validation
			$callinorder = $_POST['callinorder'];
			$query_update_call_in_order = "
				UPDATE Restaurant 
				SET call_in_order='$callinorder' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_call_in_order = mysqli_query($dbc, $query_update_call_in_order);
			$_SESSION['call_in_order'] = stripslashes($_POST['call_in_order']);
			$error[] = 'Your call in order phone number has been updated.';
		}
		else
		{
			 $error[] = 'Your new call in order phone number is invalid. ';
		}
		
		$_POST['fax'] = preg_replace("/\D/", "", $_POST['fax']); 
		if (empty($_POST['fax']))
		{
			//do nothing
		}
		elseif (preg_match("/^[0-9]{10}$/i", $_POST['fax']))
		{
		   //regular expression for email validation
			$fax = $_POST['fax'];
			$query_update_fax = "
				UPDATE Restaurant 
				SET fax='$fax' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_fax = mysqli_query($dbc, $query_update_fax);
			$_SESSION['fax'] = stripslashes($_POST['fax']);
			$error[] = 'Your fax number has been updated.';
		}
		else
		{
			 $error[] = 'Your new fax number is invalid. ';
		}
	
		if (empty($_POST['newaddress']))
		{
			//do nothing
		}
		elseif (!preg_match("/[0-9]+ [0-9a-z-.' ]+/i", $_POST['newaddress']))
		{
		   //regular expression for address validation
			$error[] = 'Your address is invalid. ';
		}
		else
		{
			$newaddress = $_POST['newaddress'];
			$query_update_address = "
				UPDATE Restaurant 
				SET address='$newaddress' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_address = mysqli_query($dbc, $query_update_address);
			$_SESSION['address'] = stripslashes($_POST['newaddress']);
			$error[] = 'Your address has been updated.';
		}
	
		if (empty($_POST['newcity']))
		{
			//do nothing
		}
		elseif (!preg_match("/[0-9a-z][0-9a-z-.' ]+/i", $_POST['newcity']))
		{
		   //regular expression for address validation
			$error[] = 'Your new city is invalid. ';
		}
		else
		{
			$newcity = $_POST['newcity'];
			$query_update_city = "
				UPDATE Restaurant 
				SET city='$newcity' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_city = mysqli_query($dbc, $query_update_city);
			$_SESSION['city'] = stripslashes($_POST['newcity']);
			$error[] = 'Your city has been updated.';
		}
	
		if (empty($_POST['newstate']))
		{
			//do nothing
		}
		elseif($_POST['newstate'] == $_SESSION['state'])
		{
			//do nothing
		}
		elseif (!preg_match("/[0-9a-z][0-9a-z-.' ]+/i", $_POST['newstate']))
		{
		   //regular expression for address validation
			$error[] = 'Your new state is invalid. ';
		}
		else
		{
			$newstate = $_POST['newstate'];
			$query_update_state = "
				UPDATE Restaurant 
				SET state='$newstate' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_state = mysqli_query($dbc, $query_update_state);
			$_SESSION['state'] = stripslashes($_POST['newstate']);
			$error[] = 'Your state has been updated.';
		}
	
		if (empty($_POST['newzipcode']))
		{
			//do nothing
		}
		elseif (!preg_match("/^\d{5}$/", $_POST['newzipcode']))
		{
		   //regular expression for address validation
			$error[] = 'Your new zip code is invalid. ';
		}
		else
		{
			$newzipcode = $_POST['newzipcode'];
			$query_update_zip = "
				UPDATE Restaurant 
				SET zip='$newzipcode' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_zip = mysqli_query($dbc, $query_update_zip);
			$_SESSION['zip'] = stripslashes($_POST['newzipcode']);
			$error[] = 'Your zip code has been updated.';
		}
	
		if($_POST['wedeliverchecked']=='true')
		{
			$wedeliverchecked = 1;
		}
		else
		{
			$wedeliverchecked = 0;
		}
	
		if($_POST['weallowpickupchecked']=='true')
		{
			$weallowpickupchecked = 1;
		}
		else
		{
			$weallowpickupchecked = 0;
		}
	
		if(!$wedeliverchecked && !$weallowpickupchecked)
		{
			$error[] = 'You must allow either delivery or pickup. ';
		}
		elseif($wedeliverchecked==$_SESSION['delivers'] && $weallowpickupchecked==$_SESSION['pickup'])
		{
			//do nothing
		}
		else
		{
			$query_update_services = "
				UPDATE Restaurant 
				SET delivers='$wedeliverchecked', pickup='$weallowpickupchecked' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_services = mysqli_query($dbc, $query_update_services);
			$_SESSION['delivers'] = stripslashes($_POST['wedeliverchecked']);
			$_SESSION['pickup'] = stripslashes($_POST['weallowpickupchecked']);
			$error[] = 'Your services have been updated.';
		}

		if (empty($_POST['newpassword']))
		{
			//do nothing
		}
		elseif (strlen($_POST['newpassword']) <= 5)
		{
			$error[] = 'Your new password must be at least 6 characters. ';
		}
		elseif ($_POST['newpassword'] != $_POST['confirmpassword'])
		{
			$error[] = 'Your new passwords must match. ';
		}
		elseif(!preg_match('/[a-zA-Z0-9!@#$%^&]$/', $_POST['newpassword']))
		{
			$error[] = 'Your new password contains some funny characters. ';
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
			$query_update_password = "
				UPDATE Restaurant 
				SET password='$hash', salt='$salt' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_password = mysqli_query($dbc, $query_update_password);
			$error[] = 'Your password has been updated.';
		}
		
		if($_POST['deliveryfee'] == $_SESSION['delivery_fee'])
		{
			//do nothing
		}
		elseif (empty($_POST['deliveryfee']))
		{
			//do nothing
		}
		elseif (!is_numeric($_POST['deliveryfee']))
		{
		   //regular expression for address validation
			$error[] = 'Your new delivery fee must be a number. ';
		}
		else
		{
			$deliveryfee = $_POST['deliveryfee'];
			$query_update_delivery_fee = "
				UPDATE Restaurant 
				SET delivery_fee='$deliveryfee' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_delivery_fee = mysqli_query($dbc, $query_update_delivery_fee);
			$_SESSION['delivery_fee'] = stripslashes($_POST['deliveryfee']);
			$error[] = 'Your delivery fee has been updated.';
		}
		
		if($_POST['deliveryminimum'] == $_SESSION['delivery_minimum'])
		{
			//do nothing
		}
		elseif (empty($_POST['deliveryminimum']))
		{
			//do nothing
		}
		elseif (!is_numeric($_POST['deliveryminimum']))
		{
		   //regular expression for address validation
			$error[] = 'Your new delivery minimum must be a number. ';
		}
		else
		{
			$deliveryminimum = $_POST['deliveryminimum'];
			$query_update_delivery_minimum = "
				UPDATE Restaurant 
				SET delivery_minimum='$deliveryminimum' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_delivery_minimum = mysqli_query($dbc, $query_update_delivery_minimum);
			$_SESSION['delivery_minimum'] = stripslashes($_POST['deliveryminimum']);
			$error[] = 'Your delivery minimum has been updated.';
		}
	
		if(serialize($_POST['hours'])==$_SESSION['hours'])
		{
			//do nothing
			//$error[] = 'Your hours have not been updated.';
		}
		else
		{
			$hours = $_POST['hours'];
			$serialized_hours = serialize($_POST['hours']);
			//$error[] = $hours;
			//$error[] = $serialized_hours;
			$query_update_restaurant_hours = "
				UPDATE Restaurant 
				SET hours='$serialized_hours' 
				WHERE loc_id='$loc_id' AND rest_id='$rest_id'
				";
			$result_update_restaurant_hours = mysqli_query($dbc, $query_update_restaurant_hours);
		
			$i = 0;
			while($i < 7)
			{
				$j = 0;
				while($j < 3)
				{
					$k = $j/2 + 1;
					
					if($hours[$i][$j] && $hours[$i][$j+1])
					{
						
						if($hours[$i][$j+1] <= $hours[$i][$j])
						{
							$i2 = ($i+8)%7;
						}
						else
						{
							$i2 = $i;
						}
						
						$query_get_hours = "
							SELECT * FROM Hours 
							WHERE (loc_id='$loc_id' AND rest_id='$rest_id' AND day_num='$i' AND shift_num='$k')
							";
						$result_get_hours = mysqli_query($dbc, $query_get_hours);
						
						if((@mysqli_num_rows($result_get_hours) > 0))
						{
							//$error[] = 'update '.$i.' '.$j.' '.$k;
							
							$query_update_hours = "
								UPDATE Hours 
								SET open='".$hours[$i][$j]."', close='".$hours[$i][$j+1]."', close_day_num='".$i2."' 
								WHERE loc_id='$loc_id' AND rest_id='$rest_id' AND day_num='$i' AND shift_num='$k'
								";
							$result_update_hours = mysqli_query($dbc, $query_update_hours);
						}
						else
						{
							//$error[] = 'insert '.$i.' '.$j.' '.$k;
							
							$query_set_hours = "
								INSERT INTO Hours 
										(loc_id, rest_id, day_num, close_day_num, shift_num, open, close) 
								VALUES ('$loc_id', '$rest_id', '$i', '$i2', '$k', '".$hours[$i][$j]."', '".$hours[$i][$j+1]."')
								";
							$result_set_hours = mysqli_query($dbc, $query_set_hours);
						}
					}
					
					$j = $j + 2;
				}
				$i++;
			}
			$_SESSION['hours']=serialize($_POST['hours']);
			$error[] = 'Your hours have been updated.';
		}
	
		/*
		//if you do this you will no longer know what date each restaurant registered
		$date = date("Y-m-d H:i:s");
		$query_update_date = "UPDATE Restaurant SET date='$date' WHERE loc_id='$loc_id' AND rest_id='$rest_id'";
		$result_update_date = mysqli_query($dbc, $query_update_date);
		*/
		
		foreach ($error as $key => $values)
		{	
			echo $values.'<br>';
		}
		
		$query_get_data = "
			SELECT * FROM Restaurant 
			WHERE (loc_id ='$loc_id' AND rest_id='$rest_id')
			";
		$result_get_data = mysqli_query($dbc, $query_get_data);
		$_SESSION = mysqli_fetch_array($result_get_data, MYSQLI_ASSOC);
	
		echo 'Your info is done being updated.';
	}
	else
	{
		echo "Wrong Password, Silly.";
	}
	
    if (!empty($error)) //send to Database if there's no error '
    { // If everything's OK...
    	
    }
    
    /*
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
	*/
  
    mysqli_close($dbc);//Close the DB Connection


?>