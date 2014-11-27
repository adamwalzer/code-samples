<?php
	
	session_start();
	
	include_once "connect.php";

    if (empty($_POST['email']))
	{//if the email supplied is empty 
        $error[] = "You must enter your email address.";
    }
	else
	{       
            $email = $_POST['email'];
    }

	
    if (empty($_POST['password']))
	{
        $error[] = "You must enter your password.";
    }
	else
	{
        $query_get_salt = "
        	SELECT salt 
        	FROM Admin 
        	WHERE (email='$email')
        	";
        $result_get_salt = mysqli_query($dbc, $query_get_salt);
        $salt_row = mysqli_fetch_array($result_get_salt, MYSQLI_ASSOC);
        $salt = $salt_row['salt'];
        $password = $_POST['password'];
        $iterations = 10;
		$hash = crypt($password,$salt);
		for ($i = 0; $i < $iterations; ++$i)
		{
			$hash = crypt($hash . $newpassword,$salt);
		}
    }
    
	if($_GET['new_order']=='true')
	{
		$login_error = 'login_popup_error';
		$login_div = 'login_popup_div';
	}
	else
	{
		$login_error = 'login_error';
		$login_div = 'login_div';
	}
	
    if (empty($error))
	{ //if the array is empty , it means no error found
        $query_check_credentials = "
        	SELECT * FROM Admin 
        	WHERE (email='$email' AND password='$hash')
        	";
   
        $result_check_credentials = mysqli_query($dbc, $query_check_credentials);
        if(!$result_check_credentials)
		{//If the QUery Failed 
            $outputtext .= "<action type='popup' title='Login Failure'><text>Query Failed</text></action>";
        }

        if (@mysqli_num_rows($result_check_credentials) == 1)//if Query is successfull 
        { // A match was made.
            //$_SESSION = mysqli_fetch_array($result_check_credentials, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
        	
        	$_SESSION = mysqli_fetch_array($result_check_credentials, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
			
			$outputtext .= "
				<div>
					<script>
						changeURLVariable(".'"'.$inputText.'"'.",{".'"reload"'.":true});
					</script>
				</div>
				";
        }
		else
		{ 
            $outputtext .= "
            	<div id='$login_error'>
           			Either your account is inactive or username/password is incorrect.
           		</div>
           		<div id='$login_div' scrollTo='true' shake='true' focus='true' />
           		";
        }
    }
	else
	{
		$outputtext .= "
			<div id='$login_error'>
			";
        foreach ($error as $key => $values)
		{ 
            $outputtext .= $values."<br />";
        }
        $outputtext .= "
        	</div>
           	<div id='$login_div' scrollTo='true' shake='true' focus='true' />
        	";
    }
	
    /// var_dump($error);
    mysqli_close($dbc);
?>