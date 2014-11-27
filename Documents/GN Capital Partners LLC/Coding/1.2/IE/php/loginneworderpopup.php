<?php
	include_once "connect.php";

	$outputtext .= "<center>\n";

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
        	FROM Customer 
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
        	SELECT * FROM Customer 
        	WHERE (username='$username' AND password='$hash') AND Activation IS NULL
        	";
   
        $result_check_credentials = mysqli_query($dbc, $query_check_credentials);
        if(!$result_check_credentials)
		{//If the QUery Failed 
            $outputtext .= "Query Failed <br>\n";
        }

        if (@mysqli_num_rows($result_check_credentials) == 1)//if Query is successfull 
        { // A match was made.
            //$_SESSION = mysqli_fetch_array($result_check_credentials, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
			
			if($_SESSION['current_address'])
        	{
				$current_['on_beach'] = $_SESSION['current_on_beach'];
				$current_['address'] = $_SESSION['current_address'];
				$current_['city'] = $_SESSION['current_city'];
				$current_['state'] = $_SESSION['current_state'];
				$current_['zip'] = $_SESSION['current_zip'];
    		
				$_SESSION = mysqli_fetch_array($result_check_credentials, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
			
				$_SESSION['current_on_beach'] = $current_['on_beach'];
				$_SESSION['current_address'] = $current_['address'];
				$_SESSION['current_city'] = $current_['city'];
				$_SESSION['current_state'] = $current_['state'];
				$_SESSION['current_zip'] = $current_['zip'];
			}
			else
			{
				$_SESSION = mysqli_fetch_array($result_check_credentials, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
			
				$_SESSION['current_on_beach'] = $_SESSION['del_on_beach'];
				$_SESSION['current_address'] = $_SESSION['del_address'];
				$_SESSION['current_city'] = $_SESSION['del_city'];
				$_SESSION['current_state'] = $_SESSION['del_state'];
				$_SESSION['current_zip'] = $_SESSION['del_zip'];
			}
			
			$loc_id = $_GET['loc_id'];
			$rest_id = $_GET['rest_id'];
			$item_id = $_GET['item_id'];
			
			$outputtext .= "
				<center>	
				<form action='javascript:loginNewOrderDialog.dialog(".'"close"'.");' method='post' class='login_form'>
	
					<table id='registrationtable'>
					
					<tr>
					<th colspan='6' class='center'>
				";
			
            $outputtext .= "Welcome, ";
            if($_SESSION['first_name'])
            {
            	$outputtext .= $_SESSION['first_name']."!  You are now logged in.";
            }
            else
            {
            	$outputtext .= $_SESSION['username']."! You are now logged in.";
            }
			$outputtext .= "
					</th>
						</tr>
						
						<tr>
						<th colspan='1' class='center'>&nbsp;</th>
						<th colspan='4' class='center'>&nbsp;</th>
						<th colspan='1' class='center'>&nbsp;</th>
						</tr>
						
						<tr>
						<th colspan='1' class='center'>&nbsp;</th>
						<th colspan='4' class='center'><input type='submit' id='stylebutton' value='Thanks' title='Thanks' /></th>
						<th colspan='1' class='center'>&nbsp;</th>
						</tr>
		
						</table>
		
					</form>
				<center>";
        }
		else
		{ 
            $outputtext .= "
				<div>
					<ol>
						<li>Either your account is inactive or username/password is incorrect.</li>
					</ol>
				</div>
				<br>
				<br>
				<center>	
					<form onSubmit='loginwithoutreload(this,".'"'.$loc_id.'"'.",".'"'.$rest_id.'"'.",".'"'.$item_id.'"'.");return false;' method='post' class='login_form'>
		
						<table id='registrationtable'>
						
						<tr>
						<th colspan='1' class='center'>&nbsp;</th>
						<th colspan='2' class='optional' id='usernametest'>Username</th>
						<th colspan='2' class='center'><input type='text' id='username' name='username' placeholder='Username' size='20' /></th>
						<th colspan='1' class='center'>&nbsp;</th>
						</tr>
		
						<tr>
						<th colspan='1' class='center'>&nbsp;</th>
						<th colspan='2' class='optional' id='emailtest'>Password</th>
						<th colspan='2' class='center'><input type='password' id='password' name='password' placeholder='Password' size='20' /></th>
						<th colspan='1' class='center'>&nbsp;</th>
						</tr>
						
						<tr>
						<th colspan='1' class='center'>&nbsp;</th>
						<th colspan='4' class='center'><input type='submit' id='stylebutton' value='Login' title='Login' /></th>
						<th colspan='1' class='center'>&nbsp;</th>
						</tr>
						
						<tr>
						<th colspan='1' class='center'>&nbsp;</th>
						<th colspan='4' class='center'>&nbsp;</th>
						<th colspan='1' class='center'>&nbsp;</th>
						</tr>
						
						<tr>
						<th colspan='6' class='center'>
							<a href='javascript:registerPopup()'>Register</a> &bull;
							<a href='javascript:forgotPasswordPopup()'>Forgot Password?</a>
						</th>
						</tr>
		
						</table>
		
					</form>
				<center>
			</div>
			";
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
		
		$outputtext .= "
			<br>
			<br>
			<center>	
				<form onSubmit='loginwithoutreload(this,".'"'.$loc_id.'"'.",".'"'.$rest_id.'"'.",".'"'.$item_id.'"'.");return false;' method='post' class='login_form'>
	
					<table id='registrationtable'>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='usernametest'>Username</th>
					<th colspan='2' class='center'><input type='text' id='username' name='username' placeholder='Username' size='20' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
	
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='2' class='optional' id='emailtest'>Password</th>
					<th colspan='2' class='center'><input type='password' id='password' name='password' placeholder='Password' size='20' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='4' class='center'><input type='submit' id='stylebutton' value='Login' title='Login' /></th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='1' class='center'>&nbsp;</th>
					<th colspan='4' class='center'>&nbsp;</th>
					<th colspan='1' class='center'>&nbsp;</th>
					</tr>
					
					<tr>
					<th colspan='6' class='center'>
						<a href='javascript:registerPopup()'>Register</a> &bull;
						<a href='javascript:forgotPasswordPopup()'>Forgot Password?</a>
					</th>
					</tr>
	
					</table>
	
				</form>
			<center>
		</div>
		";
    }
	
	$outputtext .= "</center>\n";
	
	echo $outputtext;
	
    /// var_dump($error);
    mysqli_close($dbc);
?>