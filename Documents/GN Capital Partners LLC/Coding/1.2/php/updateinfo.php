<?php
	include ('connecttowrite.php');
	
	$user_id = $_SESSION['user_id'];

    $error = array();//Declare An Array to store any error message
    
    if (empty($_POST['current_password']))
    {
        $error[] = 'Please enter your password. ';
    }
    else 
    {
        $currentpassword = $_POST['current_password'];
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
		if (empty($_POST['username']))
		{//if no name has been supplied 
			//do nothing
		}
		elseif (strlen($_POST['username']) <= 5)
		{
			$error[] = 'Your new username must be at least 6 characters. ';
		}
		elseif (!ctype_alnum($_POST['username']))
		{
			$error[] = 'Your new username must contain only letters and numbers. ';
		}
		else
		{
			$newusername = $_POST['username'];
			// Make sure the email address is available:
			$query_verify_username = "
				SELECT * FROM Customer 
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
					UPDATE Customer 
					SET username='$newusername' 
					WHERE user_id='$user_id'
					";
				$result_update_username = mysqli_query($dbc, $query_update_username);
				$_SESSION['username'] = stripslashes($_POST['newusername']);
				$error[] = 'Your username has been updated.';
			}
		}
		
		if (empty($_POST['email'])) {
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
				$error[] = 'Your new email address is already registered.';
			}
			else
			{
				$query_update_email = "
					UPDATE Customer 
					SET email='$newemail' 
					WHERE user_id='$user_id'
					";
				$result_update_email = mysqli_query($dbc, $query_update_email);
				$_SESSION['email'] = stripslashes($_POST['newemail']);
				$error[] = 'Your email has been updated.';
			}
		}
	
		if (empty($_POST['phone_number']))
		{
			//do nothing
		}
		elseif (preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $_POST['phone_number']))
		{
		   //regular expression for email validation
			$newphonenumber = $_POST['phone_number'];
			$query_update_phone = "
				UPDATE Customer 
				SET phone='$newphonenumber' 
				WHERE user_id='$user_id'
				";
			$result_update_phone = mysqli_query($dbc, $query_update_phone);
			$_SESSION['phone'] = stripslashes($_POST['phone_number']);
			$error[] = 'Your phone number has been updated.';
		}
		else
		{
			 $error[] = 'Your new phone number is invalid. ';
		}
	
		if (empty($_POST['first_name']))
		{
			//do nothing
		}
		elseif(!preg_match('/[a-zA-Z0-9!@#$%^&]$/', $_POST['first_name']))
		{
			$error[] = "Your new first name contains some funny characters. ";
		}
		else 
		{
			$newfirstname = $_POST['first_name'];
			$query_update_firstname = "
				UPDATE Customer 
				SET first_name='$newfirstname' 
				WHERE user_id='$user_id'
				";
			$result_update_firstname = mysqli_query($dbc, $query_update_firstname);
			$_SESSION['first_name'] = stripslashes($_POST['first_name']);
			$error[] = "Your first name has been updated.";
		}
	
		if (empty($_POST['last_name']))
		{
			//do nothing
		}
		elseif(!preg_match('/[a-zA-Z0-9!@#$%^&]$/', $_POST['last_name']))
		{
			$error[] = "Your new last name contains some funny characters. ";
		}
		else 
		{
			$newlastname = $_POST['last_name'];
			$query_update_lastname = "
				UPDATE Customer 
				SET last_name='$newlastname' 
				WHERE user_id='$user_id'
				";
			$result_update_lastname = mysqli_query($dbc, $query_update_lastname);
			$_SESSION['last_name'] = stripslashes($_POST['last_name']);
			$error[] = "Your last name has been updated.";
		}
		
		if (empty($_POST['delivery_preference']))
		{
			//do nothing
		}
		elseif($_POST['delivery_preference'] == $_SESSION['delivery_preference'])
		{
			//do nothing
		}
		else
		{
			$newdeliverypreference = $_POST['delivery_preference'];
			$query_update_deliverypreference = "
				UPDATE Customer 
				SET delivery_preference='$newdeliverypreference' 
				WHERE user_id='$user_id'
				";
			$result_update_deliverypreference = mysqli_query($dbc, $query_update_deliverypreference);
			$_SESSION['delivery_preference'] = stripslashes($newdeliverypreference);
			$error[] = 'Your delivery preference has been updated.';
		}
	
		if (empty($_POST['address']))
		{
			//do nothing
		}
		elseif (!preg_match("/[0-9]+ [0-9a-z-.' ]+/i", $_POST['address']))
		{
		   //regular expression for address validation
			$error[] = 'Your address is invalid. ';
		}
		else
		{
			$newaddress = $_POST['address'];
			$query_update_address = "
				UPDATE Customer 
				SET del_address='$newaddress' 
				WHERE user_id='$user_id'
				";
			$result_update_address = mysqli_query($dbc, $query_update_address);
			$_SESSION['del_address'] = stripslashes($newaddress);
			$_SESSION['current_address'] = stripslashes($newaddress);
			$error[] = 'Your address has been updated.';
		}
	
		if (empty($_POST['city']))
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
			$newcity = $_POST['city'];
			$query_update_city = "
				UPDATE Customer 
				SET del_city='$newcity' 
				WHERE user_id='$user_id'
				";
			$result_update_city = mysqli_query($dbc, $query_update_city);
			$_SESSION['del_city'] = stripslashes($newcity);
			$_SESSION['current_city'] = stripslashes($newcity);
			$error[] = 'Your city has been updated.';
		}
	
		if (empty($_POST['state']))
		{
			//do nothing
		}
		elseif($_POST['state'] == $_SESSION['del_state'])
		{
			//do nothing
		}
		elseif (!preg_match("/[0-9a-z][0-9a-z-.' ]+/i", $_POST['state']))
		{
		   //regular expression for address validation
			$error[] = 'Your new state is invalid. ';
		}
		else
		{
			$newstate = $_POST['state'];
			$query_update_state = "
				UPDATE Customer 
				SET del_state='$newstate' 
				WHERE user_id='$user_id'
				";
			$result_update_state = mysqli_query($dbc, $query_update_state);
			$_SESSION['del_state'] = stripslashes($newstate);
			$_SESSION['current_state'] = stripslashes($newstate);
			$error[] = 'Your state has been updated.';
		}
	
		if (empty($_POST['zip_code']))
		{
			//do nothing
		}
		elseif (!preg_match("/^\d{5}$/", $_POST['zip_code']))
		{
		   //regular expression for address validation
			$error[] = 'Your new zip code is invalid. ';
		}
		else
		{
			$newzipcode = $_POST['zip_code'];
			$query_update_zip = "
				UPDATE Customer 
				SET del_zip='$newzipcode' 
				WHERE user_id='$user_id'
				";
			$result_update_zip = mysqli_query($dbc, $query_update_zip);
			$_SESSION['del_zip'] = stripslashes($newzipcode);
			$_SESSION['current_zip'] = stripslashes($newzipcode);
			$error[] = 'Your zip code has been updated.';
		}
		
		if (empty($_POST['bill_name']))
		{
			//do nothing
		}
		elseif(!preg_match('/[a-zA-Z0-9!@#$%^&]$/', $_POST['newbillname']))
		{
			$error[] = "Your new billing name contains some funny characters. ";
		}
		else 
		{
			$newbillname = $_POST['bill_name'];
			$query_update_billname = "
				UPDATE Customer 
				SET bill_name='$newbillname' 
				WHERE user_id='$user_id'
				";
			$result_update_billname = mysqli_query($dbc, $query_update_billname);
			$_SESSION['bill_name'] = stripslashes($newbillname);
			$error[] = "Your billing name has been updated.";
		}
		
		if (empty($_POST['bill_address']))
		{
			//do nothing
		}
		elseif (!preg_match("/[0-9]+ [0-9a-z-.' ]+/i", $_POST['newbilladdress']))
		{
		   //regular expression for address validation
			$error[] = 'Your billing address is invalid. ';
		}
		else
		{
			$newbilladdress = $_POST['bill_address'];
			$query_update_billaddress = "
				UPDATE Customer 
				SET bill_address='$newbilladdress' 
				WHERE user_id='$user_id'
				";
			$result_update_billaddress = mysqli_query($dbc, $query_update_billaddress);
			$_SESSION['bill_address'] = stripslashes($newbilladdress);
			$error[] = 'Your billing address has been updated.';
		}
	
		if (empty($_POST['bill_city']))
		{
			//do nothing
		}
		elseif (!preg_match("/[0-9a-z][0-9a-z-.' ]+/i", $_POST['bill_city']))
		{
		   //regular expression for address validation
			$error[] = 'Your new billing city is invalid. ';
		}
		else
		{
			$newbillcity = $_POST['bill_city'];
			$query_update_billcity = "
				UPDATE Customer 
				SET bill_city='$newbillcity' 
				WHERE user_id='$user_id'
				";
			$result_update_billcity = mysqli_query($dbc, $query_update_billcity);
			$_SESSION['bill_city'] = stripslashes($newbillcity);
			$error[] = 'Your billing city has been updated.';
		}
	
		if (empty($_POST['bill_state']))
		{
			//do nothing
		}
		elseif($_POST['bill_state'] == $_SESSION['bill_state'])
		{
			//do nothing
		}
		elseif (!preg_match("/[0-9a-z][0-9a-z-.' ]+/i", $_POST['bill_state']))
		{
		   //regular expression for address validation
			$error[] = 'Your new billing state is invalid. ';
		}
		else
		{
			$newbillstate = $_POST['bill_state'];
			$query_update_billstate = "
				UPDATE Customer 
				SET bill_state='$newbillstate' 
				WHERE user_id='$user_id'
				";
			$result_update_billstate = mysqli_query($dbc, $query_update_billstate);
			$_SESSION['bill_state'] = stripslashes($newbillstate);
			$error[] = 'Your billing state has been updated.';
		}
	
		if (empty($_POST['bill_zip_code']))
		{
			//do nothing
		}
		elseif (!preg_match("/^\d{5}$/", $_POST['bill_zip_code']))
		{
		   //regular expression for address validation
			$error[] = 'Your new billing zip code is invalid. ';
		}
		else
		{
			$newbillzipcode = $_POST['bill_zip_code'];
			$query_update_billzip = "
				UPDATE Customer 
				SET bill_zip='$newbillzipcode' 
				WHERE user_id='$user_id'
				";
			$result_update_billzip = mysqli_query($dbc, $query_update_billzip);
			$_SESSION['bill_zip'] = stripslashes($newbillzipcode);
			$error[] = 'Your billing zip code has been updated.';
		}
		
		if (empty($_POST['charity']))
		{
			//do nothing
		}
		else
		{
			$query_delete_charity = "
				DELETE FROM Customer_Charity 
				WHERE user_id='$user_id'
				";
			$result_delete_charity = mysqli_query($dbc, $query_delete_charity);
			
			foreach ($_POST['charity'] as $key => $value)
			{
				$query_get_charity = "
					SELECT * FROM Charity 
					WHERE charity_id = '$key'
					";
				$result_get_charity = mysqli_query($dbc, $query_get_charity);
				$charity_row = mysqli_fetch_array($result_get_charity, MYSQLI_ASSOC);
				
				if($value=='true')
				{
					$donate=1;
				}
				else
				{
					$donate=0;
				}
				
				$charity_name = sanitize($dbc,$charity_row['charity_name']);
				$query_insert_charity = "
					INSERT INTO Customer_Charity
							(user_id, charity_id, charity_name, donate)
					VALUES ('$user_id', '$key', '$charity_name', '$donate')
					";
				$result_insert_charity = mysqli_query($dbc, $query_insert_charity);
			}
			$error[] = 'Your charities have been updated. ';
		}

		if (empty($_POST['password']))
		{
			//do nothing
		}
		elseif (strlen($_POST['password']) <= 5)
		{
			$error[] = 'Your new password must be at least 6 characters. ';
		}
		elseif ($_POST['password'] != $_POST['confirm_password'])
		{
			$error[] = 'Your new passwords must match. ';
		}
		elseif(!preg_match('/[a-zA-Z0-9!@#$%^&]$/', $_POST['password']))
		{
			$error[] = 'Your new password contains some funny characters. ';
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
			$query_update_password = "
				UPDATE Customer 
				SET password='$hash', salt='$salt' 
				WHERE user_id='$user_id'
				";
			$result_update_password = mysqli_query($dbc, $query_update_password);
			$error[] = 'Your password has been updated.';
		}
	
		/*
		//if you do this you will no longer know what date each Customer registered
		$date = date("Y-m-d H:i:s");
		$query_update_date = "UPDATE Customer SET date='$date' WHERE user_id='$user_id'";
		$result_update_date = mysqli_query($dbc, $query_update_date);
		*/
		
		foreach ($error as $key => $values)
		{	
			$outputtext .= $values.'<br>';
		}
		
		//$query_get_data = "SELECT * FROM Customer WHERE user_id='$user_id'";
		//$result_get_data = mysqli_query($dbc, $query_get_data);
		//$_SESSION = mysqli_fetch_array($result_get_data, MYSQLI_ASSOC);
	
		$outputtext .= 'Your info is done being updated.';
	}
	else
	{
		$outputtext .= "Wrong Password, Silly.";
	}
	
	$outputtext = "
		<action type='popup' name='update_info' title='Update Message'>
			<button>
				{ text: 'Thanks', click: function() { $( this ).dialog( 'close' ); } }
			</button>
			<text>
				".$outputtext."
			</text>
		</action>
		";
  
    mysqli_close($dbc);//Close the DB Connection


?>