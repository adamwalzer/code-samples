<?php
	include ('connecttowrite.php');
	
	$admin_id = $_SESSION['admin_id'];

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
				UPDATE Admin 
				SET first_name='$newfirstname' 
				WHERE admin_id='$admin_id'
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
				UPDATE Admin 
				SET last_name='$newlastname' 
				WHERE admin_id='$admin_id'
				";
			$result_update_lastname = mysqli_query($dbc, $query_update_lastname);
			$_SESSION['last_name'] = stripslashes($_POST['last_name']);
			$error[] = "Your last name has been updated.";
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
				UPDATE Admin 
				SET phone='$newphonenumber' 
				WHERE admin_id='$admin_id'
				";
			$result_update_phone = mysqli_query($dbc, $query_update_phone);
			$_SESSION['phone'] = stripslashes($_POST['phone_number']);
			$error[] = 'Your phone number has been updated.';
		}
		else
		{
			 $error[] = 'Your new phone number is invalid. ';
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
				UPDATE Admin 
				SET delivery_preference='$newdeliverypreference' 
				WHERE admin_id='$admin_id'
				";
			$result_update_deliverypreference = mysqli_query($dbc, $query_update_deliverypreference);
			$_SESSION['delivery_preference'] = stripslashes($newdeliverypreference);
			$error[] = 'Your delivery preference has been updated.';
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
				UPDATE Admin 
				SET password='$hash', salt='$salt' 
				WHERE admin_id='$admin_id'
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