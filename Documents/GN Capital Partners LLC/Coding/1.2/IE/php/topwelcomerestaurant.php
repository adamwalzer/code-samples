<?php
	include_once "connect.php";

	$outputtext .= "
		<div id='topnav'>
			<div id='topnavcontent'>
				<div id='topnavtext'>
	";

    if (empty($_SESSION['username']))
	{//if the email supplied is empty 
        $error[] = 'You must enter your username. ';
    }
	else
	{       
            $username = $_SESSION['username'];
    }

	
    if (empty($_SESSION['password']))
	{
        $error[] = 'You must enter your password. ';
    }
	else
	{
        $password = $_SESSION['password'];
    }

	
    if (empty($error))
	{ //if the array is empty , it means no error found
        $query_check_credentials = "
        	SELECT * FROM Restaurant 
        	WHERE (username='$username' AND password='$password') AND Activation IS NULL
        	";
   
        $result_check_credentials = mysqli_query($dbc, $query_check_credentials);
        if(!$result_check_credentials)
		{//If the QUery Failed 
            $outputtext .= "Query Failed. \n";
        }

        if (@mysqli_num_rows($result_check_credentials) == 1)//if Query is successfull 
        { // A match was made.
            $_SESSION = mysqli_fetch_array($result_check_credentials, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
           
            $outputtext .= "Welcome, ";
			$outputtext .= $_SESSION['name']."!";
			$outputtext .= "&nbsp;
			<a href='javascript:logout()'>Logout</a>
			&nbsp;
			<a href='http://gottanom.com/editrestaurantinfo.php'>Account Settings</a>";
        }
		else
		{ 
            $outputtext .= "
            	&nbsp; Either your account is inactive or username/password is incorrect.
				&nbsp;
				<a href='javascript:logout()'>Try Again</a>
				&nbsp;
				";
        }

		include "socialmenu.php";
    }
	else
	{
        foreach ($error as $key => $values)
		{ 
            $outputtext .= ' '.$values.' &bull;';
        }
    }
	
	$outputtext .= "
			</div>
				";
				
	include "toplogo.php";
	
	$outputtext .= "<div id='mainleft'>\n";
				
		include "leftrestaurantmenu.php";
	
	$outputtext .= "</div>\n";
				
	$outputtext .= "
	
			</div>
		</div>
		
		";
	
    /// var_dump($error);
    mysqli_close($dbc);
?>