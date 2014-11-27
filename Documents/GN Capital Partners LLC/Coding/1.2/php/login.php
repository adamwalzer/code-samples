<?php
	
	session_start();
	
	include_once "connect.php";

    if (empty($_POST['username']))
	{//if the email supplied is empty 
        $error[] = "You must enter your username.";
    }
	else
	{       
            $username = $_POST['username'];
    }

	
    if (empty($_POST['password']))
	{
        $error[] = "You must enter your password.";
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
        	SELECT * FROM Customer 
        	WHERE (username='$username' AND password='$hash') AND Activation IS NULL
        	";
   
        $result_check_credentials = mysqli_query($dbc, $query_check_credentials);
        if(!$result_check_credentials)
		{//If the QUery Failed 
            $outputtext .= "<action type='popup' title='Login Failure'>Query Failed</action>";
        }

        if (@mysqli_num_rows($result_check_credentials) == 1)//if Query is successfull 
        { // A match was made.
            //$_SESSION = mysqli_fetch_array($result_check_credentials, MYSQLI_ASSOC);//Assign the result of this query to SESSION Global Variable
        	
        	$current_['input'] = $_SESSION['input'];
        	
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
			
			$_SESSION['input'] = $current_['input'];
			
			if($_SESSION['loc_id'])
			{
				if($inputText)
				{
					$inputText .= '&';
				}
				$inputText .= 'loc_id='.$_SESSION['loc_id'];
			}
			
			if($_SESSION['rest_id'])
			{
				if($inputText)
				{
					$inputText .= '&';
				}
				$inputText .= 'rest_id='.$_SESSION['rest_id'];
			}
			
			if($_GET['new_order']=='true')
			{
        		$outputtext .= "
            		<div>
            			<script>
            				popup[".'"login"'."].dialog(".'"close"'.");
            				executePage(".'"startneworderpopup&loc_id='.$_GET['loc_id'].'&rest_id='.$_GET['rest_id'].'&item_id='.$_GET['item_id'].'"'.");
            			</script>
            		</div>
            		";
        	}
        	else
        	{
            	$outputtext .= "
            		<div>
            			<script>
            				changeURLVariable(".'"'.$inputText.'"'.",{".'"reload"'.":true});
            			</script>
            		</div>
            		";
            }
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