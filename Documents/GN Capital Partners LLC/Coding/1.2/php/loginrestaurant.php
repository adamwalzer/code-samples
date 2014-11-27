<?php
	include_once "connect.php";

    if (empty($_POST['username']))
	{//if the email supplied is empty 
        $error[] = 'You must enter your username. ';
    }
	else
	{       
            $username = $_POST['username'];
    }

	
    if (empty($_POST['password']))
	{
        $error[] = 'You must enter your password. ';
    }
	else
	{
        $query_get_salt = "
        	SELECT salt 
        	FROM Restaurant 
        	WHERE (username='$username')
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

	
    if (empty($error))
	{ //if the array is empty , it means no error found
        $query_check_credentials = "
        	SELECT * FROM Restaurant 
        	WHERE (username='$username' AND password='$hash') AND Activation IS NULL
        	";
   
        $result_check_credentials = mysqli_query($dbc, $query_check_credentials);
        if(!$result_check_credentials)
		{//If the QUery Failed 
            $outputtext .= "Query Failed <br>\n";
        }

        if (@mysqli_num_rows($result_check_credentials) == 1)//if Query is successfull 
        { // A match was made.
            $_SESSION = mysqli_fetch_array($result_check_credentials, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
            
            $outputtext .= "Welcome";
        }
		else
		{ 
            $outputtext .= "Either your account is inactive or username/password is incorrect.";
        }
    }
	else
	{
		$outputtext .= '<div> <ol>';
        foreach ($error as $key => $values)
		{ 
            $outputtext .= '	<li>'.$values.'</li>';
        }
        $outputtext .= '</ol></div>';
    }
	
	echo $outputtext;
	
    /// var_dump($error);
    mysqli_close($dbc);
?>